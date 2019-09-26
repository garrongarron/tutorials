#!/bin/bash
echo "Copying files in folder in  ../repo/";
VARIABLE="$(git log -1 --pretty=%B)"
echo "1";
/bin/cp -rf ../repo/.git ../.git
echo "2";
rm -r ../repo/*
echo "3";
/bin/cp -rf ../.git ../repo/.git
echo "4";
rm -r ../.git
echo "5";
cp -r dist/* ../repo/
echo "6";
cd ../repo/
echo "1.1";
chmod -R 777 .
echo "7";
git add .
echo "8";
git commit -m "${VARIABLE}"
echo "9";
git push origin master
echo "DONE!";
