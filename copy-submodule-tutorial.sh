#!/bin/bash
echo "Copying files in folder in  ../tutorial/ ()";
VARIABLE="$(git log -1 --pretty=%B)"
cp -r dist/* ../tutorial/ && cd ../tutorial/
git add .
git commit -m "${VARIABLE}"
