<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="130px" :rules="formRules">
                <el-form-item label="分享KEY" prop="unikey">
                    <el-input v-model="formData.unikey" clearable placeholder="请输入分享KEY" />
                </el-form-item>
                <el-form-item label="项目审核ID" prop="project_audit_id">
                    <el-input v-model="formData.project_audit_id" clearable placeholder="请输入项目审核ID" />
                </el-form-item>
                <el-form-item label="ShareUserID" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入share用户ID" />
                </el-form-item>
                <el-form-item label="审核类型" prop="type">
                    <el-input v-model="formData.type" clearable placeholder="请输入审核类型" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="projectTasksAuditShareEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiProjectTasksAuditShareAdd, apiProjectTasksAuditShareEdit, apiProjectTasksAuditShareDetail } from '@/api/project_tasks_audit_share'
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
    return mode.value == 'edit' ? '编辑邀请日志' : '新增邀请日志'
})

// 表单数据
const formData = reactive({
    id: '',
    unikey: '',
    project_audit_id: '',
    user_id: '',
    type: '',
})


// 表单验证
const formRules = reactive<any>({
    unikey: [{
        required: true,
        message: '请输入分享KEY',
        trigger: ['blur']
    }],
    project_audit_id: [{
        required: true,
        message: '请输入项目审核ID',
        trigger: ['blur']
    }],
    user_id: [{
        required: true,
        message: '请输入share用户ID',
        trigger: ['blur']
    }],
    type: [{
        required: true,
        message: '请输入审核类型【ZHAOPING】',
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
    const data = await apiProjectTasksAuditShareDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiProjectTasksAuditShareEdit(data)
        : await apiProjectTasksAuditShareAdd(data)
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
