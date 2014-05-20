/**
 * Sass and compass install:
 * http://stackoverflow.com/questions/19068382/sass-compile-error-stalenesscheckermutex-nameerror
 */
module.exports.extraConfigs = function( grunt, _currentConfigs ){

    // Generate path to file in package root
    function pkgPath( _path ){
        var _pkgPath = '../web/packages/flexry/%s';
        return _pkgPath.replace('%s', _path);
    }


    /////////////////////////////// CONCAT FILES ///////////////////////////////
    _currentConfigs.concat.flexry = { files: {} };

    // concat auto.js
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/auto.js') ] = [
        pkgPath('js/libs/jscolor.js'),
        pkgPath('blocks/flexry_gallery/dev/auto.dev.js')
    ];

    // concat inline_script.js.txt
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/inline_script.js.txt') ] = [
        pkgPath('blocks/flexry_gallery/dev/inline_script.dev.js')
    ];

    // concat dicer template : for 1.1 release; but leave build scripts in!
    /*_currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/dicer/view.js') ] = [
        pkgPath('js/libs/greensock/uncompressed/TimelineMax.js'),
        pkgPath('js/libs/greensock/uncompressed/TweenMax.js'),
        pkgPath('js/libs/greensock/uncompressed/easing/*.js'),
        pkgPath('js/libs/greensock/uncompressed/plugins/*.js'),
        pkgPath('js/libs/greensock/uncompressed/utils/*.js'),
        pkgPath('blocks/flexry_gallery/templates/dicer/dev/view.dev.js')
    ];*/

    // concat camera template
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/pixedelic_camera/view.js') ] = [
        pkgPath('js/libs/camera/camera.js'),
        pkgPath('js/libs/camera/jquery.easing.1.3.js'),
        pkgPath('blocks/flexry_gallery/templates/pixedelic_camera/dev/view.dev.js')
    ];

    // owl slider
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/owl_slider/view.js') ] = [
        pkgPath('js/libs/owl.carousel.js'),
        pkgPath('blocks/flexry_gallery/templates/owl_slider/dev/view.dev.js')
    ];

    // concat accordion template
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/accordion/view.js') ] = [
        pkgPath('blocks/flexry_gallery/templates/accordion/dev/view.dev.js')
    ];

    // concat grid template
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/grid/view.js') ] = [
        pkgPath('js/libs/masonry-3.1.4.js'),
        pkgPath('blocks/flexry_gallery/templates/grid/dev/view.dev.js')
    ];

    // concat rotating_list template
    _currentConfigs.concat.flexry.files[ pkgPath('blocks/flexry_gallery/templates/rotating_list/view.js') ] = [
        pkgPath('blocks/flexry_gallery/templates/rotating_list/dev/view.dev.js')
    ];

    // concat flexry lightbox
    _currentConfigs.concat.flexry.files[ pkgPath('js/flexry-lightbox.js') ] = [
        pkgPath('js/dev/flexry-lightbox.dev.js')
    ];


    /////////////////////////////// UGLIFY FILES ///////////////////////////////
    _currentConfigs.uglify.flexry = {
        options: {
            banner: '/*! FLEXRY - Build v<%= pkg.version %> (<%= grunt.template.today("yyyy-mm-dd") %>) */\n',
            expand: true
        },
        files : {}
    };

    var _uglifyTargets = [
        pkgPath('blocks/flexry_gallery/auto.js'),
        pkgPath('blocks/flexry_gallery/inline_script.js.txt'),
        pkgPath('js/flexry-lightbox.js'),
        pkgPath('blocks/flexry_gallery/templates/rotating_list/view.js'),
        //pkgPath('blocks/flexry_gallery/templates/dicer/view.js'),
        pkgPath('blocks/flexry_gallery/templates/accordion/view.js'),
        pkgPath('blocks/flexry_gallery/templates/grid/view.js'),
        pkgPath('blocks/flexry_gallery/templates/pixedelic_camera/view.js'),
        pkgPath('blocks/flexry_gallery/templates/owl_slider/view.js')
    ];

    for( var i = 0; i < _uglifyTargets.length; i++ ){
        _currentConfigs.uglify.flexry.files[ _uglifyTargets[i] ] = _uglifyTargets[i];
    };


    /////////////////////////////// SASS BUILDS ///////////////////////////////
    _currentConfigs.sass.flexry = {
        options  : {
            style: 'compressed',
            compass: true
        },
        files    : [
            // lightbox
            {src: [pkgPath('css/dev/flexry-lightbox.scss')], dest: pkgPath('css/flexry-lightbox.min.css')},
            // block default view
            {src: [pkgPath('blocks/flexry_gallery/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/view.css')},
            // rotating list
            {src: [pkgPath('blocks/flexry_gallery/templates/rotating_list/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/rotating_list/view.css')},
            // dicer
            //{src: [pkgPath('blocks/flexry_gallery/templates/dicer/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/dicer/view.css')},
            // pixedelic camera
            {src: [pkgPath('blocks/flexry_gallery/templates/pixedelic_camera/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/pixedelic_camera/view.css')},
            // owl slider
            {src: [pkgPath('blocks/flexry_gallery/templates/owl_slider/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/owl_slider/view.css')},
            // grid
            {src: [pkgPath('blocks/flexry_gallery/templates/accordion/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/accordion/view.css')},
            // grid
            {src: [pkgPath('blocks/flexry_gallery/templates/grid/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/grid/view.css')}
        ]
    }

    // Watch Tasks
    _currentConfigs.watch.flexry = {
        files : [pkgPath('**/*.dev.js'), pkgPath('**/*.scss')],
        tasks : ['newer:concat:flexry', 'sass_style_uncompressed', 'newer:sass:flexry']
    };

    // During watch tasks, change the sass output style to expanded
    grunt.registerTask('sass_style_uncompressed', 'Modify SASS output style', function(){
        grunt.config('sass.flexry.options.style', 'expanded');
    });

    // Register the flexry task to dev all ($: grunt flexry)
    grunt.registerTask('build_flexry', ['concat:flexry', 'uglify:flexry', 'sass:flexry']);


}