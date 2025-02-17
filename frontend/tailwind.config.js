// import daisyui from 'daisyui'
import flyonui from 'flyonui'
import flyonuiPlugin from 'flyonui/plugin'

/** @type {import('tailwindcss').Config} */
// eslint-disable-next-line no-undef
module.exports = {
  content: ['./src/**/*.{vue,js,ts}', './node_modules/flyonui/dist/js/*.js'],
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
    flyonuiPlugin,
  ],
  // daisyui: {
  //     themes: ["dark", "valentine", "sunset", "cyberpunk", "aqua"]
  // }
  flyonui: {
    themes: [
      {
        valentine: {
          primary: '#e96d7b',
          'primary-content': '#010811',
          secondary: '#a991f7',
          'secondary-content': '#130201',
          accent: '#66b1b3',
          'accent-content': '#ebddf1',
          neutral: '#af4670',
          'neutral-content': '#f0d6e8',
          'base-100': '#fae7f4',
          'base-200': '#cdd1d2',
          'base-300': '#afb2b3',
          'base-content': '#632c3b',
          info: '#2563eb',
          'info-content': '#000d09',
          success: '#16a34a',
          'success-content': '#010f04',
          warning: '#d97706',
          'warning-content': '#140e00',
          error: '#e74c3c',
          'error-content': '#130201',
        },
      },
    ],
  },
}
