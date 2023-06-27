const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                colors: {
                    "lab-purple": "#5267DF",
                    "lab-red": "#FA5959",
                    "lab-blue": "#242A45",
                    "lab-grey": "#9194A2",
                    "lab-white": "#F7F7F7",
                }
            },
            container: {
                center: true,
                padding: "1rem",
                screens: {
                    lg: "1124px",
                    xl: "1124px",
                    "2xl": "1124px"
                }
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};


module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                "lab-purple": "#5267DF",
                "lab-red": "#FA5959",
                "lab-blue": "#242A45",
                "lab-grey": "#9194A2",
                "lab-white": "#F7F7F7",
            }
        },
        container: {
            center: true,
            padding: "1rem",
            screens: {
                lg: "1124px",
                xl: "1124px",
                "2xl": "1124px"
            }
        },
    },
    plugins: [],
}
