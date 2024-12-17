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
                <el-form-item label="审计ID" prop="audit_id">
                    <el-input v-model="formData.audit_id" clearable placeholder="请输入审计ID" />
                </el-form-item>
                <el-form-item label="" prop="creator">
                    <el-input v-model="formData.creator" clearable placeholder="请输入" />
                </el-form-item>
                <el-form-item label="项目id" prop="project_id">
                    <el-input v-model="formData.project_id" clearable placeholder="请输入项目id" />
                </el-form-item>
                <el-form-item label="用户id" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入用户id" />
                </el-form-item>
                <el-form-item label="招聘专员ID" prop="recruit_user_id">
                    <el-input v-model="formData.recruit_user_id" clearable placeholder="请输入招聘专员ID" />
                </el-form-item>
                <el-form-item label="驻场经理ID" prop="onsite_user_id">
                    <el-input v-model="formData.onsite_user_id" clearable placeholder="请输入驻场经理ID" />
                </el-form-item>
                <el-form-item label="任务类型" prop="type">
                    <el-input v-model="formData.type" clearable placeholder="请输入任务类型" />
                </el-form-item>
                <el-form-item label="任务状态" prop="status">
                    <el-input v-model="formData.status" clearable placeholder="请输入任务状态" />
                </el-form-item>
                <el-form-item label="工作状态 - 1 待上岗" prop="work_status">
                    <el-input v-model="formData.work_status" clearable placeholder="请输入工作状态 - 1 待上岗" />
                </el-form-item>
                <el-form-item label="备注" prop="remarks">
                    <el-input v-model="formData.remarks" clearable placeholder="请输入备注" />
                </el-form-item>
                <el-form-item label="合同ID" prop="sign_flow_id">
                    <el-input v-model="formData.sign_flow_id" clearable placeholder="请输入合同ID" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="projectTasksAuditLogsEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiProjectTasksAuditLogsAdd, apiProjectTasksAuditLogsEdit, apiProjectTasksAuditLogsDetail } from '@/api/project_tasks_audit_logs'
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
    return mode.value == 'edit' ? '编辑项目任务审计表' : '新增项目任务审计表'
})

// 表单数据
const formData = reactive({
    id: '',
    audit_id: '',
    creator: '',
    project_id: '',
    user_id: '',
    recruit_user_id: '',
    onsite_user_id: '',
    type: '',
    status: '',
    work_status: '',
    remarks: '',
    sign_flow_id: '',
})


// 表单验证
const formRules = reactive<any>({
    audit_id: [{
        required: true,
        message: '请输入审计ID',
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
        message: '请输入工作状态 - 1 待上岗',
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
    const data = await apiProjectTasksAuditLogsDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiProjectTasksAuditLogsEdit(data) 
        : await apiProjectTasksAuditLogsAdd(data)
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
