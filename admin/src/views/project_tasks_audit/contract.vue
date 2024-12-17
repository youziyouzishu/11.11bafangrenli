<template>
	<div class="contract-popup">
		<popup ref="popupRef" :title="popupTitle" :async="true" width="720px" @confirm="handleSubmit" @close="handleClose">
			<div v-if="mode == 2">
				<el-form ref="formRef" :model="formData" label-width="130px" :rules="formRules">
					<el-form-item label="招聘账号服务费">
						<el-radio-group v-model="formData.accountServiceFeeType">
							<el-radio label="免费">免费</el-radio>
							<el-radio label="元/月">元/月</el-radio>
							<el-radio label="元/天">元/天</el-radio>
						</el-radio-group>
						<el-input type="number" class="mt-2" v-show="formData.accountServiceFeeType !== '免费'"
							v-model="formData.accountServiceFeeAmount" placeholder="请输入具体金额"></el-input>
					</el-form-item>

					<el-form-item label="费用标准">
						<el-radio-group v-model="formData.feeStandardType">
							<el-radio label="元/人">元/人</el-radio>
							<el-radio label="元/班次">元/班次</el-radio>
							<el-radio label="元/小时">元/小时</el-radio>
						</el-radio-group>
						<el-input type="number" class="mt-2" v-model="formData.feeStandardAmount" placeholder="请输入具体金额"></el-input>
					</el-form-item>

					<!-- 新增其他表单项 -->
					<el-form-item label="支付日期">
						<el-input type="number" v-model="formData.paymentDate" placeholder="请输入支付日"></el-input>
					</el-form-item>
					<el-form-item label="违约金">
						<el-input type="number" v-model="formData.penaltyFee" placeholder="请输入违约金"></el-input>
					</el-form-item>
				</el-form>
				<el-alert type="error" title="以上内容会替换合同中的信息，请仔细填写，签署后合同生效" :closable="false" show-icon></el-alert>
				<div class="contract">
					<p>1、可根据招聘方需求为其提供招聘账号，可以收费，需要提前说明收费标准
						<span style="color:red;">{{ formData.accountServiceFeeAmount }} {{ formData.accountServiceFeeType
							}}</span> ，如没有提前说明则视为免费为招聘方提供招聘账号。
					</p>
					<br />
					<p>2、费用标准：按乙方招聘最终入职人数 <span style="color:red;">{{ formData.feeStandardAmount }} {{ formData.feeStandardType }}
						</span>的标准支付乙方的招聘费用。</p>
					<br />
					<p>3、支付方式：甲方根据项目按月支付乙方招聘费用（不含税），每月 <span style="color:red;">{{ formData.paymentDate }}</span> 日支付上个月费用。</p>
					<br />
					<p>4、乙方不得虚假，夸大宣传，否则无论此人是否为有效产生费用人员，甲方都有权不予结算，并承担违约责任
						<span style="color:red;">{{ formData.penaltyFee }}</span> 元/人。
					</p>
				</div>
			</div>
			<div v-if="mode == 3">
				<el-form ref="formRef2" :model="formData" label-width="330px" :rules="form2Rules">
					<el-form-item label="服务地址" prop="serverAddress">
						<el-input v-model="formData.serverAddress" placeholder="请输入服务地址"></el-input>
					</el-form-item>
					<el-form-item label="服务开始时间" prop="serverSdate">
						<el-date-picker class="flex-1 !flex" v-model="formData.serverSdate" clearable type="date"
							value-format="YYYY-MM-DD" placeholder="发布时间" wdith="400px;"></el-date-picker>
					</el-form-item>
					<el-form-item label="服务结束时间" prop="serverEdate">
						<el-date-picker class="flex-1 !flex" v-model="formData.serverEdate" clearable type="date"
							value-format="YYYY-MM-DD" placeholder="发布时间" wdith="400px;"></el-date-picker>
					</el-form-item>
					<el-form-item label="费用标准" prop="feeStandardAmount">
						<el-radio-group v-model="formData.feeStandardType">
							<el-radio label="元/人">元/人</el-radio>
							<el-radio label="元/班次">元/班次</el-radio>
							<el-radio label="元/小时">元/小时</el-radio>
						</el-radio-group>
						<el-input type="number" class="mt-2" v-model.number="formData.feeStandardAmount"
							placeholder="请输入具体金额"></el-input>
					</el-form-item>
					<el-form-item label="支付日期" prop="paymentDate">
						<el-input type="number" v-model.number="formData.paymentDate" placeholder="请输入支付日"></el-input>
					</el-form-item>
					<el-form-item label="终止产生费用百分比" label-width="330px" prop="ratio">
						<el-input type="number" v-model.number="formData.ratio" placeholder="请输入具体比例"></el-input>
					</el-form-item>
					<!-- 新增其他表单项 -->
					<el-form-item label="漏接一人核减" label-width="330px" prop="cost3">
						<el-input type="number" v-model.number="formData.cost3" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="漏报一人核减" prop="cost4">
						<el-input type="number" v-model.number="formData.cost4" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="因乙方自身原因造成纠纷核减" prop="cost5">
						<el-input type="number" v-model.number="formData.cost5" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="漏做日报核减" prop="cost6">
						<el-input type="number" v-model.number="formData.cost6" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="漏做周报核减" prop="cost7">
						<el-input type="number" v-model.number="formData.cost7" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="漏做月报以及此合同第四款第10、11条，核减" prop="cost8">
						<el-input type="number" v-model.number="formData.cost8" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="虚假招聘信息赔偿" prop="cost13">
						<el-input type="number" v-model.number="formData.cost13" placeholder="请输入金额"></el-input>
					</el-form-item>
					<el-form-item label="已方主动终止合同违约金" prop="cost14">
						<el-input type="number" v-model.number="formData.cost14" laceholder="请输入金额"></el-input>
					</el-form-item>
				</el-form>
				<el-alert type="error" title="以上内容会替换合同中的信息，请仔细填写，签署后合同生效" :closable="false" show-icon></el-alert>
				<!-- <div class="contract">
					<p>2. 驻场地点：
						<span style="color:red;">{{ formData.accountServiceFeeAmount }} {{ formData.accountServiceFeeType
							}}</span> 。
					</p>
					<p> 3. 服务期限：自 至 </p>
					<br />
					<p>2、费用标准：按乙方招聘最终入职人数 <span style="color:red;">{{ formData.feeStandardAmount }} {{ formData.feeStandardType }}
						</span>的标准支付乙方的招聘费用。</p>
					<br />
					<p>3、支付方式：甲方根据项目按月支付乙方招聘费用（不含税），每月 <span style="color:red;">{{ formData.paymentDate }}</span> 日支付上个月费用。</p>
					<br />
					<p>4、乙方不得虚假，夸大宣传，否则无论此人是否为有效产生费用人员，甲方都有权不予结算，并承担违约责任
						<span style="color:red;">{{ formData.penaltyFee }}</span> 元/人。
					</p>
				</div> -->
			</div>
			<div v-if="mode == 4">
				<el-form ref="formRef3" :model="formData" label-width="130px" :rules="form3Rules">
					<el-form-item label="驻场经理" prop="onsite_id">
						<!-- 状态筛选器 -->
						<el-select v-model="formData.onsite_id" class="w-[140px]" placeholder="请选择驻场经理" @change="filterTable"
							@focus="fetchNamesOptions(formData)">
							<el-option v-for="item in namesOptions" :key="item.user_id" :label="item.psn_name" :value="item.user_id">
							</el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="工作岗位" prop="workPosition">
						<el-input v-model="formData.workPosition" placeholder="请输入具体工作岗位"></el-input>
					</el-form-item>
					<el-form-item label="合同开始时间" prop="workSdate">
						<el-date-picker class="flex-1 !flex" v-model="formData.workSdate" clearable type="date"
							value-format="YYYY-MM-DD" placeholder="发布时间" wdith="400px;"></el-date-picker>
					</el-form-item>
					<el-form-item label="合同结束时间" prop="workEdate">
						<el-date-picker class="flex-1 !flex" v-model="formData.workEdate" clearable type="date"
							value-format="YYYY-MM-DD" placeholder="发布时间" wdith="400px;"></el-date-picker>
					</el-form-item>
					<el-form-item label="本合同期限/月" prop="workMonth">
						<el-input type="number" class="mt-2" v-model.number="formData.workMonth" placeholder="请输入具体多少个月"></el-input>
					</el-form-item>
					<el-form-item label="工资金额" prop="payCost">
						<el-input type="number" class="mt-2" v-model.number="formData.payCost" placeholder="请输入具体金额"></el-input>
					</el-form-item>
					<el-form-item label="支付日期" prop="paymentDate">
						<el-input type="number" v-model.number="formData.paymentDate" placeholder="请输入支付日"></el-input>
					</el-form-item>
				</el-form>
				<el-alert type="error" title="以上内容会替换合同中的信息，请仔细填写，签署后合同生效" :closable="false" show-icon></el-alert>
			</div>
		</popup>
	</div>
