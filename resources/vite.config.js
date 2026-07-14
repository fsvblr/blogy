import { defineConfig } from 'vite';

export default defineConfig({
    root: import.meta.dirname,

    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler',
            },
        },
    },
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                'js/main': './src/js/main.js',
                'css/style': './src/scss/style.scss',
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.includes('css/')) {
                        return '[name].[ext]';
                    }
                    return 'assets/[name].[ext]';
                },
            },
        },
    },
});
