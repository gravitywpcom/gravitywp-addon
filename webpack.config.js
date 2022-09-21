const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

// Configuration object.
const config = ( env, options ) => {
	const PROD = 'production' === options.mode;

	return {
		// Create the entry points.
		// One for frontend and one for the admin area.
		entry: {
			// frontend and admin will replace the [name] portion of the output config below.
			scripts: './src/js/scripts.js',
			form_editor: './src/js/form_editor.js'
		},

		// Create the output files.
		// One for each of our entry points.
		output: {
			// [name] allows for the entry object keys to be used as file names.
			filename: PROD ? 'js/[name].min.js' : 'js/[name].js',
			// Specify the path.
			path: __dirname + '/'
		},

		devtool: PROD ? false : 'eval-cheap-module-source-map',

		// Setup a loader to transpile down the latest and great JavaScript so older browsers
		// can understand it.
		module: {
			rules: [
				{
					// Look for any .js files.
					test: /\.js$/,
					// Exclude the node_modules folder.
					exclude: /node_modules/,
					// Use babel loader to transpile the JS files.
					loader: 'babel-loader'
				},
				{
					test: /\.scss$/,
					use: [
						MiniCssExtractPlugin.loader,
						'css-loader',
						{
							loader: 'postcss-loader',
							options: {
								postcssOptions: {
									minimize: true,
									autoprefixer: {
										add: true,
										cascade: false,
									}
								},
								sourceMap: ! PROD,
							}
						},
						'sass-loader'
					]
				}
			]
		},
		plugins: [
			new MiniCssExtractPlugin( {
				filename: () => {
					const dir = 'css/';
					return PROD ? dir + 'style.min.css' : dir + 'style.css';
				},
				chunkFilename: '[id]', // Need to set this so we can use filename as a function.
			} )
		]
	}
}

// Export the config object.
module.exports = config;
