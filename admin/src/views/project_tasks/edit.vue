<template>
    <div class="edit-popup">
        <drawer ref="popupRef" :title="popupTitle" confirmButtonText="保存" :async="true" width="95%"
            @confirm="handleSubmit" @close="handleClose">
            <template #title>
                <div>
                    <span>{{ popupTitle }}</span>
                    <a-button type="text" icon="close" @click="handleClose">自定义关闭</a-button>
                </div>
            </template>

            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <div>

                    <el-steps direction="vertical" :active="active">
                        <el-step title="招聘需求">
                            <template #description>
                                <el-form-item label="名称" prop="project_name">
                                    <el-input :disabled="mode == 'edit'" v-model="formData.project_name"
                                        style="width:380px;" maxlength="20" show-word-limit clearable
                                        placeholder="请输入项目名称" />
                                </el-form-item>

                                <el-form-item label="岗位" prop="work_type">
                                    <el-select multiple :multiple-limit="4" style="width:300px;"
                                        v-model="formData.work_type" clearable placeholder="请选择类型">
                                        <el-option v-for="(item, index) in dictData.work_type" :key="index"
                                            :label="item.name" :value="item.value" />
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="工期" prop="work_time">
                                    <el-select multiple style="width:300px;" v-model="formData.work_time" clearable
                                        placeholder="请选择类型">
                                        <el-option v-for="(item, index) in dictData.work_time" :key="index"
                                            :label="item.name" :value="item.value" />
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="人数" prop="min_people">
                                    <el-input v-model="formData.min_people" clearable placeholder="最小人数"
                                        style="width:100px;margin-left:10px;margin-right:10px;" type="number" />
                                    <div>~</div>
                                    <el-input v-model="formData.max_people" clearable placeholder="最大人数"
                                        style="width:100px;margin-left:10px;margin-right:10px;" type="number" />
                                    <div> 人 </div>
                                </el-form-item>
                                <el-form-item label="薪资" prop="is_salary">
                                    <el-radio-group v-model="formData.is_salary">
                                        <el-radio :label="1">面议</el-radio>
                                        <el-radio :label="2">指定</el-radio>
                                    </el-radio-group>
                                    <el-input v-show="formData.is_salary == 2" v-model="formData.salary_range" clearable
                                        placeholder="起始薪资" style="width:100px;margin-left:10px;margin-right:10px;"
                                        type="number" />
                                    <div v-show="formData.is_salary == 2">~</div>
                                    <el-input v-show="formData.is_salary == 2" v-model="formData.salary_range_end"
                                        clearable placeholder="结束薪资"
                                        style="width:100px;margin-left:10px;margin-right:10px;" type="number" />
                                    <div v-show="formData.is_salary == 2"> K / 月 </div>
                                </el-form-item>
                                <el-form-item label="上岗城市" prop="up_city" style="width:400px;">
                                    <el-select v-model="formData.up_city" filterable placeholder="请选择上岗城市"
                                        style="width: 100%;">
                                        <el-option v-for="city in flattenedRegionData" :key="city.value"
                                            :label="city.label" :value="city.value" />
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="描述" prop="project_name">
                                    <el-card header="" shadow="none" class="!border-none">
                                        <editor v-model="formData.task_description"
                                            style="margin-left:-20px;margin-top:-15px;" height="400px" mode="simple"
                                            readonly="true" />
                                    </el-card>
                                </el-form-item>

                            </template>
                        </el-step>
                        <el-step title="项目设置">
                            <template #description>
                                <el-form-item label="单位" prop="recruitment_settlement_type">
                                    <div v-show="mode == 'edit'">
                                        <div :label="1" v-show="formData.recruitment_settlement_type == 1">{{
            formData.recruitment_settlement_type == 1 ?
                formData.recruitment_settlement_value : 0 }} 元/人</div>
                                        <div :label="2" v-show="formData.recruitment_settlement_type == 2">{{
            formData.recruitment_settlement_type === 2 ?
                formData.recruitment_settlement_value : 0 }} 元/班次</div>
                                        <div :label="3" v-show="formData.recruitment_settlement_type == 3">{{
            formData.recruitment_settlement_type === 3 ?
                formData.recruitment_settlement_value : 0 }} 元/工时</div>
                                    </div>
                                    <el-radio-group v-show="mode != 'edit'"
                                        v-model="formData.recruitment_settlement_type"
                                        @change="recruitment_settlement_change">
                                        <el-radio :label="1">{{
            formData.recruitment_settlement_type == 1 ?
                formData.recruitment_settlement_value : '' }} 元/人</el-radio>
                                        <el-radio :label="2">{{
            formData.recruitment_settlement_type == 2 ?
                formData.recruitment_settlement_value : '' }} 元/班次</el-radio>
                                        <el-radio :label="3">{{
            formData.recruitment_settlement_type == 3 ?
                formData.recruitment_settlement_value : '' }} 元/工时</el-radio>
                                    </el-radio-group>
                                </el-form-item>

                                <el-form-item label="" prop=" recruitment_settlement_type">
                                    <el-slider v-show="mode != 'edit'" v-model="formData.recruitment_settlement_value"
                                        :min="recruitment_settlement_range[0]" :max="recruitment_settlement_range[1]"
                                        :format-tooltip="formatTooltip" style="margin-left:20px;width:400px;">
                                    </el-slider>

                                </el-form-item>

                                <el-form-item label="提成" prop="site_settlement_type">
                                    <div :disabled="mode == 'edit'">
                                        <div label="1">{{ formData.site_settlement_value }} 元/人</div>
                                    </div>
                                    <el-slider v-show="mode != 'edit'" v-model="formData.site_settlement_value"
                                        :min="site_settlement_range[0]" :max="site_settlement_range[1]"
                                        :format-tooltip="formatTooltip" style="margin-left:20px;width:400px;">
                                    </el-slider>
                                </el-form-item>



                                <el-form-item label="定时发布" prop="recruitment_start_time" style="width:400px;">
                                    <el-date-picker :disabled-date="disabledDate" :disabled="mode == 'edit'"
                                        class="flex-1 !flex" v-model="formData.recruitment_start_time" clearable
                                        type="datetime" value-format="YYYY-MM-DD HH:mm:ss" placeholder="发布时间"
                                        wdith="400px;"></el-date-picker>
                                </el-form-item>
                                <el-form-item label="招聘城市" prop="project_name">
                                    <el-transfer v-model="formData.citys" :data="flattenedRegionData" filterable
                                        :titles="['可选城市', '已选城市']" :props="transferProps">
                                    </el-transfer>
                                </el-form-item>
                            </template>
                        </el-step>
                        <el-step title="资质审核">
                            <template #description>
                                <el-form-item label="用工合同" prop="cooperative_contract">
                                    <MaterialPicker v-model="formData.cooperative_contract" :limit="1" type="file" />
                                </el-form-item>
                            </template>

                        </el-step>


                        <!-- <el-step title="定向信息">
                            <template #description>
                                <el-form-item label="驻场经理" prop="site_managers">
                                    <el-input v-model="formData.site_managers" style="width:400px;" clearable
                                        placeholder="请输入驻场经理（可选多人）" />
                                </el-form-item>
                                <el-form-item label="评分定向" prop="recruitment_score_direction">
                                    <el-input v-model="formData.recruitment_score_direction" clearable
                                        placeholder="请输入招聘专员评分定向" style="width:400px;" />
                                </el-form-item>
                                <el-form-item label="地域定向" prop="regional_orientation">
                                    <el-input v-model="formData.regional_orientation" clearable placeholder="请输入地域定向"
                                        style="width:400px;" />
                                </el-form-item>
                                <el-form-item label="兴趣标签" prop="interest_tags">
                                    <el-input v-model="formData.interest_tags" clearable placeholder="请输入兴趣标签" />
                                </el-form-item>
