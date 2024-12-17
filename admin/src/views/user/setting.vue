<!-- 个人资料 -->
<template>
    <div class="user-setting">
        <el-tabs v-model="activeName">
            <el-tab-pane label="公司信息" name="userinfo">
                <el-card class="!border-none" shadow="never">
                    <el-alert type="error" title="温馨提示：营业执照、劳务派遣资质、人力资源许可证 等信息均在招聘项目详情展示时显示,务必如实填写" :closable="false"
                        show-icon></el-alert>
                </el-card>
                <el-card class="!border-none" shadow="never">
                    <el-form ref="formInfoRef" class="ls-form" :model="formData" :rules="rules" label-width="200px">
                        <el-form-item label="公司LOGO：" prop="avatar">
                            <MaterialPicker v-model="formData.avatar" :limit="1" />
                        </el-form-item>

                        <el-form-item label="账号：" prop="account">
                            <div class="w-80">
                                <el-input v-model="formData.account" disabled />
                            </div>
                        </el-form-item>

                        <el-form-item label="公司简称：" prop="org_name">
                            <div class="w-80">
                                <el-input v-model="formData.name" placeholder="请输入公司名称" disabled />
                            </div>
                        </el-form-item>
                        <el-form-item label="公司介绍：" prop="company_info">
                            <div class="w-80">
                                <el-input v-model="formData.company_info" type="textarea"
                                    :autosize="{ minRows: 3, maxRows: 3 }" maxlength="200" show-word-limit clearable
                                    placeholder="请输入公司介绍" />
                            </div>
                        </el-form-item>
                        <el-form-item label="营业执照：" prop="company_img">
                            <MaterialPicker v-model="formData.company_img" :limit="1" />
                        </el-form-item>

                        <el-form-item label="劳务派遣资质：" prop="ld_licence_img">
                            <MaterialPicker v-model="formData.ld_licence_img" :limit="1" />
                        </el-form-item>

                        <el-form-item label="人力资源许可证：" prop="hr_licence_img">
                            <MaterialPicker v-model="formData.hr_licence_img" :limit="1" />
                        </el-form-item>
                    </el-form>
                </el-card>
                <footer-btns>
                    <el-button type="primary" @click="handleUserInfoSubmit">保存</el-button>
                </footer-btns>
            </el-tab-pane>
            <el-tab-pane label="密码修改" name="second"> <el-card class="!border-none" shadow="never">
                    <el-form ref="formPasswordRef" class="ls-form" :model="formData" :rules="rules" label-width="200px">
                        <el-form-item label="当前密码：" prop="password_old">
                            <div class="w-80">
                                <el-input v-model.trim="formData.password_old" placeholder="修改密码时必填, 不修改密码时留空"
                                    type="password" show-password />
                            </div>
                        </el-form-item>

                        <el-form-item label="新的密码：" prop="password">
                            <div class="w-80">
                                <el-input v-model.trim="formData.password" placeholder="修改密码时必填, 不修改密码时留空"
                                    type="password" show-password />
                            </div>
                        </el-form-item>

                        <el-form-item label="确定密码：" prop="password_confirm">
                            <div class="w-80">
                                <el-input v-model.trim="formData.password_confirm" placeholder="修改密码时必填, 不修改密码时留空"
                                    type="password" show-password />
                            </div>
                        </el-form-item>
                    </el-form>
                </el-card>
                <footer-btns>
                    <el-button type="primary" @click="handlePasswordSubmit">保存</el-button>
                </footer-btns>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script setup lang="ts" name="userSetting">
import { setUserInfo, setAuth } from '@/api/user'
import feedback from '@/utils/feedback'
import type { FormInstance } from 'element-plus'
const formInfoRef = ref<FormInstance>()
const formAuthRef = ref<FormInstance>()
const formPasswordRef = ref<FormInstance>()
import useUserStore from '@/stores/modules/user'
const userStore = useUserStore()
import MaterialPicker from '@/components/material/picker.vue'
import { useRoute, useRouter } from 'vue-router';

const route = useRoute()
const router = useRouter();
const activeName = ref('userinfo')

