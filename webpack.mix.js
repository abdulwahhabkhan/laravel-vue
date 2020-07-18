const mix = require('laravel-mix');
require('laravel-mix-versionhash');
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
mix.extract();
mix.js('resources/js/app.js', 'public/js').sourceMaps()
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/landing.scss', 'public/css');
mix.versionHash();
