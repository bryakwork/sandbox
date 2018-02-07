var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: './rollun-js.js',
    output: {
        filename: 'index.js',
        library: 'RollunJs',
        libraryTarget: 'var'
    },
    module: {
        loaders: [
            {
                test: /\.css$/,
                exclude: /node_modules/,
                loader: 'style-loader!css-loader'
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.html$/,
                loader: 'html-loader',
                query: {
                    minimize: true
                }
            },
            {
                test: /\.yml$/,
                loader: 'json-loader!yaml-loader',
            },
            {
                test: /\.txt$/,
                loader: 'raw-loader'
            },
            {
                test: /\.(png|jpg|jpeg|gif|svg|woff|woff2)$/,
                loader: 'url-loader?limit=10000'
            },
            {
                test: /\.(eot|ttf|wav|mp3)$/,
                loader: 'file-loader'
            }
        ]
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.common.js'
        }
    }
};
