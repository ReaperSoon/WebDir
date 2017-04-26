#!/bin/bash

FORCE="n"
if [[ $1 == "--force" ]];then
   FORCE=""
fi
   
find /home/steve/transmission/* -type d -not -path '*/\.*' -not -path '*/iPhone' -not -path '*/steve' -exec cp -v${FORCE}R /home/steve/transmission/.error.php /home/steve/transmission/.htaccess /home/steve/transmission/.images/ /home/steve/transmission/.index.php /home/steve/transmission/.sorttable.js /home/steve/transmission/.style.css  /home/steve/transmission/.video.js {} \;
