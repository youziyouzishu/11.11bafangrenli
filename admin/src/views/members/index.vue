<template>
	<div>
		<el-tabs v-model="activeTab" class="tabs">
			<el-tab-pane label="全部" name="all"></el-tab-pane>
			<el-tab-pane label="招聘专员" name="recruiter"></el-tab-pane>
			<el-tab-pane label="驻场经理" name="manager"></el-tab-pane>
		</el-tabs>

		<div class="card-container">
			<el-alert type="error" class="mb-3" title="注意：每日可主动发起沟通30次。" :closable="false" show-icon></el-alert>
			<el-card class="box-card" v-for="item in pager.lists" :key="item.name">
				<div class="card-header">
					<span v-if="item.role" :class="['role-label', getRoleClass(item.role)]">{{ getRoleLabel(item.role) }}</span>
					<img :src="item.avatar" alt="" class="avatar" />
					<span>{{ item.psn_name }}</span>
				</div>
				<div class="card-body">
					<p class="description">
						{{ item.profile ?? '没有个人简介 ~' }}
					</p>
				</div>
				<div class="card-footer">
					<span>电话: {{ maskedPhone(item.psn_mobile) }}</span>
					<el-button size="mini" type="primary" class="contact-button" @click="openContact(item)">沟通</el-button>
				</div>
			</el-card>
			<footer-btns>
				<div class="flex mt-4 justify-end">
					<pagination v-model="pager" @change="getLists" />
				</div>
			</footer-btns>
		</div>
	</div>
</template>

<script lang="ts" setup>
import { ref, reactive, watch } from 'vue';
import { usePaging } from '@/hooks/usePaging';
import { apiMembers, apiToIm } from '@/api/member';

// 查询条件
const queryParams = reactive({
	role: '',
});

// 分页相关
const { pager, getLists } = usePaging({
	fetchFun: apiMembers,
	params: queryParams
});
getLists();

const searchQuery = ref('');
const activeTab = ref('all');

watch(activeTab, (newTab) => {
	if (newTab === 'all') {
		queryParams.role = '';
	} else if (newTab === 'recruiter') {
		queryParams.role = '2';
	} else if (newTab === 'manager') {
		queryParams.role = '3';
	}
	getLists();
});

function getRoleLabel(role: number) {
	if (role === 2) {
		return '招聘专员';
	} else if (role === 3) {
		return '驻场经理';
	}
	return '';
}

function getRoleClass(role: number) {
	if (role === 2) {
		return 'recruiter-label';
	} else if (role === 3) {
		return 'manager-label';
	}
	return '';
}

function maskedPhone(phone: string) {
	return phone.replace(/(\d{3})\d{4}(\d{4})/, '\$1****\$2');
}

const openContact = async (item) => {
	// 假设从 API 获取 URL
	const contactUrl = await toIm(item.id);
	console.log(contactUrl.url)
	if (contactUrl) {
		window.open(contactUrl.url, '_blank');
	}
}

const toIm = async (id: number) => {
	let ret = await apiToIm({ id: id })
	return ret
}

</script>

<style>
.search-input {
	margin-bottom: 20px;
	width: 300px;
}

.tabs {
	margin-bottom: 20px;
}

.card-container {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
	justify-content: flex-start;
}

.box-card {
	width: 300px;
	height: 180px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-sizing: border-box;
	padding: 2px;
	position: relative;
}

.card-header {
	display: flex;
	align-items: center;
	height: 50px;
	position: relative;
}

.role-label {
	color: white;
	padding: 5px;
	border-radius: 6px;
	position: absolute;
	top: -15px;
	right: -30px;
	font-size: 11px;
	transform: rotate(45deg);
	transform-origin: center;
	width: 60px;
	text-align: center;
}

.recruiter-label {
	background-color: rgb(125, 125, 225);
}

.manager-label {
	background-color: rgb(120, 158, 120);
}

.avatar {
	width: 40px;
	height: 40px;
	border-radius: 50%;
	margin-right: 10px;
}

.card-body {
	height: 40px;
	margin: 5px 0;
	overflow: hidden;
}

.description {
	overflow: hidden;
	text-overflow: ellipsis;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	white-space: normal;
}

.card-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	height: 40px;
}

.contact-button {
	margin-left: 10px;
}

@media (max-width: 768px) {
	.box-card {
		width: 100%;
	}
}
</style>
