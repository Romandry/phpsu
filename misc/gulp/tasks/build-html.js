/**
 * Gulp-задача. Сборка HTML.
 *
 * @since        10.04.2015 13:20
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var plumber = require('gulp-plumber');
var include = require('gulp-file-include');
var cache = require('gulp-check-hash');
var config = require('../config.js');

gulp.task('build-html', function () {

	gulp.src(config.html.srcFilters)
		.pipe(plumber())
		.pipe(include())
		.pipe(cache({src: config.html.srcPath, build: config.html.buildPath}))
		.pipe(gulp.dest(config.html.buildPath));

});
