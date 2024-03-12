// webpack.config.js
const path = require('path');

module.exports = {
  entry: './src/JS/badWordsFilter.js',
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: 'bundle.js'
  },
  mode: 'development'
};




