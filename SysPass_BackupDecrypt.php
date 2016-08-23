<?php
//---------------------------------------------------------------------------
//mcrypt wird verwendet und muss als zusätzliches packet instaliert werden !! 
error_reporting(0); 		//error reports verschwinden
$file = $argv[1];           //einlesen der aufrufparameter
//"-------------------------------------------------------------------

if(strlen($argv[1])<=0){
    echo "\nPlease provide the SysPass_Backup.sql:\n";
    $file=trim(fgets(STDIN));
    echo "\n";
}

if($argv[1]=="--help" or $argv[1]=="-h"){
    echo "\nPlese use this script as follow \n";
    echo "php /path/to/SysPass_BackupDecrypt.php /path/to/SysPass_Backup.sql \n\n";
  exit();
}

if(strlen($argv[2])<=0){
    echo "\nPlease provide the Masterkey :\n";
            $salt=trim(fgets(STDIN));

    echo "\n";
}


if(file_exists($file)){
   $filep = file_get_contents($file);                      //inhalt der Datei
    $pos=strpos($filep,"INSERT INTO `accounts` VALUES");                  //Erstes ergebniss für Accounts
    $end=strpos($filep,"DROP TABLE IF EXISTS `authTokens`;")-30;                 //Letztes ergebniss für Accounts
    $TPaccs=substr($filep,$pos,($end-$pos));
}else{
    echo "\n The is no such File ".$file."\n\n";
    exit();
}


//$TPaccs = str_replace("INSERT INTO ".$prefix."items VALUES(","",$TPaccs,$count);
$TPdata = explode("INSERT INTO `accounts` VALUES(",$TPaccs);
unset($TPdata[0]);
$TPdata=array_values($TPdata);
$count = sizeof($TPdata);



//########################### decrypt ###################################



for ($i=0;$i<$count;$i++){
    $TPvalues = explode("'",$TPdata[$i],-1);
    echo $TPvalues[9]."\n";
       $changedPass=unpack('H*hex',$TPvalues[7]);
        $newPass=hexToStr($changedPass['hex']);
       
       $changedIV=unpack('H*hex',$TPvalues[9]);
    var_dump($changedIV);
         $newIV=hexToStr($changedIV['hex']);
   var_dump(unpack('H*hex',$newIV));
    
   $password = decrypt($salt,$newIV,$newPass);
   
   
   $mask = "|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n\n";
    printf($mask, "Name",$TPvalues[1],"User",$TPvalues[5],"URL",$TPvalues[3],"Notes",$TPvalues[11],"Password",$password);
}


function decrypt($key,$iv,$pw){


    /* Open module, and create IV */
    $td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
    //$key = substr($key, 0, 16);
    
    /* Initialize encryption handle */
    if (mcrypt_generic_init($td, $key, $iv) != -1) {
        $p_t = mdecrypt_generic($td, $pw);

        /* Clean up */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
    }
return $p_t;
}

function hexToStr($hex){
   $string='';
   $hex=str_split($hex,"2");
   for($i=0; $i< count($hex); $i++){
	if($hex[$i]=="5c"){
	 if($hex[$i+1]=="22"){		// replace "
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="5c"){		// replace \
          $hex=retArray($hex,$i); } 
  	 if($hex[$i+1]=="27"){		// replace '
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="25"){		// replace %
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="62"){		// replace b with backspace
	  $hex[$i+1]="08";
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="72"){		// replace r with carriage return
	  $hex[$i+1]="0d";
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="74"){		// replace t with tab
	  $hex[$i+1]="09";
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="5a"){		// replace Z with ASCII 26
	  $hex[$i+1]="1a";
          $hex=retArray($hex,$i);}
 	 if($hex[$i+1]=="30"){		// replace 0 with NULL 
	  $hex[$i+1]="00";
          $hex=retArray($hex,$i);}
	 if($hex[$i+1]=="6e"){		// replace n with Linefeed
	  $hex[$i+1]="0a";
          $hex=retArray($hex,$i);}
	 }
    }
 
   for($i=0;$i< count($hex); $i++){
    $string.=chr(hexdec($hex[$i]));
   }
   return $string;
   }


function retArray($arry , $pos){
    $newArry=array();
     for($i=0;$i< count($arry);$i++){
           if($i!=$pos){
                 array_push($newArry,$arry[$i]);
                   }
            }

return $newArry;
}


?>
