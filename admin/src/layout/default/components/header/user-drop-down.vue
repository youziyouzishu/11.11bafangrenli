<template>
    <el-dropdown class="px-2" @command="handleCommand">
        <div class="flex items-center">
            <el-avatar :size="34" :src="userInfo.avatar" />
            <div class="ml-3 mr-1">{{ userInfo.name }}</div>
            <el-badge :value="totalUnreadCount" class="item">
            </el-badge>
            <icon name="el-icon-ArrowDown" />
        </div>

        <template #dropdown>
            <el-dropdown-menu>
                <router-link to="/im">
                    <el-dropdown-item>
                        <div class="">客服消息</div><el-badge :value="totalUnreadCount" class="item">

                        </el-badge>
                    </el-dropdown-item>
                </router-link>
                <router-link to="/member/user/setting">
                    <el-dropdown-item>账号设置</el-dropdown-item>
                </router-link>
                <el-dropdown-item command="logout">退出登录</el-dropdown-item>

            </el-dropdown-menu>
        </template>
    </el-dropdown>
</template>

<script setup lang="ts">
import useUserStore from '@/stores/modules/user'
import feedback from '@/utils/feedback'
import { apiAdminMsgNotificationList } from '@/api/admin_msg'
import mp3File from '@/assets/video/dd920c06a01e5bb8b09678581e29d56f.mp3';
const userStore = useUserStore()
import { TUIStore, StoreName } from '@tencentcloud/chat-uikit-engine';


const totalUnreadCount = ref(0); // 总未读消息数
// 监听会话总未读消息数的变化
TUIStore.watch(StoreName.CONV, {
    totalUnreadCount: (count: number) => {
        totalUnreadCount.value = count;
    },
});


const ws = ref<WebSocket | null>(null);
const notifications = ref(0);


// 声音通知函数
const playNotificationSound = () => {
    let video = document.getElementById('videoElement');
    if (video) {
        try {
            video.play();
        } catch (e) {
            // 处理用户未交互导致的播放失败
            console.log('播放失败：', e);
            // 可以在这里添加逻辑，比如显示播放按钮供用户点击
        }
    }
};

// 显示通知函数
const showNotification = (message: string) => {
    playNotificationSound()
    //console.log(Notification.permission);
    if (Notification.permission === 'granted') {
        const notification = new Notification('New Message', {
            body: message,
            icon: 'path_to_your_icon.png', // 可选
            // ...其它通知选项
        });
        // 可以为通知添加点击事件等
        notification.onclick = () => {
            console.log('Notification clicked');
        };
    } else {
        feedback.notifySuccess(message)
    }
};


const HEARTBEAT_INTERVAL = 10000; // 每30秒发送一次心跳
let heartbeatTimer: number | null = null;


const MAX_RETRIES = 5; // 最大重试次数可以根据需要设置
let retryCount = 0;    // 当前重试次数
let timeout = 10000; // 设置超时时间为 2000 毫秒
let connectionTimeout: number | null = null;

// 发送心跳包的函数
const sendHeartbeat = () => {
    if (ws.value && ws.value.readyState === WebSocket.OPEN) {
        ws.value.send('ping'); // 发送心跳包，内容可以根据后端要求自定义
    }
};

// 启动心跳检测
const startHeartbeat = () => {
    if (heartbeatTimer !== null) {
        clearInterval(heartbeatTimer);
    }
    heartbeatTimer = setInterval(sendHeartbeat, HEARTBEAT_INTERVAL);
};

// 清除心跳检测
const clearHeartbeat = () => {
    if (heartbeatTimer !== null) {
        clearInterval(heartbeatTimer);
        heartbeatTimer = null;
    }
};




// 初始化WebSocket连接
const initWebSocket = () => {
    if (connectionTimeout != null) {
        clearTimeout(connectionTimeout);
    }
    connectionTimeout = setTimeout(() => {
        console.log("连接超时");
        ws.value.close();
    }, timeout);

    ws.value = new WebSocket('ws://127.0.0.1:8080/ws?token=' + userStore.token);

    ws.value.onopen = (event) => {
        if (connectionTimeout != null) {
            clearTimeout(connectionTimeout); // 连接成功后清除超时
        }
        sendHeartbeat()
        startHeartbeat(); // 调用startHeartbeat函数以解决已声明但未使用的问题
        console.log("connected success");
    };
    ws.value.onmessage = (event) => {
        const message = event.data;
        if (message === 'pong') {
            retryCount = 0; //心跳后将重试次数重置
            return;
        }
        // 显示通知
        showNotification(message);
        // 这里处理收到的消息...
        //console.log("接收到消息: " + event.data);
    };

    ws.value.onerror = (error) => {
        console.error('WebSocket Error:', error);
    };

    ws.value.onclose = (event) => {
        clearHeartbeat();
        console.log('WebSocket connection closed:', event);
        if (retryCount < MAX_RETRIES) {
            console.log(`reconnect ... (${retryCount + 1}/${MAX_RETRIES})`);
            setTimeout(initWebSocket, 3000); // 等待1秒后重试
            retryCount++; // 增加重试次数
        } else {
            console.log("reconnect reach max number，stop reconnect");
        }
    };
};

const requestNotificationPermission = () => {
    console.log("开始授权")
    if (Notification.permission === 'denied' || Notification.permission === 'default') {
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
            }
        });
    }
}

onMounted(async () => {


    //requestNotificationPermission()
    // 初始化WebSocket
    //initWebSocket();
    //setInterval(readmsg, HEARTBEAT_INTERVAL);
});

const readmsg = async () => {
    let no = await apiAdminMsgNotificationList();
    console.log("msg count", no[0].count);
    notifications.value = no[0].count;
}


onUnmounted(() => {
    // 清理工作
    if (ws.value) {
        ws.value.close();
    }
});

const userInfo = computed(() => userStore.userInfo)

const handleCommand = async (command: string) => {
    switch (command) {
        case 'logout':
            await feedback.confirm('确定退出登录吗？')
            userStore.logout()
    }
}
</script>
<style>
/* .item {
    margin-top: 10px;
    margin-right: 40px;
} */
</style>
