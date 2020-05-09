git checkout development;
git pull;
git add .;
git commit -m "auto update";
git push;
git checkout master;
git pull;
git tag -d master-2020-02-04
git tag -d refs/heads/master
git merge  -m "auto update" development;
git push;
git checkout development;
echo "done";
