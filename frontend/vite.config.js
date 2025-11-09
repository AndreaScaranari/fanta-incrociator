import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    port: 5173,
    watch: {
      usePolling: true,  // Questo è il fix principale per Docker
      interval: 1000      // Opzionale: controlla ogni secondo
    },
    hmr: {
      port: 5173
    }
  }
})