<template>
    <div class="edit-popup">
        <popup ref="popupRef" :title="popupTitle" :async="true" width="550px" @confirm="handleSubmit" @close="handleClose">
            <el-form ref="formRef" :model="formData" label-width="90px" :rules="formRules">
                <el-form-item label="项目名称" prop="project_name">
                    <el-input v-model="formData.project_name" clearable placeholder="" :disabled="true" />
                </el-form-item>
                <el-form-item label="评价人" prop="reviewer_name">
                    {{ formData.reviewer_name }}
                </el-form-item>
                <el-form-item label="评价类型" prop="reviewer_type">
                    {{ ClientRoleMap[formData.reviewer_type] }}
                </el-form-item>
                <el-form-item label="被评价人" prop="target_id">
                    {{ formData.target_name }}
                </el-form-item>
                <el-form-item label="目标类型" prop="target_type">
                    {{ ClientRoleMap[formData.target_type] }}
                </el-form-item>

                <el-form-item label="评分" prop="score">
                    <el-rate v-model="formData.score" :max="5" />
                </el-form-item>
                <el-form-item label="评价内容" prop="review_content">
                    <el-input type="textarea" v-model="formData.review_content" clearable placeholder="请输入评价内容" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="reviewTableEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiReviewTableAdd, apiReviewTableEdit, apiReviewTableDetail } from '@/api/review_table'
import { timeFormat } from '@/utils/util'
import type { PropType } from 'vue'
import { ClientRoleMap } from "@/enums/appEnums";
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
    return mode.value == 'edit' ? '编辑评价' : '新增评价'
})

// 表单数据
const formData = reactive({
    id: '',
    project_id: '',
    project_name: '',
    reviewer_id: '',
    reviewer_name: '',
    reviewer_type: '',

    target_type: '',
    target_id: '',
    target_name: '',
    score: '',
    review_content: '',
})


// 表单验证
const formRules = reactive<any>({
    project_name: [{
        required: true,
        message: '请输入项目名称',
        trigger: ['blur']
    }],
    reviewer_id: [{
        required: true,
        message: '请输入评价人',
        trigger: ['blur']
    }],
    reviewer_type: [{
        required: true,
        message: '请输入评价类型',
        trigger: ['blur']
    }],
    target_type: [{
        required: true,
        message: '请输入目标类型',
        trigger: ['blur']
    }],
    target_id: [{
        required: true,
        message: '请输入被评价人',
        trigger: ['blur']
    }],
    score: [{
        required: true,
        message: '请输入评分',
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
    const data = await apiReviewTableDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData, }
    mode.value == 'edit'
        ? await apiReviewTableEdit(data)
        : await apiReviewTableAdd(data)
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
