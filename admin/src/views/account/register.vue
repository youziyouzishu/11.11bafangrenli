<template>
	<div class="login flex flex-col">
		<div class="flex-1 flex items-center justify-center">
			<div class="login-card flex rounded-md">
				<div class="flex-1 h-full hidden md:inline-block">
					<image-contain :src="config.login_image" :width="500" height="100%" />
				</div>
				<div
					class="login-form bg-body flex flex-col justify-center px-10 py-10 md:w-[400px] w-[375px] flex-none mx-auto">
					<div class="text-center text-3xl font-medium mb-8">{{ config.web_name }}</div>
					<el-form ref="formRef" :model="formData" size="large" :rules="rules">
						<el-form-item prop="account">
							<el-input v-model="formData.account" placeholder="请输入手机号" @keyup.enter="handleEnter">
								<template #prepend>
									<icon name="el-icon-User" />
								</template>
							</el-input>
						</el-form-item>
						<el-form-item prop="password">
							<el-input ref="passwordRef" v-model="formData.password" show-password placeholder="请输入密码"
								@keyup.enter="handleRegister">
								<template #prepend>
									<icon name="el-icon-Lock" />
								</template>
							</el-input>
						</el-form-item>
						<el-form-item prop="password_confirm">
							<el-input ref="password2Ref" v-model="formData.password_confirm" show-password
								placeholder="请确认密码" @keyup.enter="handleRegister">
								<template #prepend>
									<icon name="el-icon-Lock" />
								</template>
							</el-input>
						</el-form-item>
						<el-form-item prop="role_id">
							<el-select v-model="formData.role_id" placeholder="请选择注册类型">
								<el-option v-for="item in options" :key="item.value" :label="item.label"
									:value="item.value">
								</el-option>
							</el-select>
						</el-form-item>
						<ElFormItem prop="code">
							<ElInput v-model="formData.code" placeholder="请输入验证码">
								<template #suffix>
									<div class="flex justify-center leading-5 w-[90px] pl-2.5 border-l border-br">
										<VerificationCode ref="verificationCodeRef" @click-get="sendSms" />
									</div>
								</template>
							</ElInput>
						</ElFormItem>
						<el-form-item prop="invitecode" v-if="formData.role_id==1">
							<el-input v-model="formData.invitecode" placeholder="请输入邀请码 (非必填)"
								@keyup.enter="handleEnter">
							</el-input>
						</el-form-item>

					</el-form>
					<div class="mb-3">
						<el-checkbox v-model="readPolicy">我已阅读并同意
							<router-link to="/policy/service" target="_blank">《八方人力协议》</router-link>
							和
							<router-link to="/policy/privacy" target="_blank">《隐私政策》</router-link>
						</el-checkbox>
					</div>
					<el-button type="primary" size="large" :loading="isLock" @click="lockRegister">
						注册账号
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
	import { smsSend } from '@/api/app'
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
	const verificationCodeRef = shallowRef()
	const formData = reactive({
		account: '',
		password: '',
		password_confirm: '',
		code: '',
		role_id: '',//注册类型
		invitecode: '',//邀请码
	})
	const rules = {
		account: [
			{
				required: true,
				message: '请输入手机号',
				trigger: ['blur']
			}
		],
		password: [
			{
				required: true,
				message: '请输入密码',
				trigger: ['blur']
			}
		],
		//验证密码是否一致
		password_confirm: [
			{
				required: true,
				message: '请确认密码',
				trigger: ['blur']
			}
		],
		role_id: [
			{
				required: true,
				message: '请选择注册类型',
				trigger: ['change']
			}
		],
		//验证密码是否一致
		code: [
			{
				required: true,
				message: '请输入手机验证码',
				trigger: ['blur']
			}
		]
	}

	const sendSms = async () => {
		await formRef.value?.validateField(['mobile'])
		await smsSend({
			scene: "YZMDL",
			mobile: formData.account
		})
		verificationCodeRef.value?.start()
	}


	// 回车按键监听
	const handleEnter = () => {
		if (!formData.password) {
			return passwordRef.value?.focus()
		}
		handleRegister()
	}

	const confirmReadPolicy = () => {
		// 勾选复选框
		readPolicy.value = true;
		// 隐藏对话框
		dialogVisible.value = false;
		// 执行登录逻辑
		lockRegister()
	};

	// 登录处理
	// 登录处理
	const handleRegister = async () => {
		await formRef.value?.validate()
		if (!readPolicy.value) {
			// 如果未勾选，显示确认对话框
			dialogVisible.value = true;
		} else {

			// 记住账号，缓存
			cache.set(ACCOUNT_KEY, {
				remember: remAccount.value,
				account: remAccount.value ? formData.account : ''
			})
			await userStore.register(formData)
			const {
				query: { redirect }
			} = route
			const path = typeof redirect === 'string' ? redirect : PageEnum.INDEX
			router.push(path)
		}
	}
	const { isLock, lockFn: lockRegister } = useLockFn(handleRegister)

	onMounted(() => {
    if(route.query){
      if(route.query.invitecode){
        // console.log("======",route.query)
        formData.invitecode =  route.query.invitecode
      }
    }


		const value = cache.get(ACCOUNT_KEY)
		if (value?.remember) {
			remAccount.value = value.remember
			formData.account = value.account
		}



	})




	const options = ref([{
		value: '1',
		label: '人力公司'
	}, {
		value: '2',
		label: '业务公司'
	}, {
		value: '3',
		label: '文员'
	}])
</script>

<style lang="scss" scoped>
	.login {
		background-image: url('./images/login_bg.png');
		@apply min-h-screen bg-no-repeat bg-center bg-cover;

		.login-card {
			height: 600px;
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