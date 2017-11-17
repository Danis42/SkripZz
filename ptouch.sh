##
## Script to Print images and text on to ptouch printer linux
##
##yaout -S ptouch
##

path="/home/danii/Downloads/idoitQR/"
size="70"

usage="$(basename "$0") [-h] [-p n] [-t n] [-s n] -- program to print scaled pics and text


where:
    -h  show this help text
    -p  path to file
    -t  text to display
    -s  size of text (default: 70)"

while getopts ':hp:t:s:' option; do
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
    :) printf "missing argument for -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit
       ;;
    s) size=$OPTARG
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit
       ;;
   \?) printf "illegal option: -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit
       ;;
  esac
done
shift $((OPTIND - 1))

ptouch-print --image $path$text.png --fontsize $size --text $text
