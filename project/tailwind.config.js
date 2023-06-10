/** @type {import('tailwindcss').Config} */
const postcssPresetEnv = require('postcss-preset-env');
module.exports = {
  mode: 'jit',
  purge: [
    './public/**/*.html.twig',
    './src/**/*.php',
    './assets/**/*.{js, scss}',
    './templates/**/*.html.twig'
  ],
  content: [
    "/vendor/symfony/twig-bridge/Resources/views/Form/tailwind_2_layout.html.twig",
    "/assets/**/*.scss",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js"
  ],
  darkMode: 'class',
  theme: {
    extend: {
      aspectRatio: {
        '4/3': '4 / 3',
      },
      gridTemplateRows: {
        '[auto,auto,1fr]': 'auto auto 1fr',
      },
    },
  },
  plugins: [
    postcssPresetEnv(/* pluginOptions */),
    require("tw-elements/dist/plugin.cjs"),
    require('flowbite/plugin'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio')
  ],
}
