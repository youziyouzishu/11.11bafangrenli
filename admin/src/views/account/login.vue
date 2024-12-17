<template>
    <div class="login flex flex-col">
        <div class="flex-1 flex items-center justify-center">
            <div class="login-card flex rounded-md">
                <div class="flex-1 h-full hidden md:inline-block">
                    <image-contain :src="config.login_image" :width="400" height="100%" />
                </div>
                <div
                    class="login-form bg-body flex flex-col justify-center px-10 py-10 md:w-[400px] w-[375px] flex-none mx-auto">
                    <div class="text-center text-3xl font-medium mb-8">{{ config.web_name }}</div>
                    <el-form ref="formRef" :model="formData" size="large" :rules="rules">
                        <el-form-item prop="account">
                            <el-input v-model="formData.account" placeholder="请输入账号" @keyup.enter="handleEnter">
                                <template #prepend>
                                    <icon name="el-icon-User" />
                                </template>
                            </el-input>
                        </el-form-item>
                        <el-form-item prop="password">
                            <el-input ref="passwordRef" v-model="formData.password" show-password placeholder="请输入密码"
                                @keyup.enter="handleLogin">
                                <template #prepend>
                                    <icon name="el-icon-Lock" />
                                </template>
                            </el-input>
                        </el-form-item>
                    </el-form>
                    <div>
                        <el-checkbox v-model="readPolicy">我已阅读并同意
                            <router-link to="/policy/service" target="_blank">《八方人力协议》</router-link>
                            和
                            <router-link to="/policy/privacy" target="_blank">《隐私政策》</router-link>
                        </el-checkbox>
                    </div>
                    <div class="mb-3">
                        <!-- <el-checkbox v-model="remAccount">记住登陆账号</el-checkbox> -->
                    </div>

                    <el-button type="primary" size="large" :loading="isLock" @click="lockLogin">
                        登录
                    </el-button>
                    <!-- 确认对话框 -->
                    <el-dialog title="确认" v-model="dialogVisible" width="30%">
                        <div>请确认您已阅读并同意相关协议和隐私政策。</div>
                        <router-link to="/policy/service" target="_blank">《八方人力协议》</router-link>
                        和
                        <router-link to="/policy/privacy" target="_blank">《隐私政策》</router-link>

                        <template #footer>
                            <div class="dialog-footer">
                                <el-button @click="dialogVisible = false">取消</el-button>
                                <el-button type="primary" @click="confirmReadPolicy">确定</el-button>
                            </div>
                        </template>
                    </el-dialog>
                    <div>
                        <el-button type="text" @click="() => router.push(PageEnum.REGISTER)">
                            快速注册
                        </el-button>
                        <el-button type="text" @click="() => router.push(PageEnum.REGISTER)">
                            验证码登陆
                        </el-button>

                    </div>
                </div>
            </div>
        </div>

        <layout-footer />
    </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, reactive, ref, shallowRef } from 'vue'
import type { InputInstance, FormInstance } from 'element-plus'
import LayoutFooter from '@/layout/components/footer.vue'
import useAppStore from '@/stores/modules/app'
import useUserStore from '@/stores/modules/user'
import cache from '@/utils/cache'
import { ACCOUNT_KEY } from '@/enums/cacheEnums'
import { PageEnum } from '@/enums/pageEnum'
import { useLockFn } from '@/hooks/useLockFn'
const passwordRef = shallowRef<InputInstance>()
const formRef = shallowRef<FormInstance>()
const appStore = useAppStore()
const userStore = useUserStore()
const route = useRoute()
const router = useRouter()
const remAccount = ref(false)
const readPolicy = ref(false)
const dialogVisible = ref(false);
const config = computed(() => appStore.config)
const formData = reactive({
    account: '',
    password: ''
})
const rules = {
    account: [
        {
            required: true,
            message: '请输入账号',
            trigger: ['blur']
        }
    ],
    password: [
        {
            required: true,
            message: '请输入密码',
            trigger: ['blur']
        }
    ]
}
// 回车按键监听
const handleEnter = () => {
    if (!formData.password) {
        return passwordRef.value?.focus()
    }
    handleLogin()
}
// 登录处理
const handleLogin = async () => {
    if (!readPolicy.value) {
        // 如果未勾选，显示确认对话框
        dialogVisible.value = true;
    } else {
        // 执行登录逻辑
        await formRef.value?.validate()
        // 记住账号，缓存
        cache.set(ACCOUNT_KEY, {
            remember: remAccount.value,
            account: remAccount.value ? formData.account : ''
        })
        await userStore.login(formData)
        userStore.updateUserInfo()
        const {
            query: { redirect }
        } = route
        const path = typeof redirect === 'string' ? redirect : PageEnum.INDEX
        router.push(path)
    }
}

const confirmReadPolicy = () => {
    // 勾选复选框
    readPolicy.value = true;
    // 隐藏对话框
    dialogVisible.value = false;
    // 执行登录逻辑
    lockLogin()
};
const { isLock, lockFn: lockLogin } = useLockFn(handleLogin)

onMounted(() => {
    const value = cache.get(ACCOUNT_KEY)
    if (value?.remember) {
        remAccount.value = value.remember
        formData.account = value.account
    }
})
</script>

<style lang="scss" scoped>
.login {
    background-image: url('./images/login_bg.png');
    @apply min-h-screen bg-no-repeat bg-center bg-cover;

    .login-card {
        height: 400px;
    }

    .mb-5 {
        margin-bottom: 1.25rem;


    }

    /* 20px */
    a {
        color: #0827ea;
        /* Element Plus 默认主题色 */
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

}
</style>
