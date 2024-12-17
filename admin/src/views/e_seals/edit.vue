<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="700px" @confirm="handleSubmit"
            @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="用户ID" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入用户ID" />
                </el-form-item>
                <el-form-item label="主体ID" prop="target_id">
                    <el-input v-model="formData.target_id" clearable placeholder="请输入主体ID" />
                </el-form-item>
                <el-form-item label="用户类型" prop="type">
                    <el-select class="flex-1" v-model="formData.type" clearable placeholder="请选择用户类型">
                        <el-option v-for="(item, index) in dictData.seal_type" :key="index" :label="item.name"
                            :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="印章名称" prop="seal_name">
                    <el-input v-model="formData.seal_name" clearable placeholder="请输入印章名称" />
                </el-form-item>
                <el-form-item label="印章模板样式" prop="seal_template_style">
                    <el-input v-model="formData.seal_template_style" clearable placeholder="请输入印章模板样式" />
                </el-form-item>
                <el-form-item label="印章透明度" prop="seal_opacity">
                    <el-input v-model="formData.seal_opacity" clearable placeholder="请输入印章透明度" />
                </el-form-item>
                <el-form-item label="印章颜色" prop="seal_color">
                    <el-input v-model="formData.seal_color" clearable placeholder="请输入印章颜色" />
                </el-form-item>
                <el-form-item label="旧样式编码" prop="seal_old_style">
                    <el-input v-model="formData.seal_old_style" clearable placeholder="请输入旧样式编码" />
                </el-form-item>
                <el-form-item label="印章尺寸" prop="seal_size">
                    <el-input v-model="formData.seal_size" clearable placeholder="请输入印章尺寸" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="eSealsEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiESealsAdd, apiESealsEdit, apiESealsDetail } from '@/api/e_seals'
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
    return mode.value == 'edit' ? '编辑印章信息表' : '新增印章信息表'
})

// 表单数据
const formData = reactive({
    id: '',
    user_id: '',
    target_id: '',
    type: '',
    seal_name: '',
    seal_template_style: '',
    seal_opacity: '',
    seal_color: '',
    seal_old_style: '',
    seal_size: '',
})


// 表单验证
const formRules = reactive<any>({
    user_id: [{
        required: true,
        message: '请输入用户ID',
        trigger: ['blur']
    }],
    type: [{
        required: true,
        message: '请选择用户类型',
        trigger: ['blur']
    }],
    seal_name: [{
        required: true,
        message: '请输入印章名称',
        trigger: ['blur']
    }],
    seal_template_style: [{
        required: true,
        message: '请输入印章模板样式',
        trigger: ['blur']
    }],
    seal_opacity: [{
        required: true,
        message: '请输入印章透明度',
        trigger: ['blur']
    }],
    seal_color: [{
        required: true,
        message: '请输入印章颜色',
        trigger: ['blur']
    }],
    seal_old_style: [{
        required: true,
        message: '请输入旧样式编码',
        trigger: ['blur']
    }],
    seal_size: [{
        required: true,
        message: '请输入印章尺寸（宽_高，例如40_40）',
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
    const data = await apiESealsDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiESealsEdit(data)
        : await apiESealsAdd(data)
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
