import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [react(), tailwindcss()],
  root: 'resources/react',
  base: '/react/',
  build: {
    outDir: '../../public/react',
    emptyOutDir: true,
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