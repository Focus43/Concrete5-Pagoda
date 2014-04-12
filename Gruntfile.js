/**
 * Customize me as necessary per project.
 */
module.exports = function(grunt){

    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    var _configs = {
        pkg      : grunt.file.readJSON('package.json'),
        banner   : '/*! Build: v<%= pkg.version %>; Author: <%= pkg.author.name %> */\n',
        bump     : {options: {files: ['package.json'], commit: false, push: false, createTag: false, updateConfigs: ['pkg', 'banner']}},
        jshint   : {options: { jshintrc: './.jshintrc' }},
        concat   : {},
        strip    : {},
        uglify   : {},
        sass     : {},
        watch    : {}
    };

    //require('../web/packages/toj/grunt_settings.js').buildSettings(grunt, _configs);

    grunt.initConfig(_configs);

    grunt.registerTask('default', ['watch']);

}