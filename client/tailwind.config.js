/** @type {import('tailwindcss').Config} */

const defaultTheme = require('tailwindcss/defaultTheme')

export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['"Inter var"', ...defaultTheme.fontFamily.sans],
        'mono': ['"Roboto"', ...defaultTheme.fontFamily.mono],
      },
      colors: {
        current: 'currentColor',
        'white': '#ffffff',
        'primary': {
          DEFAULT: '#0d459b',
          50: '#edf8ff',
          100: '#d6eeff',
          200: '#b5e4ff',
          300: '#83d4ff',
          400: '#48bbff',
          500: '#1e99ff',
          600: '#0679ff',
          700: '#0165ff',
          800: '#084dc5',
          900: '#0d459b',
        }
      },
    },
  },
  plugins: [require("daisyui"), require('tailwindcss-animated')],
  darkMode: 'false',
  daisyui: {
    themes: [
      {
        mytheme: {
          "primary": "#0d459b",
          "primary-content": "#084dc5"
        },
      },
      "light"
    ],
  }
}


