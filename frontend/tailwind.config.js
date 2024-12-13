import daisyui from 'daisyui'

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./src/**/*.{vue,js,ts}'],
    safelist: [
        'alert-success',
        'alert-warning',
        'alert-error',
        'alert-info',
      ],
    plugins: [daisyui],
    daisyui: {
        themes: ["dark", "valentine", "sunset"]
    }
};
  