/**
 * Customize me as necessary per project.
 */
module.exports = function(grunt){

    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    /**
     * Default tasks included in package.json; for each package, add grunt_settings.js
     * file in the package root and add options there.
     */
    var _configs = {
        pkg      : grunt.file.readJSON('package.json'),
        banner   : '/*! Build: v<%= pkg.version %>; Author: <%= pkg.author.name %> */\n',
        bump     : {options: {files: ['package.json'], commit: false, push: false, createTag: false, updateConfigs: ['pkg', 'banner']}},
        jshint   : {options: { jshintrc: './.jshintrc' }},
        watch    : {options: {spawn: false}},
        concat   : {},
        strip    : {},
        uglify   : {},
        sass     : {}
    };


    /**
     * Find any grunt_settings.js files in any package directory, load it, and pass in
     * grunt and the _configs object. The grunt_settings.js file should contain:
     * module.exports = function( grunt, _config ){ ...declarations here... }.
     */
    grunt.file.expand('./web/packages/**/grunt_settings.js').forEach(function( _path ){
        require(_path)(grunt, _configs);
    });


    /**
     * Finally, pass the _configs object to grunt, after additional configs have been
     * set by packages.
     */
    grunt.initConfig(_configs);


    /**
     * Always make the default task just be 'watch'
     */
    grunt.registerTask('default', ['watch']);

}