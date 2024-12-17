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
                <el-form-item label="用户ID" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入用户ID" />
                </el-form-item>
                <el-form-item label="用户信息授权状态" prop="authorize_user_info">
                    <el-input v-model="formData.authorize_user_info" clearable placeholder="请输入用户信息授权状态" />
                </el-form-item>
                <el-form-item label="实名状态: 0-未实名, 1-已实名" prop="realname_status">
                    <el-input v-model="formData.realname_status" clearable placeholder="请输入实名状态: 0-未实名, 1-已实名" />
                </el-form-item>
                <el-form-item label="个人ID" prop="psn_id">
                    <el-input v-model="formData.psn_id" clearable placeholder="请输入个人ID" />
                </el-form-item>
                <el-form-item label="账户手机号" prop="account_mobile">
                    <el-input v-model="formData.account_mobile" clearable placeholder="请输入账户手机号" />
                </el-form-item>
                <el-form-item label="账户电子邮箱" prop="account_email">
                    <el-input v-model="formData.account_email" clearable placeholder="请输入账户电子邮箱" />
                </el-form-item>
                <el-form-item label="个人姓名" prop="psn_name">
                    <el-input v-model="formData.psn_name" clearable placeholder="请输入个人姓名" />
                </el-form-item>
                <el-form-item label="国籍" prop="psn_nationality">
                    <el-input v-model="formData.psn_nationality" clearable placeholder="请输入国籍" />
                </el-form-item>
                <el-form-item label="身份证号码" prop="psn_id_card_num">
                    <el-input v-model="formData.psn_id_card_num" clearable placeholder="请输入身份证号码" />
                </el-form-item>
                <el-form-item label="证件类型" prop="psn_id_card_type">
                    <el-input v-model="formData.psn_id_card_type" clearable placeholder="请输入证件类型" />
                </el-form-item>
                <el-form-item label="银行卡号" prop="bank_card_num">
                    <el-input v-model="formData.bank_card_num" clearable placeholder="请输入银行卡号" />
                </el-form-item>
                <el-form-item label="手机号" prop="psn_mobile">
                    <el-input v-model="formData.psn_mobile" clearable placeholder="请输入手机号" />
                </el-form-item>
                <el-form-item label="创建时间" prop="created_at">
                    <el-input v-model="formData.created_at" clearable placeholder="请输入创建时间" />
                </el-form-item>
                <el-form-item label="更新时间" prop="updated_at">
                    <el-input v-model="formData.updated_at" clearable placeholder="请输入更新时间" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="personalVerificationEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiPersonalVerificationAdd, apiPersonalVerificationEdit, apiPersonalVerificationDetail } from '@/api/personal_verification'
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
    return mode.value == 'edit' ? '编辑个人实名认证表' : '新增个人实名认证表'
})

// 表单数据
const formData = reactive({
    id: '',
    user_id: '',
    authorize_user_info: '',
    realname_status: '',
    psn_id: '',
    account_mobile: '',
    account_email: '',
    psn_name: '',
    psn_nationality: '',
    psn_id_card_num: '',
    psn_id_card_type: '',
    bank_card_num: '',
    psn_mobile: '',
    created_at: '',
    updated_at: '',
})


// 表单验证
const formRules = reactive<any>({
    user_id: [{
        required: true,
        message: '请输入用户ID',
        trigger: ['blur']
    }],
    authorize_user_info: [{
        required: true,
        message: '请输入用户信息授权状态',
        trigger: ['blur']
    }],
    realname_status: [{
        required: true,
        message: '请输入实名状态: 0-未实名, 1-已实名',
        trigger: ['blur']
    }],
    psn_id: [{
        required: true,
        message: '请输入个人ID',
        trigger: ['blur']
    }],
    psn_name: [{
        required: true,
        message: '请输入个人姓名',
        trigger: ['blur']
    }],
    psn_id_card_num: [{
        required: true,
        message: '请输入身份证号码',
        trigger: ['blur']
    }],
    psn_id_card_type: [{
        required: true,
        message: '请输入证件类型',
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
    const data = await apiPersonalVerificationDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiPersonalVerificationEdit(data) 
        : await apiPersonalVerificationAdd(data)
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
