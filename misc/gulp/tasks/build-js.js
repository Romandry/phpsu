/**
 * Gulp-задача. Сборка Javascript.
 *
 * @since        02.04.2015 18:10
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var plumber = require('gulp-plumber');
var cache = require('gulp-check-hash');
var rigger = require('gulp-rigger');
var uglify = require('gulp-uglify');
var header = require('gulp-header');
var rename = require('gulp-rename');
var config = require('../config.js');

gulp.task('build-js', function () {

	gulp.src(config.js.srcFilters)
		.pipe(plumber())
		.pipe(rigger())
		.pipe(header(config.docHeader))
		.pipe(cache({
			src      : config.js.srcPath,
			build    : config.js.buildPath
		}))
		.pipe(gulp.dest(config.js.buildPath))

		// Сжимаем, сохраняя комментарии вида /*! ... */
		.pipe(uglify({preserveComments: 'some'}))
		.pipe(rename({suffix:'.min'}))
		.pipe(gulp.dest(config.js.buildPath))
	;

});
