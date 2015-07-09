/**
 * Gulp-tasks config.
 *
 * @since        30.06.2015 9:21
 * @author       DelphinPRO delphinpro@yandex.ru
 * @license      Licensed under the MIT license
 */

var pkg = require('../../package.json');

/* Local domain on which you run the site */

var _localDomain = 'phpsu.project';

/* Your favorite browsers for devel */

var _browsers = ['firefox'];

/* Path */

var _src = 'misc/source/';
var _build = 'static_html/';

var _js = 'js/';
var _css = 'css/';
var _img = 'images/';
var _fonts = 'fonts/';
var _html = 'html/';

var _buildCss = _build + 'styles/';
var _buildJs = _build + 'js/';
var _buildImages = _build + 'images/';
var _buildFonts = _build + 'fonts/';
var _buildHtml = 'not used...';


module.exports = {

	// The list of tasks performed ar the build
	buildTaskList  : [/*'build-html',*/ 'build-js', 'build-css', 'build-images', 'build-fonts'],

	// BrowserSync options: http://www.browsersync.io/docs/options/
	browserSyncOpts: {
		browser  : _browsers, // Browsers
		notify   : false,
		startPath: '/',
		proxy    : _localDomain // Local domain
	},

	docHeader      : '/*! ' + pkg.name + ' v' + pkg.version + ' */\n',

	css: {
		srcPath         : _src + _css,
		srcPathWatch    : [_src + _css + '**/*.scss'],
		srcFilters      : [
			_src + _css + '**/*.scss', // All scss-files
			'!' + _src + _css + '**/_*.scss' // Excluding files with names starting with an underscore
		],
		buildPath       : _buildCss,
		autoprefixerOpts: {
			// Which prefixes will be put.
			// For browsers having a share of more than 1%
			// And for the last two versions of browsers.
			// Information taken from caniuse.com
			browsers: ['> 1%', 'last 2 versions']
		}
	},

	js: {
		srcPath     : _src + _js,
		srcPathWatch: [_src + _js + '**/*.js'],
		srcFilters  : [_src + _js + '*.js'], // All js-files
		buildPath   : _buildJs
	},

	images: {
		srcPath     : _src + _img,
		srcPathWatch: [_src + _img + '**/*.*'],
		srcFilters  : [
			_src + _img + '**/*.png',
			_src + _img + '**/*.jpg',
			_src + _img + '**/*.gif'
		],
		srcFiltersSVG  : [
			_src + _img + '**/*.svg'
		],
		buildPath   : _buildImages
	},

	fonts: {
		srcPath     : _src + _fonts,
		srcPathWatch: [_src + _fonts + '**/*.*'],
		srcFilters  : [_src + _fonts + '**/*.*'],
		buildPath   : _buildFonts
	},

	html: {
		srcPath     : _src + _html,
		srcPathWatch: [_src + _html + '**/*.html'],
		srcFilters  : [_src + _html + '**/*.html', '!'+_src + _html + '**/_*.html'],
		buildPath   : _buildHtml

	}

};
