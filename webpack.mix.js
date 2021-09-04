let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'resources/assets/app-assets/vendors/css/perfect-scrollbar.min.css',
    'resources/assets/app-assets/vendors/css/prism.min.css',
    'resources/assets/app-assets/css/gantt_v3.css',
    'resources/assets/app-assets/css/circleposgres.css',
    'resources/assets/app-assets/js/crvclockpicker/crvclockpicker.min.css',
    'resources/assets/app-assets/js/crvclockpicker/crvclockpicker.min.css'
], 'public/css/plantilla.css')


.scripts([
    'resources/assets/app-assets/vendors/js/core/jquery-3.2.1.min.js',
    'resources/assets/app-assets/vendors/js/jquery-ui.min.js',
    'resources/assets/app-assets/js/jquery.fn.gantt_v3.js',
    'resources/assets/app-assets/vendors/js/core/popper.min.js',
    'resources/assets/app-assets/vendors/js/core/bootstrap.min.js',
    'resources/assets/app-assets/vendors/js/perfect-scrollbar.jquery.min.js',
    'resources/assets/app-assets/vendors/js/prism.min.js',
    'resources/assets/app-assets/vendors/js/jquery.matchHeight-min.js',
    'resources/assets/app-assets/vendors/js/screenfull.min.js',
    'resources/assets/app-assets/vendors/js/pace/pace.min.js',
    'resources/assets/app-assets/js/app-sidebar.js',
    'resources/assets/app-assets/js/notification-sidebar.js',
    'resources/assets/app-assets/js/customizer.js',
    'resources/assets/app-assets/js/jquery.contextMenu.min.js',
    'resources/assets/app-assets/js/jquery.ui.position.js',
    'resources/assets/app-assets/js/crvclockpicker/crvclockpicker.js'

], 'public/js/plantilla.js')
.js(['resources/assets/js/app.js'],'public/js/app.js');
