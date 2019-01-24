// config globals
var src = './src/';
var dist = './public/assets/';
var srcPug = './src/pug/**/*.pug'
var distPartials = './public/partials/';
var proxyUrl = 'estilodevidafitnes.com';
var localPort = 3005;
var destJSFile = 'paymentfitnes-main.js';


// my scripts: default load all script of folder js/*
var scripts = [
  src + 'js/functions.js',
  src + 'js/main.js',
];

// node modules folder
var modules = './node_modules/';
// var plugins path: (check theme plugins before of add new plugins)
var plugins = [
  // modules + 'vue/dist/vue.js'

];


// define package
var gulp = require('gulp'),
	plumber = require('gulp-plumber'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	eslint = require('gulp-eslint'),
	sass = require('gulp-sass'),
	postcss = require('gulp-postcss'),
	autoprefixer = require('autoprefixer'),
	cssnano = require('cssnano'),
	sourcemaps = require('gulp-sourcemaps'),
	browserSync = require('browser-sync').create(),
	reload = browserSync.reload,
	imagemin = require('gulp-imagemin'),
	md5 = require('gulp-md5'),
	del = require('del'),
	wpPot = require('gulp-wp-pot'),
	crypto = require('crypto'),
	fs = require('fs'),
	mymd5 = require('./node_modules_third/gulp-mymd5/index.js'),
	rename = require('gulp-rename'),
	pug = require('gulp-pug'),
	dataJson = {
		'md5': ''
	};

gulp.task('scripts-cleanup', function () {
	del.sync([dist], {force: true});
});

/**
 * task execute
 * this create a file with data json
 * create variable hash to production
*/
gulp.task( 'set-dataJson', function() {
	dataJson['md5'] = crypto.createHash( 'md5' ).update( Date.now().toString() ).digest( 'hex' ).slice( 0, 10 );
	fs.writeFile( './gulpfiledata.json', JSON.stringify( dataJson ), function( err ) {
		if ( err ) {
			return console.log( err );
		}
	});
});

//pug
gulp.task('pug', function() {
  return gulp.src(srcPug)
      .pipe(pug({ pretty: true }))
      .on('error', gutil.log) // Log errors instead of killing the process
      .pipe(rename({
          extname: '.php'
      }))
      .pipe(gulp.dest(distPartials))
});
// pot files
gulp.task('potFiles', function () {
  gulp.src(['**/*.php'])
  .pipe(wpPot({
    domain: 'paymentfitnes'
  }))
  .pipe(gulp.dest('languages/paymentfitnes.pot'));
});

// minify images
gulp.task('images', function(){
  gulp.src(src + 'img/**/*.+(png|jpg|jpeg|gif|svg)')
  .pipe(imagemin())
  .pipe(gulp.dest(dist + 'img/'));
});

// copy fonts
gulp.task('fonts', function(){
  gulp.src(src + 'fonts/**/*')
  .pipe(gulp.dest(dist + 'fonts/'));
});

// sass mappgins files
gulp.task('styles:dev', function(){
  var processors = [
    autoprefixer({browsers: ['> 3%', 'last 2 versions', 'ie 9', 'ios 6', 'android 4']})
  ];

  gulp.src(src + 'scss/**/*.scss')
  .pipe(plumber())
  .pipe(sourcemaps.init())
  .pipe(sass({outputStyle: 'compressed'}))
  .pipe(postcss(processors))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(dist + 'css/'))
  .pipe(browserSync.stream());
});

// sass dist remove source maps
gulp.task('styles:dist', function(){
  var processors = [
    autoprefixer({browsers: ['> 3%', 'last 2 versions', 'ie 9', 'ios 6', 'android 4']}),
    cssnano({zindex: false})
  ];

  gulp.src(src + 'scss/**/*.scss')
  .pipe(plumber())
  .pipe(sass())
  .pipe(postcss(processors))
  .pipe(gulp.dest(dist + 'css/'))
  .pipe(mymd5(dataJson.md5))
  .pipe(gulp.dest(dist + 'css/'));
});


// script js
var scriptsTemp = [
  src + 'temp/plugins.js',
  src + 'temp/main.js'
];

// plugins js minify
gulp.task('pluginsjs', function(){
  gulp.src(plugins)
  .pipe(concat('plugins.js'))
  .pipe(uglify())
  .pipe(gulp.dest(src + 'temp/'));
});

gulp.task('scripts:temp', function(){
  gulp.src(scripts)
  .pipe(sourcemaps.init())
  .pipe(plumber())
  .pipe(eslint())
  .pipe(eslint.format())
  .pipe(eslint.failAfterError())
  .pipe(concat('main.js'))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(src + 'temp/'));
});

gulp.task('eslint:dev', function(){
  gulp.src(scriptsTemp)
  .pipe(eslint())
  .pipe(eslint.format())
  .pipe(eslint.failAfterError());
});

gulp.task('scripts:dev', function(){
  gulp.src(scriptsTemp)
  .pipe(sourcemaps.init())
  .pipe(plumber())
  .pipe(concat(destJSFile))
  .pipe(sourcemaps.write())
  .pipe(gulp.dest(dist + 'js/'));
});

gulp.task('eslint:dist', function(){
	gulp.src(scriptsTemp)
		.pipe(eslint())
		.pipe(eslint.format())
		.pipe(eslint.failAfterError());
});

gulp.task('scripts:dist', function(){
  gulp.src(scriptsTemp)
  .pipe(plumber())

  .pipe(concat(destJSFile))
  .pipe(uglify())
  .pipe(gulp.dest(dist + 'js/'))
  .pipe(mymd5(dataJson.md5))
  .pipe(gulp.dest(dist + 'js/'));
});

// serve
gulp.task('serve', function(){
  browserSync.init({
    proxy: proxyUrl,
    port: localPort,
    reloadDebounce: 500
  });
  gulp.watch(src + 'scss/**/*.scss', ['styles:dev']);
  gulp.watch(src + 'js/**/*.js', ['scripts:temp']);
  gulp.watch(src + 'temp/**/*.js', ['scripts:dev']).on('change', reload);
  gulp.watch('./**/*.php').on('change', reload);
});

// tasks globals
gulp.task('static', ['potFiles', 'pluginsjs', 'scripts:temp', 'fonts', 'images']);
// build all
gulp.task('build', ['styles:dev', 'scripts:dev', 'static']);

// gulp minify all
gulp.task('dist', ['scripts-cleanup', 'set-dataJson', 'styles:dist', 'scripts:dist', 'static']);
// dev default tasks
gulp.task('default', ['build', 'serve']);
