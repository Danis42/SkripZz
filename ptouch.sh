#!/bin/bash
##
## Script to Print images and text on to ptouch printer linux
##
##yaout -S ptouch
##

path="/home/danii/Downloads/idoitQR/"
size="70"
flag="1"

usage="$(basename "$0") [-h] [-p n] [-t n] [-m] [-s n] -- program to print scaled pics and text

where:
    -h  show this help text
    -p  path to file
    -t  text to display
    -m  mini version
    -s  size of text (default: 70)"

while getopts ':hp:t:ms:' option; do
  case "$option" in
    h) echo "$usage"
       exit
       ;;
    p) path=$OPTARG
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit
       ;;
    t) text=$OPTARG
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&3
       echo "$usage" >&3
       exit
       ;;
    m) tex2=$text"_mini"
       flag="0"
       ;;
    s) size=$OPTARG
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&4
       echo "$usage" >&4
       exit
       ;;
   \?) printf "illegal option: -%s\n" "$OPTARG" >&5
       echo "$usage" >&5
       exit
       ;;
  esac
done
shift $((OPTIND - 1))


if [ "$flag" -eq "1" ]; then
 echo "normal"
 ptouch-print --image $path$text.png --fontsize $size --text $text
fi

if [ "$flag" -eq "0" ]; then
 echo "mini"
 convert -size 40x20 xc:White -gravity Center -weight 700 -pointsize 20 -annotate 0 "$text" "$path$tex2.png"
 mogrify -rotate "90" "$path$tex2.png"
 mogrify -rotate "90" "$path$text.png"
 montage "$path$tex2.png" "$path$text.png" -geometry +2 "$path$tex2.png"
 mogrify -rotate "-90" "$path$tex2.png"
 ptouch-print --image $path$tex2.png
fi
