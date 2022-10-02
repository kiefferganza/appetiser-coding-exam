/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './node_modules/flowbite/**/*.js'
  ],
  theme: {
    extend: {
      height: {
        16.5: '4.5rem'
      }
    },
    screens: {
      sm: '325px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1536px'
    },
    fontFamily: {
      sans: ['Roboto', 'sans-serif'],
      display: ['Roboto', 'sans-serif'],
      body: ['Roboto', 'sans-serif']
    },
    colors: {
      'app-gray': '#f0f5f9',
      'app-navy-blue': '#355974',
      'app-gray-2': '#9dafbc'
    }
  },
  plugins: [
    require('flowbite/plugin')
  ]
}
