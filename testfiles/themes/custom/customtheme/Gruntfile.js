'use strict';

module.exports = function (grunt) {
  grunt.registerTask('build', function () {
    grunt.file.write('hello_grunt.txt', 'Hello world from grunt.');
  });

};
