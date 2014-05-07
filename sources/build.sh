#!/bin/bash


# compile custom rainbow
python ./rainbow/util/compile.py c csharp coffeescript css d generic go haskell html java javascript lua php python r ruby scheme shell smalltalk 

# compile core rainbow
python ./rainbow/util/compile.py --core

# copy over rainbow files
echo "processing rainbow"
mv ./rainbow/js/rainbow-custom.min.js  ../js/rainbow-custom.min.js
cp ./rainbow/js/rainbow.js ../js/dev/rainbow.js
cp ./rainbow/js/rainbow.min.js ../js/dev/rainbow.min.js
rm -rf ../js/dev/language/*
mkdir ../js/dev/language/
cp ./rainbow/js/language/* ../js/dev/language/

echo "processing linenumbers"
rm ./rainbow.linenumbers/js/rainbow.linenumbers.min.js
java -jar /usr/local/compiler-latest/compiler.jar --js ./rainbow.linenumbers/js/rainbow.linenumbers.js --js_output_file ./rainbow.linenumbers/js/rainbow.linenumbers.min.js

# line numbers
cp ./rainbow.linenumbers/js/rainbow.linenumbers.js ../js/rainbow.linenumbers.js
cp ./rainbow.linenumbers/js/rainbow.linenumbers.min.js ../js/rainbow.linenumbers.min.js
