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
                <el-form-item label="合同模版ID" prop="template_id">
                    <el-input v-model="formData.template_id" clearable placeholder="请输入合同模版ID" />
                </el-form-item>
                <el-form-item label="发起合同用户" prop="user_id">
                    <el-input v-model="formData.user_id" clearable placeholder="请输入发起合同用户" />
                </el-form-item>
                <el-form-item label="参与合同" prop="to_user_id">
                    <el-input v-model="formData.to_user_id" clearable placeholder="请输入参与合同" />
                </el-form-item>
                <el-form-item label="0 - 企业 1 - 个人" prop="type">
                    <el-input v-model="formData.type" clearable placeholder="请输入0 - 企业 1 - 个人" />
                </el-form-item>
                <el-form-item label="发起人签约账户" prop="send_psn_id">
                    <el-input v-model="formData.send_psn_id" clearable placeholder="请输入发起人签约账户" />
                </el-form-item>
                <el-form-item label="发起人组织ID" prop="send_org_id">
                    <el-input v-model="formData.send_org_id" clearable placeholder="请输入发起人组织ID" />
                </el-form-item>
                <el-form-item label="接收人账户ID" prop="accept_psn_id">
                    <el-input v-model="formData.accept_psn_id" clearable placeholder="请输入接收人账户ID" />
                </el-form-item>
                <el-form-item label="接收人组织ID" prop="accept_org_id">
                    <el-input v-model="formData.accept_org_id" clearable placeholder="请输入接收人组织ID" />
                </el-form-item>
                <el-form-item label="合同名称" prop="name">
                    <el-input v-model="formData.name" clearable placeholder="请输入合同名称" />
                </el-form-item>
                <el-form-item label="0 - 发起 1 - 接受" prop="is_send">
                    <el-input v-model="formData.is_send" clearable placeholder="请输入0 - 发起 1 - 接受" />
                </el-form-item>
                <el-form-item label="0 - 待签署 1 - 发起签署完成 2 - 参与方签署完成 3 - 结束" prop="status">
                    <el-input v-model="formData.status" clearable placeholder="请输入0 - 待签署 1 - 发起签署完成 2 - 参与方签署完成 3 - 结束" />
                </el-form-item>
                <el-form-item label="签署流ID" prop="signFlowId">
                    <el-input v-model="formData.signFlowId" clearable placeholder="请输入签署流ID" />
                </el-form-item>
                <el-form-item label="合同地址" prop="contract_file">
                    <el-input v-model="formData.contract_file" clearable placeholder="请输入合同地址" />
                </el-form-item>
            </el-form>
        </popup>
    </div>
</template>

<script lang="ts" setup name="eContractEdit">
import type { FormInstance } from 'element-plus'
import Popup from '@/components/popup/index.vue'
import { apiEContractAdd, apiEContractEdit, apiEContractDetail } from '@/api/e_contract'
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
    return mode.value == 'edit' ? '编辑合同表' : '新增合同表'
})

// 表单数据
const formData = reactive({
    id: '',
    template_id: '',
    user_id: '',
    to_user_id: '',
    type: '',
    send_psn_id: '',
    send_org_id: '',
    accept_psn_id: '',
    accept_org_id: '',
    name: '',
    is_send: '',
    status: '',
    signFlowId: '',
    contract_file: '',
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
    const data = await apiEContractDetail({
        id: row.id
    })
    setFormData(data)
}


// 提交按钮
const handleSubmit = async () => {
    await formRef.value?.validate()
    const data = { ...formData,  }
    mode.value == 'edit' 
        ? await apiEContractEdit(data) 
        : await apiEContractAdd(data)
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
