<template>
	<div>
		<el-dialog v-model="localDialogVisible" title="充值" width="400px" @close="handleClose">
			<el-form ref="rechargeForm" :model="formData" class="recharge-form">
				<el-form-item label="充值金额" prop="amount" required>
					<el-input v-model="formData.amount" placeholder="请输入充值金额" type="number"></el-input>
				</el-form-item>

				<el-form-item>
					<div class="payment-buttons">
						<el-button type="primary" @click="handleRecharge('wechat')">微信支付</el-button>
						<!-- <el-button type="success" @click="handleRecharge('alipay')">支付宝支付</el-button> -->
						<el-button type="success" @click="handleRecharge('alipay')">银行转账</el-button>
					</div>
				</el-form-item>

				<el-form-item v-if="qrCodeUrl">
					<div class="qr-code-container">
						<img :src="qrCodeUrl" alt="支付二维码" />
					</div>
				</el-form-item>
			</el-form>

			<template #footer>
				<span class="dialog-footer">
					<el-button @click="closeDialog">取消</el-button>
				</span>
			</template>
		</el-dialog>
	</div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { ElMessage } from 'element-plus';
import { recharge, rechargeConfig } from '@/api/app/recharge'
import { getPayWay, prepay, getPayResult } from '@/api/app/pay'
// 接收来自父组件的 props
const props = defineProps({
	dialogVisible: Boolean
});

// 声明 emit 事件
const emit = defineEmits(['update:dialogVisible']);

// 本地变量用于控制弹窗显示状态
const localDialogVisible = ref(props.dialogVisible);

// 监听 props 的变化以同步本地变量
watch(() => props.dialogVisible, (newVal) => {
	localDialogVisible.value = newVal;
});

// 表单数据模型
const formData = ref({
	amount: ''
});
const wallet = reactive({
	user_money: '',
	min_amount: 0
})

// 二维码地址
const qrCodeUrl = ref<string | null>(null);

// 关闭弹窗并通知父组件
const closeDialog = () => {
	localDialogVisible.value = false;
	emit('update:dialogVisible', false); // 通知父组件更新 dialogVisible 状态
};

// 处理对话框关闭事件
const handleClose = () => {
	closeDialog();
};
const getWallet = async () => {
	const data = await rechargeConfig()
	Object.assign(wallet, data)
}
// 模拟调用后端支付接口
const callPaymentApi = async (method, amount) => {
	const data = await recharge({
		money: amount
	})

	await prepay({
		order_id: data.order_id,
		from: data.from,
		pay_way: 2,
	})
};

// 处理充值按钮点击事件
const handleRecharge = async (method) => {
	if (!formData.value.amount || isNaN(Number(formData.value.amount))) {
		ElMessage.error('请输入有效的充值金额');
		return;
	}

	try {
		const result = await callPaymentApi(method, formData.value.amount);
		qrCodeUrl.value = result.qrCodeUrl; // 假设后端返回二维码链接
		ElMessage.success(`请使用${method === 'wechat' ? '微信' : '支付宝'}扫码支付`);
	} catch (error) {
		ElMessage.error(`支付失败：${error.message || error}`);
	}
};
getWallet()
</script>

<style scoped>
.recharge-form {
	margin-top: 20px;
}

.payment-buttons {
	display: flex;
	gap: 10px;
}

.qr-code-container {
	text-align: center;
	margin-top: 20px;
}

.qr-code-container img {
	max-width: 100%;
	height: auto;
}
</style>