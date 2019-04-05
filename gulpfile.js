var gulp = require('gulp');
var exec = require('child_process').exec;

function fix_in_dir(dir) {
    exec('php-cs-fixer fix "./' + dir + '" --rules=@PSR2', function (error, stdout) {
        console.log(stdout);
    });
}

function csfixtests(cb) {
    fix_in_dir('tests');
    cb();
}

function csfixsrc(cb) {
    fix_in_dir('src');
    cb();
}

function phpunit(cb) {
    exec('phpunit --colors=always', function (error, stdout) {
        console.log(stdout);
    });
    cb();
}

function watcher(cb) {
    gulp.watch([
        'src/**/*.php',
        'src/*.php',
        'tests/**/*.php',
        'tests/*.php',
    ], {delay: 6000, ignoreInitial: false}, gulp.series(gulp.parallel(csfixtests, csfixsrc)));
    cb();
}

exports.default = gulp.series(watcher);