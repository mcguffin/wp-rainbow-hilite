var gulp			= require('gulp');
var fs				= require('fs');
var concat			= require('gulp-concat');
var uglify			= require('gulp-uglify');
var sass			= require('gulp-sass');
var sourcemaps		= require('gulp-sourcemaps');
var rename			= require('gulp-rename');
var replace			= require('gulp-replace');
var clean			= require('gulp-clean');
var runSequence		= require('run-sequence');
var autoprefixer	= require('gulp-autoprefixer');

gulp.task( 'rainbow.linenumbers-scripts', function() {
	//
	return gulp.src( './src/vendor/Sjeiti/rainbow.linenumbers/js/rainbow.linenumbers.js' )
		.pipe( gulp.dest( './js/rainbow.linenumbers/' ) )
		.pipe( uglify() )
		.pipe( rename({ suffix: '.min' }) )
		.pipe( gulp.dest( './js/rainbow.linenumbers/' ) );

});


gulp.task( 'rainbow-scripts', function() {
	//
	return [
		gulp.src( './src/vendor/ccampbell/rainbow/src/language/*.js' )
			.pipe( gulp.dest( './js/rainbow/language/' ) )
			.pipe( uglify() )
			.pipe( rename({ suffix: '.min' }) )
			.pipe( gulp.dest( './js/rainbow/language/' ) ),

		gulp.src( './src/vendor/ccampbell/rainbow/dist/rainbow.js' )
			.pipe( gulp.dest( './js/rainbow/' ) )
			.pipe( uglify({
				mangle: {
	                except: ['Prism']
				}
			}) )
			.pipe( rename({ suffix: '.min' }) )
			.pipe( gulp.dest( './js/rainbow/' ) )
	];
});

gulp.task( 'rainbow-styles', function() {
	return gulp.src( './src/vendor/ccampbell/rainbow/themes/sass/*.sass' )
		.pipe( sass({
			precision: 8,
			outputStyle: 'compressed'
		}) )
        .pipe( autoprefixer( { browsers: ['last 3 versions'] } ) )
		.on('error', sass.logError)
		.pipe( gulp.dest('./css/rainbow/themes/'));
});



gulp.task( 'frontend-scripts', function() {
	// rainbow + linenumbers + lang-modules
	return gulp.src( './src/js/frontend/*.js' )
			.pipe( sourcemaps.init() )
			.pipe( uglify() )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest( './js/frontend/' ) );

});
gulp.task( 'frontend-styles', function() {
	// rainbow + linenumbers + theme
	return gulp.src( './src/scss/frontend/*.scss' )
//		.pipe( sourcemaps.init() )
		.pipe( sass({
			precision: 8,
			outputStyle: 'compressed'
		}) )
		.on('error', sass.logError)
        .pipe( autoprefixer( { browsers: ['last 2 versions'] } ) )
//		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest('./css/frontend/'));
});



gulp.task( 'admin-styles', function() {
	// rainbow. linenumbers. lang-modules.
	return [
		gulp.src( './src/scss/admin/*.scss' )
			.pipe( sourcemaps.init() )
			.pipe( sass({
				precision: 8,
				outputStyle: 'compressed'
			}) )
	        .pipe( autoprefixer( { browsers: ['last 2 versions'] } ) )
			.on('error', sass.logError)
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest('./css/admin/')),
		gulp.src( './src/scss/admin/mce/wprainbow/*.scss' )
			.pipe( sourcemaps.init() )
			.pipe( sass({
				precision: 8,
				outputStyle: 'compressed'
			}) )
	        .pipe( autoprefixer( { browsers: ['last 2 versions'] } ) )
			.on('error', sass.logError)
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest('./css/admin/mce/wprainbow/')),
	];
});

gulp.task( 'admin-scripts', function() {
	return [
		gulp.src( './src/js/admin/*.js' )
			.pipe( sourcemaps.init() )
			.pipe( uglify() )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest( './js/admin/' ) ),

		gulp.src( './src/js/admin/mce/wprainbow/plugin.js' )
			.pipe( sourcemaps.init() )
			.pipe( uglify() )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest( './js/admin/mce/wprainbow/' ) ),
		];
});



gulp.task( 'watch', function() {
	gulp.watch('./src/js/admin/**/*.js', [ 'admin-scripts' ] );
	gulp.watch('./src/js/frontend/**/*.js', [ 'frontend-scripts' ] );
	gulp.watch('./src/scss/admin/**/*.scss', [ 'admin-styles' ] );
	gulp.watch('./src/scss/frontend/**/*.scss', [ 'frontend-styles' ] );
});


gulp.task( 'build', function(){
	runSequence(
		'admin-scripts', 'rainbow-scripts', 'frontend-scripts',
		'rainbow.linenumbers-scripts',
		'admin-styles',  'rainbow-styles',  'frontend-styles'
	);
} );

gulp.task( 'default', [ 'build', 'watch' ] );