</template>

<script lang="ts" setup name="projectTasksAuditEdit">
import { reactive, ref, shallowRef, computed } from 'vue';
import type { FormInstance } from 'element-plus';
import Popup from '@/components/popup/index.vue';
import { apiProjectTasksAuditAdd, apiProjectTasksAuditEdit, apiProjectTasksAudited, apiGetNamesByType } from '@/api/project_tasks_audit';
import type { PropType } from 'vue';

defineProps({
	dictData: {
		type: Object as PropType<Record<string, any[]>>,
		default: () => ({})
	}
});

const emit = defineEmits(['success', 'close']);
const formRef = shallowRef<FormInstance>();
const formRef2 = shallowRef<FormInstance>();
const formRef3 = shallowRef<FormInstance>();
const popupRef = shallowRef<InstanceType<typeof Popup>>();
const mode = ref('2');
const namesOptions = ref([])
// 弹窗标题
const popupTitle = computed(() => {
	return mode.value == '2' ? '招聘方合同' : '驻场方合同';
});


// 表单数据
const formData = reactive({
	id: 0,
	paymentDate: '',
	penaltyFee: '',
	feeStandardType: '元/人', // 增加类型字段
	feeStandardAmount: '', // 增加金额字段
	accountServiceFeeType: '免费', // 增加类型字段
	accountServiceFeeAmount: '', // 增加金额字段
	serverAddress: '', // 增加金额字段
	serverSdate: '', // 增加金额字段
	serverEdate: '', // 增加金额字段
	ratio: 0, // 增加金额字段
	cost3: 0, // 增加金额字段
	cost4: 0, // 增加金额字段
	cost5: 0, // 增加金额字段
	cost6: 0, // 增加金额字段
	cost7: 0, // 增加金额字段
	cost8: 0, // 增加金额字段
	cost13: 0, // 增加金额字段
	cost14: 0, // 增加金额字段
	status: 1,
	workSdate: '',
	workEdate: '',
	workMonth: '',
	workPosition: '',
	payCost: 0,
	project_id: 0,
	type: 0,
	onsite_id: undefined,
});

