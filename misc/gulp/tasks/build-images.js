/**
 * Gulp-задача. Оптимизация изображений.
 *
 * @since        02.04.2015 18:13
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var path = require('path');
var plumber = require('gulp-plumber');
var cache = require('gulp-check-hash');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');
var config = require('../config.js');

gulp.task('build-images', function () {

	gulp.src(config.images.srcFilters)
		.pipe(plumber())
		.pipe(cache({
			src  : config.images.srcPath,
			build: config.images.buildPath,
			type : 'filetime'
		}))
		.pipe(imagemin({
			progressive: true,
			svgoPlugins: [{removeViewBox: false}],
			use        : [pngquant()],
			interlaced : true
		}))
		.pipe(gulp.dest(config.images.buildPath));

	gulp.src(config.images.srcFiltersSVG)
		.pipe(plumber())
		.pipe(cache({
			src  : config.images.srcPath,
			build: config.images.buildPath
		}))
		.pipe(gulp.dest(config.images.buildPath));

});
