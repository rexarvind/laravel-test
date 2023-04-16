const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            container: {
                center: true,
            },
            colors: {
                blue: {
                    500: '#1565c0',
                },
            },
            boxShadow: {
                sm: 'rgba(0,0,0,0.12) 0 1px 3px, rgba(0,0,0,0.24) 0 1px 2px',
                DEFAULT: 'rgba(0,0,0,0.16) 0 3px 6px, rgba(0,0,0,0.23) 0 3px 6px',
                md: 'rgba(0,0,0,0.19) 0 10px 20px, rgba(0,0,0,0.23) 0 6px 6px',
                lg: 'rgba(0,0,0,0.25) 0 14px 28px, rgba(0,0,0,0.22) 0 10px 10px'
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
