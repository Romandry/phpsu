/**
 * Gulpfile
 *
 * @since        18.02.2015 11:30
 * @author       DelphinPRO delphinpro@yandex.ru
 * @copyright    Copyright (C) 2015 DelphinPRO. All rights reserved.
 * @license      Licensed under the MIT license
 */

'use strict';

var cache = require('gulp-check-hash');
cache.root = __dirname;

var requireDir = require('require-dir');
requireDir('./gulp/tasks', { recurse: true });
