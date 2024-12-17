import { fileURLToPath, URL } from "url";

import { defineConfig, PluginOption } from "vite";
import vue from "@vitejs/plugin-vue";
import vueJsx from "@vitejs/plugin-vue-jsx";
import { visualizer } from "rollup-plugin-visualizer";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { ElementPlusResolver } from "unplugin-vue-components/resolvers";
import {
  createStyleImportPlugin,
  ElementPlusResolve,
} from "vite-plugin-style-import";
import { createSvgIconsPlugin } from "vite-plugin-svg-icons";
import vueSetupExtend from "vite-plugin-vue-setup-extend";
// import legacyPlugin from '@vitejs/plugin-legacy'
// https://vitejs.dev/config/
export default defineConfig({
  base: "/business/",
  server: {
    host: "0.0.0.0",
    port: 5174,
  },

  plugins: [
    vue(),
    vueJsx(),
    visualizer({
      emitFile: false,
      open: true,
    }) as PluginOption,
    AutoImport({
      imports: ["vue", "vue-router"],
      resolvers: [ElementPlusResolver()],
      eslintrc: {
        enabled: true,
      },
    }),
    Components({
      resolvers: [ElementPlusResolver()],
    }),
    createStyleImportPlugin({
      resolves: [ElementPlusResolve()],
    }),
    createSvgIconsPlugin({
      // 配置路劲在你的src里的svg存放文件
      iconDirs: [fileURLToPath(new URL("./src/assets/icons", import.meta.url))],
      symbolId: "local-icon-[dir]-[name]",
    }),
    vueSetupExtend(),
    // legacyPlugin({
    //     targets: ['defaults', 'IE 11']
    // })
  ],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
  build: {
    rollupOptions: {
      manualChunks(id) {
        if (id.includes("node_modules")) {
          return id
            .toString()
            .split("node_modules/")[1]
            .split("/")[0]
            .toString();
        }
      },
    },
  },
});
