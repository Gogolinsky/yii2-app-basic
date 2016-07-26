'use strict';

var gulp         = require('gulp');
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var csso         = require('gulp-csso');
var rename       = require('gulp-rename');

gulp.task('default', ['sass', 'watch']);

gulp.task('sass', function () {
    return gulp.src('./web/sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({
            browsers: ['last 10 versions']
        }))
        .pipe(csso())
        .pipe(rename('style.css'))
        .pipe(gulp.dest('./web/css/'));
});

gulp.task('watch', function () {
    gulp.watch([
        './web/sass/*.scss'
    ], ['sass']);
});