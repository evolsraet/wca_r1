import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/v1/sass/app.scss',
                'resources/v1/js/app.js',
                'resources/v2/sass/app.v2.scss',
                'resources/v2/js/app.v2.js',
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
                additionalData: `
                // @use "resources/v1/sass/variables" as v1;
                @use "resources/v2/sass/variables" as v2;`
            }
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, './resources/v1/js'),
            '@v2': path.resolve(__dirname, './resources/v2/js'),
            'swiper/vue': 'swiper/vue',
            'swiper': 'swiper',
        },
    },
    server: {
        host: '0.0.0.0', // 모든 네트워크 인터페이스에서 접속 가능
        port: 5173,      // Vite 서버의 포트 (필요에 따라 변경 가능)
        hmr: {
            // host: '192.168.10.185', // 외부 접속용 IP
            host: process.env.VITE_DEV_SERVER_HOST || 'localhost',
            port: process.env.VITE_DEV_SERVER_PORT || 5173,      // Vite 서버의 포트 (필요에 따라 변경 가능)
    },
    },
});
