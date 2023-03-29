/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/*.js",
    "./assets/styles/*.scss",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
