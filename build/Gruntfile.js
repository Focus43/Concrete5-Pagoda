/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  var _initConfigs = {
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! <%= pkg.project %> - Build v<%= pkg.version %> (<%= grunt.template.today("yyyy-mm-dd") %>)\n',
    filename: '<%= pkg.name %>',
    concat: {
//      options: {
//        banner: '<%= banner %>',
//        stripBanners: true
//      },
//      dist: {
//        src:  [],
//        dest: ''
//      }
    },
    strip: {
      main : {
        src :  '<%= concat.dist.dest %>',
        dest : ''
      }
    },
    uglify: {
      options: {
        banner: '<%= banner %>'
      },
      dist: {
        src: '<%= strip.main.dest %>',
        dest: '<%= strip.main.dest %>'
      }
    },
    jshint: {
      options: {},
      gruntfile: {
        src: 'Gruntfile.js'
      },
      lib_test: {
        src: ['']
      }
    },
    sass: {
      dev: {
        options: {
          style: 'expanded',
          debugInfo: false
        },
        files: {}
      },
      build: {
        options: {
          style: 'compressed'
        },
        files: {}
      }
    },
    watch: {}
  };

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-strip');
  grunt.loadNpmTasks('grunt-bump');

  require('../web/packages/flexry/grunt_settings.js').extraConfigs(grunt, _initConfigs);

  grunt.initConfig(_initConfigs);

  // Default task.
  grunt.registerTask('default', []);
  //grunt.registerTask('build', ['jshint', 'concat', 'strip', 'uglify', 'sass:build', 'bump:minor']);
  //grunt.registerTask('release', ['jshint', 'concat', 'strip', 'uglify', 'sass:build', 'bump:major']);

};