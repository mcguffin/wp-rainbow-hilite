#!/bin/bash

CWDNAME=${PWD##*/}

if [ "$CWDNAME" == "trunk" ]; then
	parent=${PWD%/*}
	CWDNAME=${parent##*/}
	ZIPNAME=../../$CWDNAME.zip
else 
	ZIPNAME=../$CWDNAME.zip
fi

rm $ZIPNAME

zip -r $ZIPNAME . -x "README.md" "mkzip.sh" "build.sh" ".*" "*/.*" "./sources/*"
