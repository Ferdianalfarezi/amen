import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    server: {
        host: true, // 🔥 biar bisa diakses lewat jaringan (Ngrok, LAN, dll)
        hmr: {
            host: 'localhost', // kalau mau, nanti bisa ganti ke domain ngrok lo juga
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
})
