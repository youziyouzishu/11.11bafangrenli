<template>
	<div class="form-drawer">
		<Drawer ref="drawerRef" :title="title" :async="true" width="95%" @close="handleClose">
			<template #title>
				<div>
					<span>{{ title }}</span>
					<el-button type="text" icon="el-icon-close" @click="handleClose">自定义关闭</el-button>
				</div>
			</template>

			<template #default>
				<el-table v-loading="loading" :data="logs" stripe>
					<el-table-column label="日志ID" prop="id" />
					<el-table-column label="项目名称" prop="projectinfo.project_name" show-overflow-tooltip>
						<template #default="{ row }">
							<div
								:style="row.is_diff.includes('projectinfo.project_name') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.projectinfo.project_name }}
							</div>
						</template>
					</el-table-column>
					<el-table-column :label="roleNames[activeTab]" prop="userinfo.psn_name" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('userinfo.psn_name') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.userinfo.psn_name }}
							</div>
						</template>
					</el-table-column>
					<el-table-column v-if="activeTab == '4'" :label="roleNames[2]" prop="recruit_name" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('recruit_name') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.recruit_name }}
							</div>
						</template>
					</el-table-column>
					<el-table-column v-if="activeTab == '4'" :label="roleNames[3]" prop="onsite_name" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('onsite_name') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.onsite_name }}
							</div>
						</template>
					</el-table-column>

					<el-table-column v-if="activeTab != '4'" label="电话" prop="userinfo.psn_mobile" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('userinfo.psn_mobile') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.userinfo.psn_mobile }}
							</div>
						</template>
					</el-table-column>

					<el-table-column label="审核状态" prop="status" width="105" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('status') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								<el-tag v-if="row.status == 0">待审核</el-tag>
								<el-tag v-if="row.status == 1">待签署</el-tag>
								<el-tag type="success" v-else-if="row.status == 2">完成</el-tag>
								<el-tag type="danger" v-else-if="row.status == 3">拒绝</el-tag>
							</div>
						</template>
					</el-table-column>

					<el-table-column v-if="activeTab == '4'" width="120" label="工作状态" prop="work_status" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('work_status') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ getWorkStatusLabel(row.work_status) }}
							</div>
						</template>
					</el-table-column>
					<el-table-column label="备注说明" prop="remarks" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('remarks') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.remarks }}
							</div>
						</template>
					</el-table-column>
					<el-table-column label="企业端操作人" prop="admin_user" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('admin_user') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.admin_user }}
							</div>
						</template>
					</el-table-column>
					<el-table-column label="APP端操作人" prop="client_user" show-overflow-tooltip>
						<template #default="{ row }">
							<div :style="row.is_diff.includes('client_user') ? { backgroundColor: 'rgb(250, 212, 0)' } : {}">
								{{ row.client_user }}
							</div>
						</template>
					</el-table-column>
					<el-table-column label="变更时间" prop="create_time" width="200px" show-overflow-tooltip />
				</el-table>
			</template>
			<template #footer>
				<span class="drawer-footer">
					<el-button @click="closeDrawer">关闭</el-button>
				</span>
			</template>
		</Drawer>
	</div>
</template>

<script lang="ts" setup>
import { ref, defineProps, defineEmits, defineExpose } from 'vue';
import Drawer from '@/components/drawer/index.vue';
import { apiProjectTasksAuditLogsLists } from '@/api/project_tasks_audit_logs';
import { ElMessage } from 'element-plus';

// Props
const props = defineProps<{
	title: string;
	formData: Record<string, any>;
	formRules: Record<string, any>;
	auditId: number;
	visible: boolean;
	popupTitle: string;
	activeTab: string;
}>();

const roleNames = { "2": "招聘专员", "3": "驻场经理", "4": "入职员工" }

// 状态选项
const statusOptions = [
	{ label: '全部', value: null },
	{ label: '待审核', value: 0 },
	{ label: '待签署', value: 1 },
	{ label: '完成', value: 2 },
	{ label: '拒绝', value: 3 },
];

const work_statusOptions = [
	{ label: '待到岗', value: 1 },
	{ label: '已到岗', value: 2 },
	{ label: '流失', value: 3 },
	{ label: '离职', value: 4 },
];

// Emits
const emit = defineEmits(['close', 'update:visible']);

// Refs
const drawerRef = ref<InstanceType<typeof Drawer>>();

// 审计日志数据
const logs = ref<any[]>([]);
const loading = ref(false); // 加载状态

const now = ref<any>({}); // 当前行数据

// 获取审计日志
const fetchLogs = async () => {
	try {
		loading.value = true;
		const response = await apiProjectTasksAuditLogsLists({ audit_id: props.auditId });
		const rawLogs = response.lists || [];

		// 预处理数据
		logs.value = rawLogs.map((log, index) => {

			log.is_diff = [];
			if (index === 0) {
				now.value = log;
			} else {
				for (const key in now.value) {
					if (key === "UpdatedAt" || key === "ID" || key === "is_diff" || key === "user") {
						continue;
					}
					if (now.value[key] !== log[key]) {
						log.is_diff.push(key);
						if (index === 1) {
							rawLogs[index - 1].is_diff.push(key);
						}
					}
				}
				now.value = log;
			}
			return log;
		});
	} catch (error) {
		ElMessage.error('获取日志失败');
	} finally {
		loading.value = false;
	}
};

// 获取工作状态标签
const getWorkStatusLabel = (value: number) => {
	const status = work_statusOptions.find(option => option.value === value);
	return status ? status.label : '';
};

// Methods
const handleClose = () => {
	closeDrawer();
	logs.value = [];
	emit('close');
};

const closeDrawer = () => {
	emit('update:visible', false);
};

// Expose methods
const open = () => {
	drawerRef.value?.open();
	fetchLogs();
};

const close = () => {
	drawerRef.value?.close();

};

defineExpose({
	open,
	close,
});
</script>

<style scoped>
.form-drawer .el-form-item {
	margin-bottom: 20px;
}

.drawer-footer {
	text-align: right;
	padding-top: 10px;
	border-top: 1px solid #f0f0f0;
}
</style>
