import { createApp } from "vue";
import App from "./App.vue";
import install from "./install";
import "./permission";
import "./styles/index.scss";
import "virtual:svg-icons-register";
import preventReClick from "./components/directives/preventReClick.js"; // 引入自定义指令

const app = createApp(App);

// 注册全局指令
app.directive("prevent-reclick", preventReClick);

app.use(install);
app.mount("#app");

console.log("Vue version:", app.version);
