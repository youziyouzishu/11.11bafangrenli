<script setup lang="ts">
import { useDark, useWindowSize, useThrottleFn } from "@vueuse/core";
import useAppStore from "./stores/modules/app";
import useSettingStore from "./stores/modules/setting";
import { ScreenEnum } from "./enums/appEnums";
import zhCn from 'element-plus/es/locale/lang/zh-cn'

import {
    TUIStore,
    StoreName,
    TUITranslateService,
    TUIConversationService,
} from "@tencentcloud/chat-uikit-engine";
import TUINotification from "./TUIKit/components/TUINotification/index";
import { locales } from "./views/account/locales";
import { TUILogin } from '@tencentcloud/tui-core';
import useUserStore from '@/stores/modules/user';
const userStore = useUserStore();
import { framework } from './TUIKit/adapter-vue';
import { TUIChatKit } from './TUIKit';
import { watch } from 'vue';


TUITranslateService.provideLanguages(locales);
TUITranslateService.useI18n();



TUITranslateService.changeLanguage("zh")

const route = useRoute();



const appStore = useAppStore();
const settingStore = useSettingStore();
const elConfig = {
    zIndex: 3000,
    locale: zhCn,
};
const isDark = useDark();

// 监听 userInfo.im_user_sign 的变化
watch(
    () => userStore.userInfo.im_user_sign,
    (im_user_sign) => {
        if (im_user_sign) {
            // 当 userInfo.im_user_sign 不为空时执行的函数
            TUILogin.login({
                SDKAppID: 1600054212,
                userID: "hr_" + userStore.userInfo.sn,
                userSig: im_user_sign,
                useUploadPlugin: true,
                framework,
            }).then((e) => {

                setTimeout(() => {
                    if (route.query.conversationId) {
                        TUIConversationService.getConversationProfile(route.query.conversationId)
                        TUIConversationService.switchConversation(route.query.conversationId)
                            .then(() => {
                                console.log('Switched conversation successfully');
                                console.log(route.query.conversationId);
                            })
                            .catch((error) => {
                                console.error('Failed to switch conversation:', error);
                            });
                    }

                }, 500);
            })
                .catch((error) => {

                })

            /**
             * Init TUINotification configuration.
             * 初始化 TUINotification 相关配置信息
             */
            TUINotification.setNotificationConfiguration({
                // 是否显示预览信息
                showPreviews: true,
                // 是否允许通知
                allowNotifications: true,
                // 通知标题
                notificationTitle: "八方人力",
                // 通知图标
                notificationIcon: "https://bf.sf0000.com.cn/uploads/images/20240630/20240630185207a20537816.png",
            });

            /**
        * Listen for new messages and use notification components.
        * This capability is only available in the web environmen.
        * 使用 TUI 相关能力监听 newMessageList 字段 进行消息通知
        * 该能力仅可使用在原生 Web 环境下
        */
            TUIStore.watch(StoreName.CHAT, {
                // 监听 newMessageList 字段的变化
                newMessageList: (newMessageList) => {
                    // 回调函数返回新的 messageList
                    if (Array.isArray(newMessageList)) {
                        console.log(newMessageList)
                        newMessageList.forEach(message => TUINotification.notify(message));
                        console.log(newMessageList);

                    }

                }
            });
        }
    },
    { immediate: true } // 如果需要立即执行一次，可以加上这个选项
);



onMounted(async () => {


    //设置主题色
    settingStore.setTheme(isDark.value);
    // 获取配置
    const data: any = await appStore.getConfig();


    // 设置网站logo
    let favicon: HTMLLinkElement = document.querySelector('link[rel="icon"]')!;
    if (favicon) {
        favicon.href = data.web_favicon;
        return;
    }


    favicon = document.createElement("link");
    favicon.rel = "icon";
    favicon.href = data.web_favicon;
    document.head.appendChild(favicon);



});


const { width } = useWindowSize();
watch(
    width,
    useThrottleFn((value) => {
        if (value > ScreenEnum.SM) {
            appStore.setMobile(false);
            appStore.toggleCollapsed(true);
        } else {
            appStore.setMobile(true);
            appStore.toggleCollapsed(true);
        }
        if (value < ScreenEnum.MD) {
            appStore.toggleCollapsed(true);
        }
    }),
    {
        immediate: true,
    }
);
</script>

<template>
    <el-config-provider :locale="elConfig.locale" :z-index="elConfig.zIndex">
        <router-view />
    </el-config-provider>
</template>

<style></style>


<style scoped>
::v-deep .el-scrollbar .el-card {
    overflow: visible;
}

::v-deep .el-tabs__content {
    overflow: visible;
}

::v-deep .el-scrollbar .el-table {
    overflow: visible;
}

::v-deep .el-table__header-wrapper {
    position: sticky;
    top: 0px;
    z-index: 1000;
    overflow: hidden;
}



::v-deep .el-drawer__body .el-table__header-wrapper {
    top: 0
}

.el-icon-loading {
    animation: rotating 1s linear infinite;
}

@keyframes rotating {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>