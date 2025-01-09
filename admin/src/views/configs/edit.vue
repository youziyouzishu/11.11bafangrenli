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
                <el-form-item label="名称" prop="name">
    <el-input v-model="formData.name" clearable placeholder="请输入名称" />
</el-form-item>
                <el-form-item label="月数" prop="num">
    <el-input v-model="formData.num" clearable placeholder="请输入月数" />
</el-form-item>
                <el-form-item label="次数" prop="times">
    <el-input v-model="formData.times" clearable placeholder="请输入次数" />
</el-form-item>
                <el-form-item label="价格" prop="price">
    <el-input v-model="formData.price" clearable placeholder="请输入价格" />
</el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="consultEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiConsultAdd, apiConsultEdit, apiConsultDetail } from '@/api/consult'
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
    return mode.value == 'edit' ? '编辑沟通次数' : '新增沟通次数'
})

// 表单数据
const formData = reactive({
    id: '',
    name: '',
    num: '',
    times: '',
    price: '',
})


// 表单验证
const formRules = reactive<any>({
    name: [{
        required: true,
        message: '请输入名称',
        trigger: ['blur']
    }],
    num: [{
        required: true,
        message: '请输入月数',
        trigger: ['blur']
    }],
    times: [{
        required: true,
        message: '请输入次数',
        trigger: ['blur']
    }],
    price: [{
        required: true,
        message: '请输入价格',
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
    const data = await apiConsultDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiConsultEdit(data) 
        : await apiConsultAdd(data)
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
