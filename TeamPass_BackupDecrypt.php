<?php
//---------------------------------------------------------------------------
//mcrypt wird verwendet und muss als zusätzliches packet instaliert werden !! 
error_reporting(0); 		//error reports verschwinden
$file = $argv[1];           //einlesen der aufrufparameter
//"-------------------------------------------------------------------

if(strlen($argv[1])<=0){
    echo "\nPlease provide the TeamPass_Backup.sql:\n";
    $file=trim(fgets(STDIN));
}

if($argv[1]=="--help" or $argv[1]=="-h"){
    echo "\nPlese use this script as follow \n";
    echo "php /path/to/TeamPass_BackupDecrypt.php /path/to/TeamPass_Backup.sql \n\n";
  exit();
}

if(strlen($argv[2])<=0){
    echo "\nPlease provide the Salt key :\n";
            $salt=trim(fgets(STDIN));
}


if(file_exists($file)){
   $filep = file_get_contents($file);                      //inhalt der Datei
    $prefix =substr($filep,11,strpos($filep,"api;")-11);
    $pos=strpos($filep,"INSERT INTO ".$prefix."items VALUES");                  //Erstes ergebniss für Accounts
    $end=strpos($filep,"DROP TABLE ".$prefix."items_edition;");                 //Letztes ergebniss für Accounts
    $TPaccs=substr($filep,$pos,($end-$pos));
}else{
    echo "\n The is no such File ".$file."\n\n";
    exit();
}


//$TPaccs = str_replace("INSERT INTO ".$prefix."items VALUES(","",$TPaccs,$count);
$TPdata = explode("INSERT INTO ".$prefix."items VALUES(",$TPaccs);
unset($TPdata[0]);
$TPdata=array_values($TPdata);

$count = sizeof($TPdata);



//########################### decrypt ###################################



for ($i=0;$i<$count;$i++){
    $TPvalues = explode("\"",$TPdata[$i],-1);
    $password = decrypt($salt,$TPvalues[9],$TPvalues[7]);
   
    // echo $password."\n\n";
    $mask = "|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n|%-11s |%-30.30s \n\n";
    printf($mask, "Label",$TPvalues[3],"Description",htmlspecialchars_decode($TPvalues[5]),"URL",$TPvalues[13],"Login",$TPvalues[19],"Email",$TPvalues[27],"Password",$password);
}


function decrypt($key,$iv,$pw){
    $blocksz=strlen($key);
    $pwBIN = hex2bin(trim($pw));
    $ivBIN = hex2bin($iv);
    


    /* Open module, and create IV */
    $td = mcrypt_module_open('rijndael-128','', 'cbc', '');
    $key = substr($key, 0, 16);
    
    /* Initialize encryption handle */
    if (mcrypt_generic_init($td, $key, $ivBIN) != -1) {
        $p_t = mdecrypt_generic($td, $pwBIN);

        /* Clean up */
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
    }
return $p_t;
}

?>
