/**
 * Gulp-задача. Сбор статики из bower-пакетов.
 *
 * @since        01.07.2015 22:09
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var cache = require('gulp-check-hash');
var plumber = require('gulp-plumber');
var header = require('gulp-header');
var rename = require('gulp-rename');
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var config = require('../config');

// Конфигурация
var assetsConfig = {

	// Список вендорных стилей для склеивания
	css: [
		'bower_components/colorbox/example1/colorbox.css'
	],

	// Список вендорных картинок для склеивания
	images: [
		// Откуда и куда
		['bower_components/colorbox/example1/images/**', config.css.buildPath + 'images/']
	]

	// Остальное потом допишем, по мере надобности :)

};

// Задача
gulp.task('bower', function () {

	gulp.src(assetsConfig.css)
		.pipe(plumber())
		.pipe(concat('vendor.css'))
		.pipe(gulp.dest(config.css.buildPath))

		.pipe(minifyCss())
		.pipe(header(config.docHeader))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(config.css.buildPath))
	;

	assetsConfig.images.forEach(function(item){
		gulp.src(item[0])
			.pipe(plumber())
			.pipe(imagemin({
				progressive: true,
				svgoPlugins: [{removeViewBox: false}],
				use        : [pngquant()],
				interlaced : true
			}))
			.pipe(gulp.dest(item[1]))
		;
	});

});
