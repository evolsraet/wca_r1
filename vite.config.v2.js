import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/v2/sass/app.v2.scss',
                'resources/v2/js/app.v2.js',
            ],
            refresh: true,
            buildDirectory: 'build/v2',
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
                additionalData: ``
            }
        }
    },
    resolve: {
        alias: {
            '@v2': path.resolve(__dirname, './resources/v2/js'),
            // '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
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
    build: {
        outDir: 'public/build/v2',
        manifest: true,
    },
    publicDir: 'node_modules/@mdi/font/fonts',
});
