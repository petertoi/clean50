const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const SpritesmithPlugin = require( 'webpack-spritesmith' );
const SVGSpritemapPlugin = require( 'svg-spritemap-webpack-plugin' );
const mix = require( 'laravel-mix' );
require( 'laravel-mix-polyfill' );
require( 'laravel-mix-versionhash' );
require( 'laravel-mix-criticalcss' );

/*
 * -----------------------------------------------------------------------------
 * Theme Export Process
 * -----------------------------------------------------------------------------
 * Configure the export process in `webpack.mix.export.js`. This bit of code
 * should remain at the top of the file here so that it bails early when the
 * `export` command is run.
 * -----------------------------------------------------------------------------
 */

if ( process.env.export ) {
	require( './resources/assets/build/webpack.mix.export.js' );
	return;
}
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

const config = require( './resources/assets/build/config' );

const assetsDir = config.paths.assets;
const outputDir = config.paths.output;

const options = {
	extractVueStyles: false,
	processCssUrls: false,
	purifyCss: false,
	postCss: [
		require( 'autoprefixer' ),
	],
};

mix
	.options( options )
	.setPublicPath( outputDir )
	.sourceMaps( false )
	.sass( `${ assetsDir }/scss/main.scss`, `${ outputDir }/css/main.css` )
	.criticalCss(
		{
			enabled: mix.inProduction(),
			paths: {
				base: config.devUrl,
				templates: `./${ outputDir }/css/critical/`,
			},
			urls: config.criticalCss.urls,
			options: {
				minify: true,
			},
		},
	)
	.autoload( {
		jquery: [ '$', 'window.jQuery' ],
	} )
	.js( `${ assetsDir }/js/main.js`, `${ outputDir }/js/main.js` )
	.js( `${ assetsDir }/js/admin-acf.js`, `${ outputDir }/js/admin-acf.js` )
	.polyfill( {
		enabled: true,
		useBuiltIns: 'usage',
		targets: false,
	} )
	.extract()
	.copy( `${ assetsDir }/fonts/**/*`, `${ outputDir }/fonts` )
	.copy( `${ assetsDir }/img/**/*`, `${ outputDir }/img` )
	.copy( `${ assetsDir }/lang/**/*.mo`, `${ outputDir }/lang` )
	.copy( `${ assetsDir }/svg/**/*`, `${ outputDir }/svg` )
	.copy( `${ assetsDir }/sprites/*`, `${ outputDir }/sprites` )
	.browserSync( {
		proxy: config.devUrl,
		files: config.watch,
	} )
	.webpackConfig( {
		plugins: [
			new CleanWebpackPlugin(),
			new SpritesmithPlugin( {
				src: {
					cwd: `${ assetsDir }/sprites/img/`,
					glob: '**/*.png',
				},
				target: {
					image: `${ assetsDir }/sprites/map.png`,
					css: `${ assetsDir }/scss/common/_sprite.scss`,
				},
				apiOptions: {
					cssImageRef: '../sprites/map.png',
				},
			} ),
			new SVGSpritemapPlugin(
				[ `${ assetsDir }/sprites/svg/**/*.svg` ],
				{
					output: {
						filename: 'sprites/map.svg',
						svg4everybody: true,
						svgo: {
							plugins: [
								{
									removeAttrs: { attrs: '(stroke|fill)' },
								},
								{
									removeDimensions: true,
								},
							],
						},
					},
				},
			),
		],
		externals: {
			jquery: 'jQuery',
		},
	} );

if ( mix.inProduction() ) {
	mix.versionHash();
}
