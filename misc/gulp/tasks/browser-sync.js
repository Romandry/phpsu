/**
 * Gulp-задача. LiveReload.
 *
 * @since        30.06.2015 12:51
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var gulp = require('gulp');
var browserSync = require('browser-sync');
var config = require('../config.js');

gulp.task('browser-sync', function () {

	browserSync.init(config.browserSyncOpts);

	// Ловим изменения и обновляем страницу в браузере
	gulp.watch([
		'public_html/**',
		'static_html/**',
		'application/**'
	]).on('change', function () {
		browserSync.reload();
	});
});
