#  Basic ksh startup
. /usr/local/lib/std.v2.kshrc

case $- in
*i*)

   # Put interactive-only Bourne shell compatible login startup here.

   # Uncomment one or more of the following for paranoid behavior

   #set -o noclobber
   #alias cp='cp -i'
   #alias mv='mv -i'
   #alias rm='rm -i'

   # Uncomment the following if you want traditional behavior

   #unalias vi
   #unalias ls

esac
