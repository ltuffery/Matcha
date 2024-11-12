import daisyui from 'daisyui'

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./src/**/*.{vue,js,ts}'],
    plugins: [daisyui],
    daisyui: {
        themes: ["dark", "valentine", "sunset"]
    }
};
  