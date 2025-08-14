import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig(({ mode }) => {
    // .env 파일에서 환경변수 로드
    const env = loadEnv(mode, process.cwd(), '');
    
    return {
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
                additionalData: ``,
                // Deprecation warning 억제
                quietDeps: true,
                silenceDeprecations: ['legacy-js-api', 'import', 'global-builtin', 'color-functions']
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
        port: parseInt(env.VITE_PORT) || 5173,      // .env의 VITE_PORT 사용
        hmr: {
            port: parseInt(env.VITE_PORT) || 5173,
            // Laravel Sail 환경에서는 클라이언트에서 호스트 주소로 접근
            clientPort: parseInt(env.VITE_PORT) || 5173,
        },
        watch: {
            usePolling: true, // Docker 환경에서 파일 변경 감지를 위해
        },
    },
    build: {
        outDir: 'public/build/v2',
        manifest: 'manifest.json',
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    // MDI 폰트 파일들을 루트에 배치
                    if (assetInfo.name && assetInfo.name.includes('materialdesignicons-webfont')) {
                        return '[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                }
            }
        }
    },
};
});