</template>
                        </el-step> -->
                    </el-steps>
                </div>
            </el-form>
            <template #footer v-if="mode!='edit'">
                <div class="footer">
                    <div class="left-section">
                        <div class="payment-method">
                            <span class="icon">💳</span> 余额支付
                        </div>
                        <div class="balance">
                            <span class="balance-amount">{{ balance }}</span> 元 <el-button size="large"
                                v-show="balance=='0.00' && balance != null" style="font-size: 15px; padding: 2px 5px;"
                                @click="isDialogVisible = true" type="success" link>
                                充值</el-button>
                        </div>
                        <div class="payment-amount">
                            实付金额 <span class="amount">60.00</span> 元
                        </div>
                    </div>
                    <div class="right-section">

                        <elbutton class="next-step-btn" @click="handleSubmit">立即发布</elbutton>
                    </div>
                </div>
            </template>
        </drawer>
    </div>
    <PaymentDialog v-model:dialogVisible="isDialogVisible" />
</template>

<script lang="ts" setup name="projectTasksEdit">
import { useDictData } from '@/hooks/useDictOptions'
import type { FormInstance } from 'element-plus'
import Drawer from '@/components/drawer/index.vue'
import { apiProjectTasksAdd, apiProjectTasksEdit, apiProjectTasksDetail } from '@/api/project_tasks'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
import { time } from 'echarts/core'
import { regionData, codeToText } from 'element-china-area-data'
import PaymentDialog from '@/components/pay/pay.vue';
import MaterialPicker from '@/components/material/picker.vue'
import { getBalance } from '@/api/perms/admin'
defineProps({
    dictData: {
        type: Object as PropType<Record<string, any[]>>,
        default: () => ({})
    }
})
// 控制 PaymentDialog 显示状态
const isDialogVisible = ref(false);
const balance = ref(null);
const emit = defineEmits(['success', 'close', 'confirm', 'cancel'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Drawer>>()
const mode = ref('add')
const active = ref(3)
const recruitment_settlement_range = ref([500, 800])
const site_settlement_range = ref([2, 10])

// 弹窗标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑项目 ( ' + formData.project_name + ' )' : '发布项目'
})

