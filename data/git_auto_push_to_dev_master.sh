#!/bin/bash

# update json. if update make update.txt
echo "php update...";

php update.php

# if update.txt exist
if [[ -f ./update.txt ]]; then

echo "git update...";

  echo 'git checkout development'
  git checkout development;echo '';

  echo 'git pull'
  git pull;  echo '';

  echo 'git add .'
  git add .;echo '';

  echo 'git commit -m ';
  git commit -m "auto update";echo'';

  echo 'git push';
  git push;echo'';

  echo 'git checkout master';
  git checkout master;echo'';

  echo 'git pull';
  git pull;echo'';

  echo 'git tag -d ';
  git tag -d master-2020-02-04;
  git tag -d refs/heads/master;echo'';

  echo ' git merge -m ';
  git merge -m "auto update" development;echo'';

  echo 'git push';
  git push;echo'';
  git checkout development;echo'';

  echo "wait 5min for tweet...";

  sleep 300;

  php tweet.php

  rm update.txt

fi


echo "done."
