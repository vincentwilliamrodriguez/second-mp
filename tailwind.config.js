import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

const baseColors = [
    'slate', 'gray', 'zinc', 'neutral', 'stone',
    'red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan',
    'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'
];

const colorsToCssVariables = ({ addBase, theme }) => {
    const allColors = theme('colors');
    const cssVariables = {};

    Object.entries(allColors).forEach(([colorName, colorValues]) => {
      if (typeof colorValues === 'object') {
        Object.entries(colorValues).forEach(([shade, value]) => {
          cssVariables[`--color-${colorName}-${shade}`] = value;
        });
      } else {
        cssVariables[`--color-${colorName}`] = colorValues;
      }
    });

    addBase({
      ':root': cssVariables
    });
  };



/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        {
            pattern: new RegExp(`^(bg|hover:bg|focus:bg|active:bg)-(${baseColors.join('|')})-(100|200|300|400|500|600|700|800|900)$`)
        }
    ],

    theme: {
        extend: {
            colors: {
              'accent-foreground': 'var(--color-accent-foreground)',
              'accent': 'var(--color-accent)',
              'accent-content': 'var(--color-accent-content)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                pulse: {
                    '50%': { opacity: '0.8' },
                },
            },
        },
    },

    plugins: [forms, typography, colorsToCssVariables],
};
