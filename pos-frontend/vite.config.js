import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import { VitePWA } from "vite-plugin-pwa";
import { fileURLToPath, URL } from "node:url";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    base: "/terminal/",

    plugins: [
        tailwindcss(),
        vue(),
        VitePWA({
            registerType: "autoUpdate",
            workbox: {
                globPatterns: ["**/*.{js,css,html,ico,png,svg}"],
                navigateFallback: "/terminal/index.html",
                navigateFallbackAllowlist: [/^\/terminal/],
            },
            manifest: {
                name: "POS",
                short_name: "POS",
                start_url: "/terminal/",
                scope: "/terminal/",
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
        outDir: "../public/pos-terminal",
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
