import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Primary & Accents
                'deep-teal': '#0F4C5C',
                'teal-mid': '#1A6B7A',
                'teal-light': '#2A8FA0',
                'warm-amber': '#E8A838',
                'amber-light': '#F9D679',
                'amber-pale': '#FDF4E3',

                // Neutrals & Surfaces
                'ink': '#0A3040',
                'slate': '#4A7A80',
                'muted': '#7AACAA',
                'mint-white': '#F0FAFA',
                'border-teal': '#C8E8E4',

                // Status Colors
                'success-bg': '#DCFCE7',
                'success-text': '#16A34A',
                'warning-bg': '#FEF9C3',
                'warning-text': '#B45309',
                'error-bg': '#FEE2E2',
                'error-text': '#DC2626',
                'review-bg': '#EDE9FE',
                'review-text': '#7C3AED',
            },
            fontFamily: {
                // Heading font from Figma
                'display': ['Fraunces', ...defaultTheme.fontFamily.serif],
                // UI/Body font from Figma
                'sans': ['"DM Sans"', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'button': '11px',
                'card': '20px',
                'input': '10px',
            },
            boxShadow: {
                // Custom Shadow Scale from your Figma Guide
                'S': '0 2px 8px rgba(15,76,92, 0.08)',
                'M': '0 8px 24px rgba(15,76,92, 0.10)',
                'L': '0 16px 40px rgba(15,76,92, 0.12)',
                'XL': '0 24px 60px rgba(15,76,92, 0.16)',
                'amber-glow': '0 8px 24px rgba(232,168,56, 0.30)',
                'teal-glow': '0 8px 24px rgba(15,76,92, 0.25)',
            },
            backgroundImage: {
                'primary-gradient': 'linear-gradient(135deg, #0F4C5C 0%, #1A6B7A 100%)',
                'amber-gradient': 'linear-gradient(135deg, #E8A838 0%, #F9D679 100%)',
                'match-gradient': 'linear-gradient(90deg, #0F4C5C 0%, #E8A838 100%)',
                'hero-gradient': 'linear-gradient(160deg, #0F4C5C 0%, #2A8FA0 100%)',
            },
        },
    },

    plugins: [forms],
};
