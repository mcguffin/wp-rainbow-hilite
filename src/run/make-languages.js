const fs = require('fs');
const comps = require('prismjs/components.js');
/*
const sets = {
	popular: [ "arduino", "bash", "basic", "c", "clojure", "cmake", "coffeescript", "cpp", "csharp", "css", "d", "dart", "elixir", "gcode", "git", "go", "haskell", "ini", "java", "javascript", "json", "jsx", "kotlin", "less", "lisp", "lua", "markdown", "markup", "markup-templating", "objectivec", "php", "php-extras", "phpdoc", "powershell", "processing", "python", "r", "regex", "ruby", "rust", "sass", "scala", "scheme", "scss", "smalltalk", "smarty", "sql", "swift", "twig", "typescript", "visual-basic", "wasm", "wiki", "yaml" ],
	rainbow: [ "bash", "c", "coffeescript", "csharp", "css", "d", "go", "haskell", "java", "javascript", "lua", "markup", "php", "python", "r", "ruby", "scheme", "smalltalk" ],
	web: [ "bash", "coffeescript", "css", "dart", "git", "json", "javascript", "jsx", "less", "markdown", "markup", "markup-templating", "php", "php-extras", "phpdoc", "powershell", "python", "regex", "ruby", "sass", "scss", "smarty", "sql", "twig", "typescript", "wasm", "yaml" ],
}
*/
delete(comps.core);
delete(comps.plugins);
delete(comps.languages.meta);
delete(comps.themes.meta);
// comps.sets = {
// 	popular: 'Popular',
// 	rainbow: 'Rainbow',
// 	web: 'Web developer'
// }
Object.keys(comps.themes).forEach( t => {
	if ( 'object' === typeof comps.themes[t] ) {
		comps.themes[t] = comps.themes[t].title
	}
});
Object.keys(comps.languages).forEach( l => {
	if ( !! comps.languages[l].option ) {
		delete(comps.languages[l].option);
	}
	if ( !! comps.languages[l].peerDependencies ) {
		delete(comps.languages[l].peerDependencies);
	}
	if ( !! comps.languages[l].require ) {
		delete(comps.languages[l].require);
	}
	if ( !! comps.languages[l].owner ) {
		delete(comps.languages[l].owner);
	}
	if ( !! comps.languages[l].overrideExampleHeader ) {
		delete(comps.languages[l].overrideExampleHeader);
	}
	if ( !! comps.languages[l].alias ) {
		if ( 'string' === typeof comps.languages[l].alias ) {
			comps.languages[l].alias = [ comps.languages[l].alias ];
		}
	}

	if ( !! comps.languages[l].aliasTitles ) {
		comps.languages[l].aliasTitles = Object.values( comps.languages[l].aliasTitles )
	}
});

fs.writeFileSync( './js/prism.json', JSON.stringify( comps, null, "\t" ) );
