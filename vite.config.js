import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import mkcert from "vite-plugin-mkcert";
import inject from "@rollup/plugin-inject";

export default defineConfig({
    plugins: [
        inject({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        commonjsOptions: { transformMixedEsModules: true } // Change
    }
});
