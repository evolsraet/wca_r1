import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@use "resources/sass/variables" as *;`
            }
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, './resources/js'),
            'swiper/vue': 'swiper/vue',
            'swiper': 'swiper',
        },
    },
    server: {
        host: '0.0.0.0', // 모든 네트워크 인터페이스에서 접속 가능
        port: 5173,      // Vite 서버의 포트 (필요에 따라 변경 가능)
        hmr: {
            // host: '192.168.10.185', // 외부 접속용 IP
            host: 'localhost',
        },
    },
});
