import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  server: {
    host: '127.0.0.1',   // 👈 Força IPv4, evita o [::]
    port: 5173,
    strictPort: true,
    origin: 'http://127.0.0.1:5173', // 👈 Corrige a URL que o Laravel usa
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
