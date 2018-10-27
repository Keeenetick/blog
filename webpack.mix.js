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
    'resourse/assets/admin/bootstrap/css/bootstrap.min.css',
    'resourse/assets/admin/font-awesome/4.5.0/css/font-awesome.min.css',
    'resourse/assets/admin/ionicons/2.0.1/css/ionicons.min.css',
    'resourse/assets/admin/dist/css/AdminLTE.min.css',
    'resourse/assets/admin/dist/css/skins/_all-skins.min.css'
],'public/css/admin.css');
mix.scripts([
    'resourse/assets/admin/plugins/jQuery/jquery-2.2.3.min.js',
    'resourse/assets/admin/bootstrap/js/bootstrap.min.js',
    'resourse/assets/admin/plugins/slimScroll/jquery.slimscroll.min.js',
    'resourse/assets/admin/plugins/fastclick/fastclick.js',
    'resourse/assets/admin/dist/js/app.min.js',
    'resourse/assets/admin/dist/js/demo.js',
],'public/js/admin.js');