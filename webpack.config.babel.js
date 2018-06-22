import path from 'path';
import merge from 'webpack-merge';
import baseConfig from 'create-plesk-app/lib/webpack/config';

module.exports = (env = {}) => merge(baseConfig(env), {
    mode: env.dev ? 'development' : 'production',
    context: path.resolve(__dirname, 'frontend'),
    entry: './main',
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'src/htdocs/js'),
        libraryTarget: 'amd',
    },
    module: {
        rules: [
            {
                test: /\.js$/i,
                exclude: /node_modules/,
                loader: 'babel-loader',
            },
            {
                test: /\.(png|gif|svg|jpg|woff|woff2)$/i,
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]?[hash]',
                    publicPath: 'images/',
                    outputPath: '../images/',
                },
            },
        ],
    },
    externals: {
        '@plesk/ui-library': { amd: 'plesk-ui-library' },
    },
});