// 表单数据
const formData = reactive({
    name: '',
    avatar: '', // 头像
    account: '', // 账号
    company_info: '', // 公司介绍
    company_name: '',
    company_img: '', //组织资质
    ld_licence_img: '', //劳务派遣
    hr_licence_img: '', //人力资质
    password_old: '', // 当前密码
    password: '', // 新的密码
    password_confirm: '', // 确定密码
    enterpriseVerification: {
        org_name: '', // 名称
        org_id_card_num: '', // 统一社会信用代码
        legal_rep_name: '', // 法人
        legal_rep_id_card_num: '', // 证件号
        bank_name: '', // 银行账号
        bank_card: '', // 银行账号
        auth_flow_id: '', // 银行账号
        auth_url: '', // 银行账号
        realname_status: '',
    },
})

// 表单校验规则
const rules = reactive<object>({
    avatar: [
        {
            required: true,
            message: '请上传公司LOGO',
            trigger: ['change']
        }
    ],
    company_info: [
        {
            required: true,
            message: '请输入公司介绍',
            trigger: ['blur']
        }
    ],
    ld_licence_img: [
        {
            required: true,
            message: '请上传劳务派遣资质',
            trigger: ['change']
        }
    ],
    hr_licence_img: [
        {
            required: true,
            message: '请上传人力资源资质',
            trigger: ['change']
        }
    ],
    company_img: [
        {
            required: true,
            message: '请上传营业执照',
            trigger: ['change']
        }
    ],
})

// 表单校验规则
const authRules = reactive<object>({
    "enterpriseVerification.org_name": [
        {
            required: true,
            message: '请输入法人证件号',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.org_id_card_num": [
        {
            required: true,
            message: '请输入统一社会信用代码',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.legal_rep_name": [
        {
            required: true,
            message: '请输入法人姓名',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.legal_rep_id_card_num": [
        {
            required: true,
            message: '请输入法人证件号',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.bank_name": [
        {
            required: true,
            message: '请输银行名称',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.bank_card": [
        {
            required: true,
            message: '请输入银行账号',
            trigger: ['blur']
        }
    ],
})

const handleTabChange = (newActiveName) => {
    router.push({ query: { ...route.query, type: newActiveName } });
};


// 获取个人设置
const getUser = async () => {
    const userInfo = userStore.userInfo
    for (const key in formData) {
        //@ts-ignore
        formData[key] = userInfo[key]
    }
}

// 设置个人设置
const setUser = async () => {
    if (formData.password_old || formData.password || formData.password_confirm) {
        if (!formData.password_old) {
            return feedback.msgError('请输入当前密码')
        }

        if (!formData.password) {
            return feedback.msgError('请输入新的密码')
        }

        if (!formData.password_confirm) {
            return feedback.msgError('请输入确定密码')
        }

        if (formData.password_confirm != formData.password) {
            return feedback.msgError('两次输入的密码不一样')
        }
    }

    if (formData.password_old && formData.password && formData.password_confirm) {
        if (formData.password_old.length < 6 || formData.password_old.length > 32) {
            return feedback.msgError('密码长度在6到32之间')
        }
        if (formData.password.length < 6 || formData.password.length > 32) {
            return feedback.msgError('密码长度在6到32之间')
        }
        if (formData.password_confirm.length < 6 || formData.password_confirm.length > 32) {
            return feedback.msgError('密码长度在6到32之间')
        }
    }
    await setUserInfo(formData)
    userStore.updateUserInfo()
}

const handleUserInfoSubmit = async () => {
    await formInfoRef.value?.validate()
    setUser()
};

const handlePasswordSubmit = async () => {
    await formPasswordRef.value?.validate()
    setUser()
};

const handleAuthSubmit = async () => {
    await formAuthRef.value?.validate()
    // if (formData.enterpriseVerification.auth_url != '' && formData.enterpriseVerification.realname_status < 4) {
    //     showConfirm("签署合同需跳转到e签宝", formData.enterpriseVerification.auth_url)
    //     return
    // }
    const ret = await setAuth(formData)
    getUser()

    if (ret.status == 2 || ret.status == 3) {
        showConfirm("签署合同需跳转到e签宝", ret.response.data?.authShortUrl)
    }
    if (ret.status == 4) {
        showConfirm("授权成功", "")
    }
};
const showConfirm = (content: string, link: string) => {
    ElMessageBox.confirm(
        content,
        '提示',
        {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning',
        }
    ).then(() => {
        // 跳转到外部链接
        const externalUrl = link; // 替换为你的外部链接
        window.open(externalUrl, '_blank'); // 在新标签页中打开外部链接
    }).catch(() => {
        // 用户点击取消，不做任何处理
    });
};
onMounted(() => {
    getUser()
})
</script>

<style lang="scss" scoped></style>
