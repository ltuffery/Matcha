import daisyui from 'daisyui'

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./src/**/*.{vue,js,ts}'],
    safelist: [
        'alert-success',
        'alert-warning',
        'alert-error',
        'alert-info',
        'w-16',
      ],
    plugins: [daisyui],
    daisyui: {
        themes: ["dark", "valentine", "sunset"]
    }
};
