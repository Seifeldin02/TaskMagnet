import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        extend: {
            visibility: ['group-hover'],
        },
    },

    plugins: [
        forms, 
        typography,
        function({ addUtilities }) {
            addUtilities({
                '.line-through-thick': {
                    textDecoration: 'line-through',
                    'text-decoration-thickness': '5px', // Adjust thickness here
                    'text-decoration-color': 'currentColor',
                },
            }, ['responsive', 'hover', 'focus'])
        }
    ],
};