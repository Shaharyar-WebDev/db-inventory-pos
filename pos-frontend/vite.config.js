import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { VitePWA } from "vite-plugin-pwa";
import { fileURLToPath, URL } from "node:url";

export default defineConfig({
    base: "/pos/",

    plugins: [
        vue(),
        VitePWA({
            registerType: "autoUpdate",
            workbox: {
                globPatterns: ["**/*.{js,css,html,ico,png,svg}"],
                navigateFallback: "/pos/index.html",
            },
            manifest: {
                name: "POS",
                short_name: "POS",
                start_url: "/pos/",
                display: "standalone",
                background_color: "#ffffff",
                theme_color: "#000000",
            },
        }),
    ],

    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./src", import.meta.url)),
        },
    },

    build: {
        outDir: "../public/pos",
        emptyOutDir: true,
    },

    server: {
        host: "0.0.0.0",
        allowedHosts: ["tradingsoftware.ddev.site"],
        port: 5173,
        proxy: {
            "/api": "https://tradingsoftware.ddev.site",
        },
    },
});