// 表单验证
const formRules = reactive<any>({
	paymentDate: [{
		required: true,
		message: '请输入支付日期',

		trigger: ['blur']
	}, { type: 'number', min: 1, max: 28, message: '数字必须在 1 到 28 号之前的数字', trigger: 'blur' },],
	penaltyFee: [{
		required: true,
		message: '请输入违约金',
		trigger: ['blur']
	}],
	accountServiceFeeAmount: [{
		required: false,
		message: '请输入招聘账号服务费具体金额',
		trigger: ['blur']
	}],
	feeStandardAmount: [{
		required: true,
		message: '请输入结算费用标准具体金额',
		trigger: ['blur']
	}]
});

const form2Rules = reactive<any>({
	paymentDate: [{
		required: true,
		message: '请输入支付日期',
		trigger: ['blur']
	}, { type: 'number', min: 1, max: 28, message: '数字必须在 1 到 28 号之前的数字', trigger: 'blur' },],

	feeStandardAmount: [{
		required: true,
		message: '请输入结算费用标准具体金额',
		trigger: ['blur']
	}],
	serverAddress: [{
		required: true,
		message: '请输入服务地址',
		trigger: ['blur']
	}],
	serverSdate: [{
		required: true,
		message: '请输入合同开始日期',
		trigger: ['blur']
	}],
	serverEdate: [{
		required: true,
		message: '请输入合同结束日期',
		trigger: ['blur']
	}],
	cost3: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost4: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost5: [
		{ type: 'number', min: 1, max: 1000, message: '数字必须在 1 到 1000 之间', trigger: 'blur', required: true },
	],
	cost6: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost7: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost8: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost13: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	cost14: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
	ratio: [
		{ type: 'number', min: 1, max: 100, message: '数字必须在 1 到 100 之间', trigger: 'blur', required: true },
	],
});


