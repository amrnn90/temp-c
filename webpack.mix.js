const mix = require('laravel-mix');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

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

mix.js('resources/js/app.js', 'public/js')
  .js('resources/js/admin.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .sourceMaps()
  .webpackConfig({
    resolve: {
      alias: {
        "@": path.resolve(
          __dirname,
          "resources/js/"
        )
      }
    },
    plugins: [
      // new BundleAnalyzerPlugin()
    ],
    devtool: 'source-map'
  });