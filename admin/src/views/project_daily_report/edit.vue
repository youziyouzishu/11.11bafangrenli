<template>
    <div class="edit-popup">
        <popup
            ref="popupRef"
            :title="popupTitle"
            :async="true"
            width="550px"
            @confirm="handleSubmit"
            @close="handleClose"
        >
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="projectDailyReportEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiProjectDailyReportAdd, apiProjectDailyReportEdit, apiProjectDailyReportDetail } from '@/api/project_daily_report'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
defineProps({
    dictData: {
        type: Object as PropType<Record<string, any[]>>,
        default: () => ({})
    }
})
const emit = defineEmits(['success', 'close'])
const formRef = shallowRef<FormInstance>()
const popupRef = shallowRef<InstanceType<typeof Popup>>()
const mode = ref('add')


// 弹窗标题
const popupTitle = computed(() => {
    return mode.value == 'edit' ? '编辑项目日报' : '新增项目日报'
})

// 表单数据
const formData = reactive({
    id: '',
    
})


// 表单验证
const formRules = reactive<any>({
    task_audit_id: [{
        required: true,
        message: '请输入任务审计ID',
        trigger: ['blur']
    }],
    uid: [{
        required: true,
        message: '请选择人力公司ID',
        trigger: ['blur']
    }],
    report_date: [{
        required: true,
        message: '请选择报告日期',
        trigger: ['blur']
    }],
    project_id: [{
        required: true,
        message: '请输入项目ID',
        trigger: ['blur']
    }],
    user_id: [{
        required: true,
        message: '请输入用户ID',
        trigger: ['blur']
    }],
    recruit_user_id: [{
        required: true,
        message: '请输入招聘专员ID',
        trigger: ['blur']
    }],
    onsite_user_id: [{
        required: true,
        message: '请输入驻场经理ID',
        trigger: ['blur']
    }],
    work_status: [{
        required: true,
        message: '请输入工作状态',
        trigger: ['blur']
    }],
    daily_salary: [{
        required: true,
        message: '请输入日薪',
        trigger: ['blur']
    }]
})


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
    const data = await apiProjectDailyReportDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiProjectDailyReportEdit(data) 
        : await apiProjectDailyReportAdd(data)
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



defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
