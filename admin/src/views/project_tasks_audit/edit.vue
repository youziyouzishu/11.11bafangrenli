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
                <el-form-item label="项目ID" prop="project_id">
                    <el-input v-model="formData.project_id" clearable placeholder="请输入项目ID" />
                </el-form-item>
                <el-form-item label="用户ID" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入用户ID" />
                </el-form-item>
                <el-form-item label="类型(1=职员,2=招聘经理,3=驻场经理)" prop="type">
                    <el-input v-model="formData.type" clearable placeholder="请输入类型(1=职员,2=招聘经理,3=驻场经理)" />
                </el-form-item>
                <el-form-item label="状态(1=待审核,2=通过,3=拒绝)" prop="status">
                    <el-input v-model="formData.status" clearable placeholder="请输入状态(1=待审核,2=通过,3=拒绝)" />
                </el-form-item>
                <el-form-item label="拒绝说明" prop="remarks">
                    <el-input v-model="formData.remarks" clearable placeholder="请输入拒绝说明" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="projectTasksAuditEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiProjectTasksAuditAdd, apiProjectTasksAuditEdit, apiProjectTasksAuditDetail } from '@/api/project_tasks_audit'
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
    return mode.value == 'edit' ? '编辑招聘任务审核' : '新增招聘任务审核'
})

// 表单数据
const formData = reactive({
    id: '',
    project_id: '',
    user_id: '',
    type: '',
    status: '',
    remarks: '',
})


// 表单验证
const formRules = reactive<any>({
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
    type: [{
        required: true,
        message: '请输入类型(1=职员,2=招聘经理,3=驻场经理)',
        trigger: ['blur']
    }],
    status: [{
        required: true,
        message: '请输入状态(1=待审核,2=通过,3=拒绝)',
        trigger: ['blur']
    }],
    remarks: [{
        required: true,
        message: '请输入拒绝说明',
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
    const data = await apiProjectTasksAuditDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiProjectTasksAuditEdit(data) 
        : await apiProjectTasksAuditAdd(data)
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
