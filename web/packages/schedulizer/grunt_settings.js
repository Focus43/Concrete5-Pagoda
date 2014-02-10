/**
 * Sass and compass install:
 * http://stackoverflow.com/questions/19068382/sass-compile-error-stalenesscheckermutex-nameerror
 */
module.exports.extraConfigs = function( grunt, _currentConfigs ){

    // Generate path to file in package root
    function pkgPath( _path ){
        var _pkgPath = '../web/packages/schedulizer/%s';
        return _pkgPath.replace('%s', _path);
    }


    /////////////////////////////// CONCAT FILES ///////////////////////////////
    _currentConfigs.concat.schedulizer = { files: {} };

    // concat auto.js
    _currentConfigs.concat.schedulizer.files[ pkgPath('blocks/schedulizer_calendar/auto.js') ] = [
        pkgPath('blocks/schedulizer_calendar/dev/auto.dev.js')
    ];

    // concat schedulizer_calendar/view.js
    _currentConfigs.concat.schedulizer.files[ pkgPath('blocks/schedulizer_calendar/view.js') ] = [
        pkgPath('js/libs/fullcalendar-1.6.1/fullcalendar.js'),
        pkgPath('blocks/schedulizer_calendar/dev/view.dev.js')
    ];

    // concat inline_script.js.txt
    _currentConfigs.concat.schedulizer.files[ pkgPath('blocks/schedulizer_calendar/inline_script.js.txt') ] = [
        pkgPath('blocks/schedulizer_calendar/dev/inline_script.dev.js')
    ];

    // concat dicer template
    _currentConfigs.concat.schedulizer.files[ pkgPath('js/app-dashboard.js') ] = [
        pkgPath('js/libs/fullcalendar-1.6.1/fullcalendar.js'),
        pkgPath('js/libs/ajaxify.form.js'),
        pkgPath('js/dev/app-dashboard.dev.js')
    ];


    /////////////////////////////// UGLIFY FILES ///////////////////////////////
    _currentConfigs.uglify.schedulizer = {
        options: {
            banner: '/*! <%= pkg.project %> - Build v<%= pkg.version %> (<%= grunt.template.today("yyyy-mm-dd") %>) */\n',
            expand: true
        },
        files : {}
    };

    var _uglifyTargets = [
        pkgPath('blocks/schedulizer_calendar/auto.js'),
        pkgPath('blocks/schedulizer_calendar/view.js'),
        pkgPath('blocks/schedulizer_calendar/inline_script.js.txt'),
        pkgPath('js/app-dashboard.js')
    ];

    for( var i = 0; i < _uglifyTargets.length; i++ ){
        _currentConfigs.uglify.schedulizer.files[ _uglifyTargets[i] ] = _uglifyTargets[i];
    };


    /////////////////////////////// SASS BUILDS ///////////////////////////////
    _currentConfigs.sass.schedulizer = {
        options  : {
            style: 'compressed',
            compass: true
        },
        files    : [
            // schedulizer_calendar/view.css
            {src: [pkgPath('blocks/schedulizer_calendar/dev/view.scss')], dest: pkgPath('blocks/schedulizer_calendar/view.css')},
            // dashboard shit
            {src: [pkgPath('css/dev/app-dashboard.scss')], dest: pkgPath('css/app-dashboard.css')}
        ]
    }

    // Watch Tasks
    _currentConfigs.watch.schedulizer = {
        files : [pkgPath('**/*.dev.js'), pkgPath('**/*.scss')],
        tasks : ['newer:concat:schedulizer', 'sass_style_uncompressed', 'newer:sass:schedulizer']
    };

    // During watch tasks, change the sass output style to expanded
    grunt.registerTask('sass_style_uncompressed', 'Modify SASS output style', function(){
        grunt.config('sass.schedulizer.options.style', 'expanded');
    });

    // Register the schedulizer task to dev all ($: grunt schedulizer)
    grunt.registerTask('schedulizer_build', ['concat:schedulizer', 'uglify:schedulizer', 'sass:schedulizer']);


}