<template>
	<el-button v-perms="['project_tasks/audit']" type="primary" @click="showPopup" link>审核</el-button>
	<popup ref="popupRef" title="审核" :async="true" width="550px" @confirm="submitAudit" @close="handleClose">

		<div class="mb-5"> <el-alert type="warning" title="温馨提示：审核员请认真阅读确认人力是否有资格招聘" :closable="false" show-icon></el-alert>
		</div>

		<div class="mb-3">{{ props.org_name }} <a class="mr-2" style="color:var(--el-color-primary);"
				:href="props.contract_url" target="_blank">查看用工方合同</a>
		</div>
		<el-radio-group v-model="auditResult">
			<el-radio label="2">通过</el-radio>
			<el-radio label="3">拒绝</el-radio>
		</el-radio-group>
		<el-input v-if="auditResult === '3'" class="mt-3" v-model="rejectReason" placeholder="请输入拒绝理由"></el-input>
	</popup>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, Ref } from 'vue';
import { apiProjectTasksAudit } from '@/api/project_tasks';
import Popup from '@/components/popup/index.vue';

const buttonDisabled = false; // 将 buttonDisabled 改为普通变量

const popupVisible: Ref<boolean> = ref(false);
const auditResult: Ref<string> = ref('2');
const rejectReason: Ref<string> = ref('');
const popupRef = ref<InstanceType<typeof Popup> | null>(null);

import { defineProps } from 'vue';
const emit = defineEmits(['audit-success'])

const props = defineProps({
	id: [Number, Array],
	contract_url: String,
	org_name: String,
	buttonDisabled: Boolean
});


const showPopup = () => {
	open();
};

const open = (type: string = 'add') => {
	popupRef.value?.open();
};

const handleClose = () => {
	// emit('close'); // Assuming you have an emit function
};

const submitAudit = async () => {
	if (auditResult.value === '3' && !rejectReason.value) {
		// this.$message.error('请填写拒绝理由'); // You may need to access the message function in a different way
	} else {
		popupVisible.value = false;
		await apiProjectTasksAudit({ id: props.id, is_show: auditResult.value, show_remarks: rejectReason.value });
	}
	popupRef.value?.close();
	emit('audit-success');
};

watch(() => popupVisible, (newVal) => {
	popupVisible.value = newVal;
});

onMounted(() => {
	popupVisible.value = popupVisible.value; // Assuming you have a prop named show
});
</script>