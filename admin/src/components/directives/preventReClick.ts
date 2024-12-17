// directives/preventReClick.js
import { h, createApp } from "vue";
import { ElIcon } from "element-plus";
import { Loading } from "@element-plus/icons-vue";

export default {
  beforeMount(el, binding) {
    el.addEventListener("click", () => {
      if (!el.disabled) {
        // 设置 loading 状态
        el.__vueLoading = true;
        el.disabled = true;

        // 动态创建加载图标
        const loadingIcon = createApp({
          render() {
            return h(
              ElIcon,
              { class: "el-icon-loading" },
              {
                default: () => h(Loading),
              }
            );
          },
        }).mount(document.createElement("div"));

        // 插入图标并添加样式
        el.classList.add("is-loading");
        el.prepend(loadingIcon.$el);

        setTimeout(() => {
          // 恢复正常状态
          el.__vueLoading = false;
          el.disabled = false;
          el.classList.remove("is-loading");
          el.removeChild(loadingIcon.$el);
        }, binding.value || 1000); // 默认2秒，可以通过绑定值自定义时间
      }
    });
  },
};
