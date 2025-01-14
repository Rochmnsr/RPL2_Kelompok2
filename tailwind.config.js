/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "node_modules/preline/dist/*.js",
    ],
    darkMode: "class",
    theme: {
        extend: {
            backgroundImage: {
                "bg-img": "url('/public/img/bg.jpg')",
            },
            plugins: [require("preline/plugin")],
        },
    },
};
