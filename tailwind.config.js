/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html.twig",
    "./src/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        // Couleurs personnalisées du projet
        primary: {
          from: '#667eea',
          to: '#764ba2',
        },
        item: {
          from: '#f5f7fa',
          to: '#c3cfe2',
        },
        info: {
          bg: '#e3f2fd',
          text: '#1976d2',
        },
        text: {
          dark: '#333333',
          medium: '#666666',
        },
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        'gradient-item': 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
      },
    },
  },
  plugins: [],
}

