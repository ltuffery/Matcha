// import daisyui from 'daisyui'
import flyonui from 'flyonui'
import flyonuiPlugin from 'flyonui/plugin'

/** @type {import('tailwindcss').Config} */
// eslint-disable-next-line no-undef
module.exports = {
    content: [
      './src/**/*.{vue,js,ts}',
      './node_modules/flyonui/dist/js/*.js',
    ],
    safelist: [
        'alert-success',
        'alert-warning',
        'alert-error',
        'alert-info',
        'w-16',
      ],
    plugins: [
      // daisyui,
      flyonui,
      flyonuiPlugin
    ],
    // daisyui: {
    //     themes: ["dark", "valentine", "sunset", "cyberpunk", "aqua"]
    // }
};
