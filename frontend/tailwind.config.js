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
          "color-scheme": "light",
          "primary": "#e96d7b",
          "secondary": "#a991f7",
          "accent": "#66b1b3",
          "neutral": "#af4670",
          "neutral-content": "#f0d6e8",
          "base-100": "#fae7f4",
          "base-content": "#632c3b",
          "info": "#2563eb",
          "success": "#16a34a",
          "warning": "#d97706",
          "error": "oklch(73.07% 0.207 27.33)",
          "--rounded-btn": "1.9rem",
          "--tab-radius": "0.7rem",
        },
      },
    ],
  },
}
