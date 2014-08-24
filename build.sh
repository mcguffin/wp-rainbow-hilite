#!/bin/bash

export CLOSURE_COMPILER="/usr/local/compiler-latest/compiler.jar"

# compile custom rainbow
python ./sources/rainbow/util/compile.py c csharp coffeescript css d generic go haskell html java javascript lua php python r ruby scheme shell smalltalk 

# compile core rainbow
python ./sources/rainbow/util/compile.py --core


# copy over rainbow files
echo "processing rainbow"
mv ./sources/rainbow/js/rainbow-custom.min.js  ./js/rainbow-custom.min.js
cp ./sources/rainbow/js/rainbow.js ./js/dev/rainbow.js
cp ./sources/rainbow/js/rainbow.min.js ./js/dev/rainbow.min.js
rm -rf ./js/dev/language/*
mkdir ./js/dev/language/
cp ./sources/rainbow/js/language/* ./js/dev/language/


# line numbers
echo "processing linenumbers"
rm ./sources/rainbow.linenumbers/js/rainbow.linenumbers.min.js
java -jar \
	$CLOSURE_COMPILER \
	--js ./sources/rainbow.linenumbers/js/rainbow.linenumbers.js \
	--js_output_file ./sources/rainbow.linenumbers/js/rainbow.linenumbers.min.js

cp ./sources/rainbow.linenumbers/js/rainbow.linenumbers.js ./js/rainbow.linenumbers.js
cp ./sources/rainbow.linenumbers/js/rainbow.linenumbers.min.js ./js/rainbow.linenumbers.min.js


# MCE will break if we use ADVANCED_OPTIMIZATIONS
echo "processing mce script"
java -jar \
	$CLOSURE_COMPILER \
	--js ./js/wp-rainbow-mce.js \
	--js_output_file ./js/wp-rainbow-mce.min.js \

echo "processing options script"
java -jar \
	$CLOSURE_COMPILER \
	--js ./js/wp-rainbow-options.js \
	--js_output_file ./js/wp-rainbow-options.min.js \

