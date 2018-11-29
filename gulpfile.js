var gulp = require('gulp');
var exec = require('child_process').exec;

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
        'tests/*.php',
        'tests/**/*.php',
    ], {delay: 2000}, phpunit);
    cb();
}

exports.default = watcher;