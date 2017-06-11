WordPress Rainbow Hilite
========================

Includes [rainbow.js](http://craig.is/making/rainbows) a syntax highlighting script written by [Craig Campbell](http://craig.is/).
Line numbering through [rainbow.linenumbers](https://github.com/Sjeiti/rainbow.linenumbers) by [Ron Valstar](http://www.sjeiti.com/).

This is the development repository. If you just want to use the plugin you can find it in the <a href="http://wordpress.org/plugins/wp-rainbow-hilite/">WordPress plugin repository</a>.

Developing
----------

If you make changes in the js files you will have to recompile the minified script versions through [Closure compiler](https://developers.google.com/closure/compiler/).

In order to compile run `build.sh`, located in the plugins root directory:
```
	cd [WP_PLUGIN_DIRECTORY]/wp-rainbow-hilite/
	./build.sh
```

The script expects compiler to be present under `/usr/local/compiler-latest/compiler.jar`.
If for some reason the file is located somewhere else on your system you can change the `CLOSURE_COMPILER`
var in build.sh line 3.

Plugin API
----------
See the [Project Wiki](../../wiki/) for details.
