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

                <el-form-item label="业务员名称" prop="name">
    <el-input v-model="formData.name" clearable placeholder="请输入业务员名称" />
</el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="staffEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiStaffAdd, apiStaffEdit, apiStaffDetail } from '@/api/staff'
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
    return mode.value == 'edit' ? '编辑业务公司员工' : '新增业务公司员工'
})

// 表单数据
const formData = reactive({
    id: '',
    admin_id: '',
    name: '',
    invitecode: '',
})


// 表单验证
const formRules = reactive<any>({
    admin_id: [{
        required: true,
        message: '请输入所属业务公司',
        trigger: ['blur']
    }],
    name: [{
        required: true,
        message: '请输入业务员名称',
        trigger: ['blur']
    }],
    invitecode: [{
        required: true,
        message: '请输入业务员邀请码',
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
    const data = await apiStaffDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiStaffEdit(data) 
        : await apiStaffAdd(data)
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
