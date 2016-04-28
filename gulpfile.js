var gulp = require('gulp');
var sass = require('gulp-sass');
var sassGlob = require('gulp-sass-glob');
var concat = require('gulp-concat');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('default', function() {
    return gulp.start('styles', 'javascript', 'watch');
});

gulp.task('watch', function() {
    gulp.watch('web/src/sass/**/*.scss', ['styles']);
    gulp.watch(['web/src/js/main.js'], ['javascript']);
});

gulp.task('styles', function () {
    gulp
        .src('web/src/sass/main.scss')
        .pipe(sassGlob())
        .pipe(sass().on('error', console.log))
        .pipe(autoprefixer({
            browsers: ['last 5 versions']
        }))
        .pipe(gulp.dest('web/dist/css'));
});

gulp.task('javascript', function() {
    gulp
        .src([
            'web/bower_components/jquery/dist/jquery.js',
            'web/bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
            'web/src/js/main.js',
            'web/bower_components/textarea-autosize/dist/jquery.textarea_autosize.js'
        ])
        .pipe(concat('main.js'))
        .pipe(gulp.dest('web/dist/js'));
});