const form3Rules = reactive<any>({

	onsite_id: [{
		required: true,
		message: '请输选择驻场经理',
		trigger: ['blur']
	}],
	paymentDate: [{
		required: true,
		message: '请输入支付日期',
		trigger: ['blur']
	}, { type: 'number', min: 1, max: 28, message: '数字必须在 1 到 28 号之前的数字', trigger: 'blur' },],
	workSdate: [{
		required: true,
		message: '请输入合同开始日期',
		trigger: ['blur']
	}],
	workEdate: [{
		required: true,
		message: '请输入合同结束日期',
		trigger: ['blur']
	}],
	workPosition: [{
		required: true,
		message: '请输入工作岗位',
		trigger: ['blur']
	}],

	workMonth: [
		{ type: 'number', min: 1, max: 300, message: '数字必须在 1 到 300 之间', trigger: 'blur', required: true },
	],
	payCost: [
		{ type: 'number', min: 500, max: 50000, message: '数字必须在 500 到 50000 之间', trigger: 'blur', required: true },
	],
});
// 获取详情
const setFormData = async (data: Record<any, any>) => {
	for (const key in formData) {
		if (data[key] != null && data[key] != undefined) {
			formData[key] = data[key];
		}
	}
};

const fetchNamesOptions = async (row: Record<string, any>) => {
	namesOptions.value = await apiGetNamesByType({
		project_id: row.project_id,
		type: 3
	});
};

const getDetail = async (row: Record<string, any>) => {
	const data = await apiProjectTasksAuditDetail({
		id: row.id
	});
	setFormData(data);
};

// 提交按钮
const handleSubmit = async () => {

	// 根据当前 mode 值选择正确的表单实例
	var formRefToValidate;

	switch (mode.value) {
		case '2':
			formRefToValidate = formRules;
			break;
		case '3':
			formRefToValidate = form2Rules;
			break;
		case '4':
			formRefToValidate = form3Rules;
			break;
	}

	// 执行验证
	try {
		await formRefToValidate?.validate();
		// 如果验证通过，执行提交逻辑

		// ...
	} catch (error) {
		// 验证失败，处理错误
		console.error('表单验证失败:', error);
		return
	}
	const data = { ...formData };
	const ret = mode.value == '2'
		? await apiProjectTasksAudited(data)
		: await apiProjectTasksAudited(data);
	popupRef.value?.close();
	emit('success');
	showConfirm("签署合同需跳转到e签宝", ret.data.url)


};

// 打开弹窗
const open = (type = '2') => {
	mode.value = type;
	popupRef.value?.open();
};

// 关闭回调
const handleClose = () => {
	emit('close');
};

defineExpose({
	open,
	setFormData,
});

const showConfirm = (content: string, link: string) => {
	ElMessageBox.confirm(
		content,
		'提示',
		{
			confirmButtonText: '确定',
			cancelButtonText: '取消',
			type: 'warning',
		}
	).then(() => {
		// 跳转到外部链接
		const externalUrl = link; // 替换为你的外部链接
		window.open(externalUrl, '_blank'); // 在新标签页中打开外部链接
	}).catch(() => {
		// 用户点击取消，不做任何处理
	});
};
</script>

<style scoped>
.contract {
	margin-top: 20px;
}
</style>