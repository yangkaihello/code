#!/bin/bash
gitRoot="git path"
importRoot="project path"
cd $gitRoot
git stash 
git pull
git stash clear

#-f '- application/database.php'
rsync -au -pv -f '- .*' -f '- application/database.php' -f '- public/uploads' -f '- runtime' $gitRoot $importRoot