// 获取字典数据
const { dictData } = useDictData('education,experience,welfare,work_type,work_time')




// 配置 el-transfer 组件的 props
const transferProps = {
    key: 'value',
    label: 'label'
};
// 计算属性，用于过滤三级数据
// 将嵌套的三级数据转为扁平化的二级数据
const flattenedRegionData = computed(() => {
    const flattenData = [];

    regionData.forEach(province => {
        province.children.forEach(city => {
            flattenData.push({
                ...city,
                label: `${province.label}/${city.label}`
            });
        });
    });

    return flattenData;
});
//<blockquote>自定义模版</blockquote><ul><li>公司: {{公司名称}}</li><li>职位: {{职位}}</li><li>职责: {{职责}}</li><li><span style="color: rgb(206, 145, 120);">招聘提成: {{提成}}</span></li><li><span style="color: rgb(206, 145, 120);">招聘提成: {{提成}}</span></li><li>薪资范围:{{薪资}}</li></ul><p><br></p>
// 表单数据
const formData = reactive({
    id: '',
    project_name: '',
    task_description: '',
    creator: '',
    nickname: '',
    site_managers: '',
    recruitment_specialists: '',
    recruitment_start_time: '',
    recruitment_score_direction: '',
    regional_orientation: '',
    recruitment_settlement_type: 1,
    recruitment_settlement_value: 0,
    site_settlement_type: '1',
    site_settlement_value: 3,
    is_salary: 1,
    attr_welfare: '',
    salary_range: '',
    salary_range_end: '',
    interest_tags: '',
    project_score: 5,
    work_time: '',
    work_type: '',
    min_people: 1,
    max_people: 10,
    citys: [],
    up_city: undefined,
    cooperative_contract: undefined,
})


