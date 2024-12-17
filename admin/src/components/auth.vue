<template>
	<div class="content-container">
		<div class="content-wrapper" :class="{ 'blurred': (signStatus !== 6) }">
			<!-- 实际内容 -->
			<slot></slot>
		</div>

		<!-- 没有权限时展示的遮罩层 -->
		<div v-if="signStatus < 4" class="no-permission">
			<el-card shadow="never" class="permission-card">
				<p>您没有访问此内容的权限。</p>
				<p class="mb-5">请先实名认证和授权e签宝。</p>
				<el-button type="success" @click="applyForPermission">实名授权</el-button>
			</el-card>
		</div>
		<div v-if="signStatus < 6 && signStatus >= 4" class="no-permission">
			<el-card shadow="never" class="permission-card">
				<p>您没有访问此内容的权限。</p>
				<p class="mb-5">请先签署合同。</p>
				<el-button type="success" @click="applyForSignPermission">签署合同</el-button>
			</el-card>
		</div>
	</div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import useUserStore from '@/stores/modules/user';
import { useRouter } from 'vue-router';
const userStore = useUserStore();
const router = useRouter();
// 计算属性来判断是否有权限
const signStatus = computed(() => userStore.getSignStatus());
// 申请权限的处理函数
const applyForPermission = () => {
	router.push("/member/user/verify");
};
// 签署合同
const applyForSignPermission = () => {
	router.push({
		path: '/member/user/verify', // 确保在路由配置中有名称为 'e_contract' 的路由
		query: {
			type: "plant_contract"
		}
	});
};
</script>

<style scoped>
.content-container {
	position: relative;
	min-height: 100vh;
	/* 让父容器至少占满整个视窗高度 */
}

.content-wrapper {
	position: relative;
}

.blurred {
	filter: blur(1px);
	/* 高斯模糊效果 */
}

.no-permission {
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	display: flex;
	align-items: center;
	justify-content: center;
	background: rgba(255, 255, 255, 0.1);
	/* 半透明背景 */
	z-index: 3000;
	/* 确保遮罩层在最上层 */
}

.permission-card {
	text-align: center;
	/* 文字和按钮居中 */
}
</style>