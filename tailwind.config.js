import colors from "tailwindcss/colors.js";

const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
export default {
    // presets: [
    //     require('./vendor/tallstackui/tallstackui/tailwind.config.js')
    // ],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/tallstackui/tallstackui/src/**/*.php',

    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors:{
                gray: colors.neutral,
                primary: colors.indigo,
            }

        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
    darkMode: 'class',
}
