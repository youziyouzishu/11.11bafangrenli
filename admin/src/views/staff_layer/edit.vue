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
                <el-form-item label="员工ID" prop="staff_id">
    <el-input v-model="formData.staff_id" clearable placeholder="请输入员工ID" />
</el-form-item>
                <el-form-item label="管理员ID" prop="admin_id">
    <el-input v-model="formData.admin_id" clearable placeholder="请输入管理员ID" />
</el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="staffLayerEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiStaffLayerAdd, apiStaffLayerEdit, apiStaffLayerDetail } from '@/api/staff_layer'
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
    return mode.value == 'edit' ? '编辑员工邀请记录' : '新增员工邀请记录'
})

// 表单数据
const formData = reactive({
    id: '',
    staff_id: '',
    admin_id: '',
})


// 表单验证
const formRules = reactive<any>({
    staff_id: [{
        required: true,
        message: '请输入员工ID',
        trigger: ['blur']
    }],
    admin_id: [{
        required: true,
        message: '请输入管理员ID',
        trigger: ['blur']
    }],
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
    const data = await apiStaffLayerDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiStaffLayerEdit(data) 
        : await apiStaffLayerAdd(data)
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