// 表单验证
const formRules = reactive<any>({
    project_name: [{
        required: true,
        message: '请输入项目名称',
        trigger: ['blur']
    }],
    task_description: [{
        required: true,
        message: '请输入任务描诉',
        trigger: ['blur']
    }],

    recruitment_start_time: [{
        required: true,
        message: '请选择发布时间',
        trigger: ['blur']
    }],
    recruitment_settlement_type: [{
        required: true,
        message: '请输入招聘结算类型',
        trigger: ['blur']
    }],
    site_settlement_type: [{
        required: true,
        message: '请输入驻场结算类型',
        trigger: ['blur']
    }],
    attr_welfare: [{
        required: true,
        message: '请选择福利类型',
        trigger: ['blur']
    }],
    up_city: [{
        required: true,
        message: '请选择上岗城市',
        trigger: ['blur']
    }],
    work_type: [{
        required: true,
        message: '请选择岗位',
        trigger: ['blur']
    }],
    work_time: [{
        required: true,
        message: '请选择岗位',
        trigger: ['blur']
    }],
    min_people: [{
        required: true,
        message: '请选人数范围',
        trigger: ['blur']
    }],
    is_salary: [{
        required: true,
        message: '请选择岗位',
        trigger: ['blur']
    }],
    cooperative_contract: [{
        required: true,
        message: '与用工方的合作合同，必填。用于资质审核',
        trigger: ['blur']
    }],
})

const disabledDate = (time: Date): boolean => {
    let today = new Date();
    today.setHours(0, 0, 0, 0);
    return time.getTime() < today.getTime(); // 更直观地表明需要比较的是两个时间戳
}

// 获取详情
const setFormData = async (data: Record<any, any>) => {
    for (const key in formData) {
        if (data[key] != null && data[key] != undefined) {
            //@ts-ignore
            formData[key] = data[key]
        }
    }


}

const getDetail = async (row: Record<string, any>) => {
    const data = await apiProjectTasksDetail({
        id: row.id
    })
    setFormData(data)
}

const getBalanceApi = async () => {
    const data = await getBalance()
    balance.value = data.user_money
    console.log(data)
}
onMounted(() => {
    getBalanceApi();
});


const recruitment_settlement_change = (val: any) => {
    console.log(val)
    if (val == '1') {
        recruitment_settlement_range.value = [500, 800]
    } else if (val == '2') {
        recruitment_settlement_range.value = [10, 15]
    } else if (val == '3') {
        recruitment_settlement_range.value = [2, 10]
    }
    formData.recruitment_settlement_value = recruitment_settlement_range.value[0]
}
// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiProjectTasksEdit(data)
        : await apiProjectTasksAdd(data)
    popupRef.value?.close()
    emit('success')
}

//打开弹窗
const open = (type = 'add') => {
    mode.value = type
    popupRef.value?.open()
}

// 关闭回调
const handleClose = () => {
    emit('close')
}

const formatTooltip = (val: any): string => {
    return `${val} 元`; // 在数值后面加上单位“%”
}

defineExpose({
    open,
    setFormData,
    getDetail,
})
</script>

<style>
.el-select .el-input {
    width: 380px;
}

.input-with-select .el-input-group__prepend {
    background-color: #fff;
}

.footer {
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 10px 20px;
    background-color: #fff;
    height: 60px;
    /* 固定高度 */
}

.left-section {
    display: flex;
    align-items: center;
}

.payment-method,
.balance {
    display: flex;
    align-items: center;
    margin-right: 15px;
    font-size: 18px;
    color: #333;
}

.payment-method .icon,
.balance .icon {
    margin-right: 5px;
}

.payment-amount {
    font-size: 18px;
    color: #333;
}

.payment-amount .amount {
    font-size: 22px;
    color: #ff6600;
    /* 亮色 */
    font-weight: bold;
}

.balance .balance-amount {
    font-size: 25px;
    color: #ff6600;
    font-weight: bold;
    margin: 0 5px;
}

.right-section {
    display: flex;
    align-items: center;
}

.next-step-btn {
    padding: 8px 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.next-step-btn:hover {
    background-color: #0056b3;
}
</style>