#!/bin/bash
echo "Copying files in folder in  ../tutorial/ ()";
cp -r dist/* ../tutorial/ && cd ../tutorial/
git add .
