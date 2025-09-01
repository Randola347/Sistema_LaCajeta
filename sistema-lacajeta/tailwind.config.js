// tailwind.config.js
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './storage/framework/views/*.php',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  safelist: [
    // Clases que usamos en carrito, ventas, botones flotantes, etc.
    'bg-green-600',
    'hover:bg-green-700',
    'text-white',
    'bg-purple-600',
    'hover:bg-purple-700',
    'w-16',
    'h-16',
    'rounded-full',
    'shadow-2xl',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
