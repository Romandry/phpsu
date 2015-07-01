/**
 * Gulp-задача. Сборка CSS.
 *
 * @since        02.04.2015 18:12
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var cache = require('gulp-check-hash');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var prefix = require('gulp-autoprefixer');
var header = require('gulp-header');
var rename = require('gulp-rename');
var minifyCss = require('gulp-minify-css');
var config = require('../config');

gulp.task('build-css', function () {

	gulp.src(config.css.srcFilters)
		.pipe(plumber())
		.pipe(sass({
			outputStyle: 'nested'
		}))
		.pipe(prefix(config.css.autoprefixerOpts))
		.pipe(header(config.docHeader))
		.pipe(cache({
			src      : config.css.srcPath,
			build    : config.css.buildPath,
			extension: '.css'
		}))
		.pipe(gulp.dest(config.css.buildPath))

		.pipe(minifyCss())
		.pipe(rename({suffix:'.min'}))
		.pipe(gulp.dest(config.css.buildPath))
	;

});
