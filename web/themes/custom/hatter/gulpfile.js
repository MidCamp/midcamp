/* eslint-env node, es6 */
/* global require */
'use strict';

// Load dependencies
const {parallel, series, src, dest, watch} = require('gulp'),
  concat = require('gulp-concat'),
  jsMinify = require('gulp-minify'),
  shell = require('gulp-shell'),
  sourceMaps = require('gulp-sourcemaps'),
  sass = require('gulp-sass')(require('sass')),
  postCss = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  cssNano = require('cssnano'),
  pxToRem = require('postcss-pxtorem'),
  rename = require('gulp-rename');

const cssSource = 'scss/*.scss';
const jsSource = ['js/*.js', '!js/*.min.js'];

/**
 * Sass Build
 * @param {string|array} source Globs to pass to gulp.src().
 * @param {string} output Path to pass to gulp.dest().
 */
const processScss = (source, output) =>
  src(source)
    .pipe(sourceMaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(
      postCss([
        pxToRem({
          'propList': ['*'],
        }),
        autoprefixer(),
      ])
    )
    // Write sourcemaps
    .pipe(sourceMaps.write())
    // Output compiled CSS to appropriate destination folder in dist folder
    .pipe(dest(output))
    .pipe(postCss([cssNano()]))
    .pipe(rename({'extname': '.min.css'}))
    // Output minified CSS to appropriate destination folder in dist folder
    .pipe(dest(output));

const buildCss = () => processScss(cssSource, 'css/');



/**********************************************************
 * JS
 */
const jsMinifyOptions = {
  'ext': {
    'min': '.min.js',
  },
};

/**
 * JS Build
 * Copies the source and create a min version and add to dist folder
 */
const processJs = (source, output) =>
  src(source)
    .pipe(sourceMaps.init())
    .pipe(jsMinify(jsMinifyOptions))
    .pipe(sourceMaps.write())
    .pipe(dest(output));

const buildJs = () => processJs(jsSource, 'js/compiled');

/**
 * Builds repo in current state
 */
exports.build = parallel(
  buildJs,
  buildCss,
);

exports.watch = () => {
  watch(cssSource, series(buildCss));
  watch(jsSource, series(buildJs));
};

/**
 * Builds repo in current state
 */
exports.default = exports.build;
