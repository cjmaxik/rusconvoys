const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

if (!elixir.config.production) elixir.config.sourcemaps = true;

elixir((mix) => {
    mix.sass('app.scss');

    mix.copy('resources/assets/js/vendor/tinymce', 'public/tinymce');
    mix.copy('resources/assets/sass/roboto/fonts/', 'public/build/font/');

    mix.webpack('app.js');

    mix.version([
        'css/app.css', 'js/app.js'
    ]);

    mix.browserSync({
        proxy: 'caddy',
        host:  'rusconvoys.dev',
        open:  false
    });
});
