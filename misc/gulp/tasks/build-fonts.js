/**
 * Gulp-задача. Сборка шрифтов.
 *
 * @since        02.04.2015 18:16
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var cache = require('gulp-check-hash');
var config = require('../config.js');

gulp.task('build-fonts', function () {

	gulp.src(config.fonts.srcFilters)
		.pipe(cache({
			src      : config.fonts.srcPath,
			build    : config.fonts.buildPath
		}))
		.pipe(gulp.dest(config.fonts.buildPath));

});
