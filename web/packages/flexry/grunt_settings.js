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

    // Javascript
    _currentConfigs.uglify.flexry = {
        options: {
            banner: ''
        },
        files : [
            // flexry_gallery block auto.js
            {src: [pkgPath('blocks/flexry_gallery/dev/auto.dev.js')], dest: pkgPath('blocks/flexry_gallery/auto.js')},
            {src: [pkgPath('blocks/flexry_gallery/dev/inline_script.dev.js')], dest: pkgPath('blocks/flexry_gallery/inline_script.js.txt')},
            // lightbox
            {src: [pkgPath('js/dev/flexry-lightbox.dev.js')], dest: pkgPath('js/flexry-lightbox.min.js')},
            // rotating list
            {src: [pkgPath('blocks/flexry_gallery/templates/rotating_list/dev/view.dev.js')], dest: pkgPath('blocks/flexry_gallery/templates/rotating_list/view.js')},
            // slider
            {src: [pkgPath('blocks/flexry_gallery/templates/slider/dev/view.dev.js')], dest: pkgPath('blocks/flexry_gallery/templates/slider/view.js')},
            // grid
            {src: [pkgPath('blocks/flexry_gallery/templates/grid/dev/view.dev.js')], dest: pkgPath('blocks/flexry_gallery/templates/grid/view.js')}
        ]
    };

    // SASS
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
            // slider
            {src: [pkgPath('blocks/flexry_gallery/templates/slider/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/slider/view.css')},
            // grid
            {src: [pkgPath('blocks/flexry_gallery/templates/grid/dev/view.scss')], dest: pkgPath('blocks/flexry_gallery/templates/grid/view.css')}
        ]
    }

    // Watch
    _currentConfigs.watch.flexry = {
        files : [pkgPath('**/*.dev.js'), pkgPath('**/*.scss')],
        tasks : ['uglify:flexry', 'sass_style_uncompressed', 'sass:flexry']
    }

    // During watch tasks, change the sass output style to expanded
    grunt.registerTask('sass_style_uncompressed', 'Modify SASS output style', function(){
        grunt.config('sass.flexry.options.style', 'expanded');
    });

    // Register the flexry task to dev all ($: grunt flexry)
    grunt.registerTask('flexry', ['uglify:flexry', 'sass:flexry']);


}