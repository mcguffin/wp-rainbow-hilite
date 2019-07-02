var gulp			= require('gulp');
var fs				= require('fs');
var concat			= require('gulp-concat');
var uglify			= require('gulp-uglify');
var sass			= require('gulp-sass');
var sourcemaps		= require('gulp-sourcemaps');
var rename			= require('gulp-rename');
var autoprefixer	= require('gulp-autoprefixer');

gulp.task( 'linenumbers:js', function() {
	//
	return gulp.src( './src/vendor/rainbow.linenumbers/js/rainbow.linenumbers.js' )
		.pipe( gulp.dest( './js/rainbow.linenumbers/' ) )
		.pipe( uglify() )
		.pipe( rename({ suffix: '.min' }) )
		.pipe( gulp.dest( './js/rainbow.linenumbers/' ) );

});


gulp.task( 'rainbow:js:language', function() {
	//
	return gulp.src( './src/vendor/rainbow/src/language/*.js' )
			.pipe( gulp.dest( './js/rainbow/language/' ) )
			.pipe( uglify() )
			.pipe( rename({ suffix: '.min' }) )
			.pipe( gulp.dest( './js/rainbow/language/' ) );
})
gulp.task( 'rainbow:js:main', function() {
	return gulp.src( './src/vendor/rainbow/dist/rainbow.js' )
		.pipe( gulp.dest( './js/rainbow/' ) )
		.pipe( uglify({
			mangle: {
                reserved: ['Prism']
			}
		}) )
		.pipe( rename({ suffix: '.min' }) )
		.pipe( gulp.dest( './js/rainbow/' ) );
});
gulp.task('rainbow:js',gulp.parallel('rainbow:js:main','rainbow:js:language'))

gulp.task( 'rainbow:css', function() {
	return gulp.src( './src/vendor/rainbow/themes/sass/*.sass' )
		.pipe( sass({
			precision: 8,
			outputStyle: 'compressed'
		}) )
        .pipe( autoprefixer() )
		.on('error', sass.logError)
		.pipe( gulp.dest('./css/rainbow/themes/'));
});



gulp.task( 'frontend:js', function() {
	// rainbow + linenumbers + lang-modules
	return gulp.src( './src/js/frontend/*.js' )
			.pipe( sourcemaps.init() )
			.pipe( uglify() )
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest( './js/frontend/' ) );

});
gulp.task( 'frontend:css', function() {
	// rainbow + linenumbers + theme
	return gulp.src( './src/scss/frontend/*.scss' )
//		.pipe( sourcemaps.init() )
		.pipe( sass({
			precision: 8,
			outputStyle: 'compressed'
		}) )
		.on('error', sass.logError)
        .pipe( autoprefixer() )
//		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest('./css/frontend/'));
});



gulp.task( 'admin:css:main', function() {
	// rainbow. linenumbers. lang-modules.
	return gulp.src( './src/scss/admin/*.scss' )
			.pipe( sourcemaps.init() )
			.pipe( sass({
				precision: 8,
				outputStyle: 'compressed'
			}) )
	        .pipe( autoprefixer() )
			.on('error', sass.logError)
			.pipe( sourcemaps.write( '.' ) )
			.pipe( gulp.dest('./css/admin/'));
});

gulp.task( 'admin:css:mce', function() {
	return gulp.src( './src/scss/admin/mce/wprainbow/*.scss' )
		.pipe( sourcemaps.init() )
		.pipe( sass({
			precision: 8,
			outputStyle: 'compressed'
		}) )
        .pipe( autoprefixer( ) )
		.on('error', sass.logError)
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest('./css/admin/mce/wprainbow/'));
});

gulp.task('admin:css',gulp.parallel( 'admin:css:main', 'admin:css:mce' ));



gulp.task( 'admin:js:main', function() {
	return gulp.src( './src/js/admin/*.js' )
		.pipe( sourcemaps.init() )
		.pipe( uglify() )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( './js/admin/' ) );
});

gulp.task( 'admin:js:mce', function() {
	return gulp.src( './src/js/admin/mce/wprainbow/plugin.js' )
		.pipe( sourcemaps.init() )
		.pipe( uglify() )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( gulp.dest( './js/admin/mce/wprainbow/' ) );
});

gulp.task('admin:js',gulp.parallel( 'admin:js:main', 'admin:js:mce' ));



gulp.task('watch', cb => {
	gulp.watch('./src/js/admin/**/*.js', gulp.parallel( 'admin:js' ) );
	gulp.watch('./src/scss/admin/**/*.scss', gulp.parallel( 'admin:css' ) );
	gulp.watch('./src/js/frontend/**/*.js', gulp.parallel( 'frontend:js' ) );
	gulp.watch('./src/scss/frontend/**/*.scss', gulp.parallel( 'frontend:css' ) );
});


gulp.task( 'build', gulp.series(
	'rainbow:js', 'rainbow:css', 'linenumbers:js',
	'admin:js', 'admin:css',
	'frontend:js', 'frontend:css'
));

gulp.task( 'dev', gulp.series('build', 'watch') );

module.exports = {
	build:gulp.series('build')
}
