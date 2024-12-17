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
                <el-form-item label="组织ID" prop="org_id">
                    <el-input v-model="formData.org_id" clearable placeholder="请输入组织ID" />
                </el-form-item>
                <el-form-item label="组织名称" prop="org_name">
                    <el-input v-model="formData.org_name" clearable placeholder="请输入组织名称" />
                </el-form-item>
                <el-form-item label="组织类型" prop="org_type">
                    <el-input v-model="formData.org_type" clearable placeholder="请输入组织类型" />
                </el-form-item>
                <el-form-item label="组织证件号码" prop="org_id_card_num">
                    <el-input v-model="formData.org_id_card_num" clearable placeholder="请输入组织证件号码" />
                </el-form-item>
                <el-form-item label="组织证件类型" prop="org_id_card_type">
                    <el-input v-model="formData.org_id_card_type" clearable placeholder="请输入组织证件类型" />
                </el-form-item>
                <el-form-item label="法人代表姓名" prop="legal_rep_name">
                    <el-input v-model="formData.legal_rep_name" clearable placeholder="请输入法人代表姓名" />
                </el-form-item>
                <el-form-item label="法人代表证件号码" prop="legal_rep_id_card_num">
                    <el-input v-model="formData.legal_rep_id_card_num" clearable placeholder="请输入法人代表证件号码" />
                </el-form-item>
                <el-form-item label="法人代表证件类型" prop="legal_rep_id_card_type">
                    <el-input v-model="formData.legal_rep_id_card_type" clearable placeholder="请输入法人代表证件类型" />
                </el-form-item>
                <el-form-item label="管理员姓名" prop="admin_name">
                    <el-input v-model="formData.admin_name" clearable placeholder="请输入管理员姓名" />
                </el-form-item>
                <el-form-item label="管理员账号" prop="admin_account">
                    <el-input v-model="formData.admin_account" clearable placeholder="请输入管理员账号" />
                </el-form-item>
                <el-form-item label="是否授权用户信息" prop="authorize_user_info">
                    <el-input v-model="formData.authorize_user_info" clearable placeholder="请输入是否授权用户信息" />
                </el-form-item>
                <el-form-item label="实名认证状态" prop="realname_status">
                    <el-input v-model="formData.realname_status" clearable placeholder="请输入实名认证状态" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="enterpriseVerificationEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiEnterpriseVerificationAdd, apiEnterpriseVerificationEdit, apiEnterpriseVerificationDetail } from '@/api/enterprise_verification'
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
    return mode.value == 'edit' ? '编辑企业实名认证' : '新增企业实名认证'
})

// 表单数据
const formData = reactive({
    id: '',
    user_id: '',
    org_id: '',
    org_name: '',
    org_type: '',
    org_id_card_num: '',
    org_id_card_type: '',
    legal_rep_name: '',
    legal_rep_id_card_num: '',
    legal_rep_id_card_type: '',
    admin_name: '',
    admin_account: '',
    authorize_user_info: '',
    realname_status: '',
})


// 表单验证
const formRules = reactive<any>({
    user_id: [{
        required: true,
        message: '请输入用户ID',
        trigger: ['blur']
    }],
    org_id: [{
        required: true,
        message: '请输入组织ID',
        trigger: ['blur']
    }],
    org_name: [{
        required: true,
        message: '请输入组织名称',
        trigger: ['blur']
    }],
    org_id_card_num: [{
        required: true,
        message: '请输入组织证件号码',
        trigger: ['blur']
    }],
    org_id_card_type: [{
        required: true,
        message: '请输入组织证件类型',
        trigger: ['blur']
    }],
    legal_rep_name: [{
        required: true,
        message: '请输入法人代表姓名',
        trigger: ['blur']
    }],
    admin_name: [{
        required: true,
        message: '请输入管理员姓名',
        trigger: ['blur']
    }],
    admin_account: [{
        required: true,
        message: '请输入管理员账号',
        trigger: ['blur']
    }],
    realname_status: [{
        required: true,
        message: '请输入实名认证状态',
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
    const data = await apiEnterpriseVerificationDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiEnterpriseVerificationEdit(data) 
        : await apiEnterpriseVerificationAdd(data)
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
