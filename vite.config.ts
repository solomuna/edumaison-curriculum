import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'path'

export default defineConfig({
  plugins: [react(), tailwindcss()],
  root: 'resources/react',
  base: '/react/',
  build: {
    outDir: '../../public/react',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        // App principale enfants
        main: resolve(__dirname, 'resources/react/index.html'),
        // Espace Mama Judi
        mama: resolve(__dirname, 'resources/react/mama.html'),
      }
    }
  },
  server: {
    proxy: {
      '/api': {
        target: 'http://edumaison.test',
        secure: false,
        changeOrigin: true,
      },
    },
  },
})
