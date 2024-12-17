<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="合同名称" prop="file_name">
                    <el-input v-model="formData.file_name" clearable placeholder="请输入合同名称" />
                </el-form-item>
                <el-form-item label="原合同文件" prop="file_name">
                    <upload type="doc" :show-progress="true" @change="onChange" @success="onSuccess" @error="onError">
                        <el-button type="primary">上传合同</el-button>
                    </upload>
                </el-form-item>
                <!-- <el-form-item label="模版ID" prop="doc_template_id">
                    <el-input v-model="formData.doc_template_id" clearable placeholder="上传文件后自动生成模版ID" />
                </el-form-item> -->


                <el-form-item label="显示状态" prop="show_status">
                    <el-select class="flex-1" v-model="formData.show_status" clearable placeholder="请选择显示状态">
                        <el-option v-for="(item, index) in dictData.show_status" :key="index" :label="item.name"
                            :value="parseInt(item.value)" />
                    </el-select>
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="eContractTemplateEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiEContractTemplateAdd, apiEContractTemplateEdit, apiEContractTemplateDetail } from '@/api/e_contract_template'
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
    return mode.value == 'edit' ? '编辑合同模版' : '新增合同模版'
})

// 表单数据
const formData = reactive({
    id: '',
    file_name: '',
    file_path: '',
    doc_template_id: '',
    show_status: 1,
})


// 表单验证
const formRules = reactive<any>({

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
    const data = await apiEContractTemplateDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiEContractTemplateEdit(data)
        : await apiEContractTemplateAdd(data)
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

const onChange = (file: any) => {
    console.log('上传文件的状态发生改变', file)
}

const onSuccess = (file: any) => {
    formData.file_name = file.data.name
    formData.file_path = file.data.url
    console.log('上传文件成功', file)
}

const onError = (file: any) => {
    console.log('上传文件失败', file)
}

defineExpose({
    open,
    setFormData,
    getDetail
})
</script>
