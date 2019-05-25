var gulp = require('gulp'),
	uglify = require('gulp-uglify'),
	concat = require('gulp-concat'),
	rename = require('gulp-rename');

gulp.task('js', function () {
    var path = './src/assets/src/js/';
    
	return gulp.src([
            path + 'jquery.multipleInput.js'
        ])
        .pipe(concat('jquery.multipleInput.js'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(path));
});
    
gulp.task('default', ['js']);