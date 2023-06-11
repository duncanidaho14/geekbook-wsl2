// const postcss = require('postcss');
// const postcssPresetEnv = require('postcss-preset-env');

// postcss([
//   postcssPresetEnv(/* pluginOptions */)
// ]).process('/project/assets/styles/app.scss' /*, processOptions */);

module.exports = {
  plugins: {
    'postcss-import': {},
    'tailwindcss/nesting': 'postcss-nesting',
    tailwindcss: {},
    autoprefixer: {},
    'postcss-preset-env': {
      features: { 'nesting-rules': false },
    },
  },
}
