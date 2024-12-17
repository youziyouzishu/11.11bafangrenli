<!-- 签约 -->
<template>
    <el-card class="!border-none" shadow="never">
        <el-alert type="warning" title="温馨提示：实名和签约在第三方合同管理平台《e签宝》" :closable="false" show-icon></el-alert>
    </el-card>
    <div class="user-setting">
        <el-tabs v-model="activeName" v-if="formData != null">
            <el-tab-pane label="实名&签约" name="auth">
                <el-card class="!border-none" shadow="never">
                    <el-form ref="formAuthRef" class="ls-form" :model="formData" :rules="authRules" label-width="200px">

                        <el-form-item label="公司名称：" prop="enterpriseVerification.org_name">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.org_name" placeholder="请输入公司名称"
                                    :disabled="formData?.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>
                        <el-form-item label="统一社会信用代码：" prop="enterpriseVerification.org_id_card_num">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.org_id_card_num"
                                    placeholder="请输入统一社会信用代码"
                                    :disabled="formData.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>

                        <el-form-item label="法人姓名：" prop="enterpriseVerification.legal_rep_name">
                            <div class="w-800">
                                <el-input v-model="formData.enterpriseVerification.legal_rep_name" placeholder="法人/经营者"
                                    :disabled="formData.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>
                        <el-form-item label="法人证件号：" prop="enterpriseVerification.legal_rep_id_card_num">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.legal_rep_id_card_num"
                                    placeholder="法人证件号"
                                    :disabled="formData.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>
                        <el-form-item label="电子邮箱：" prop="enterpriseVerification.email">
                            <div class="w-80">
                                <el-input tyle="email" v-model="formData.enterpriseVerification.email"
                                    placeholder="电子邮箱"
                                    :disabled="formData.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>
                        <el-form-item label="通信地址：" prop="enterpriseVerification.address">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.address" placeholder="文书送达地址"
                                    :disabled="formData.enterpriseVerification.realname_status > 3" />
                            </div>
                        </el-form-item>
                        <el-form-item label="银行名称：" prop="enterpriseVerification.bank_name">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.bank_name"
                                    placeholder="请输入开户行详细地址" />
                            </div>
                        </el-form-item>
                        <el-form-item label="银行账号：" prop="enterpriseVerification.bank_card">
                            <div class="w-80">
                                <el-input v-model="formData.enterpriseVerification.bank_card" placeholder="请输入银行账号" />
                            </div>
                        </el-form-item>
                    </el-form>
                </el-card>
                <footer-btns :v-show="userStore.userInfo != null">
                    <el-button v-prevent-reclick type="primary" @click="handleAuthSubmit"
                        v-show="formData.enterpriseVerification.realname_status < 5">{{
            formData.enterpriseVerification.realname_status < 4 ? '实名授权' : '发起合同签署' }}</el-button>
                            <el-button v-prevent-reclick type="primary" @click="handleAuthSubmit"
                                v-show="formData.enterpriseVerification.realname_status >= 5"
                                :disabled="formData.enterpriseVerification.realname_status == 6"> {{
            formData.enterpriseVerification.realname_status == 5 ? '去签署' : '签署完成' }}</el-button>
                </footer-btns>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script setup lang="ts">
import { setUserInfo, setAuth, signContract } from '@/api/user'
import feedback from '@/utils/feedback'
import type { FormInstance } from 'element-plus'
import { ref, reactive } from 'vue'
import useUserStore from '@/stores/modules/user'
const userStore = useUserStore()
const formAuthRef = ref<FormInstance>()
import { useRouter } from 'vue-router';
const router = useRouter();

const activeName = ref('auth')

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
        realname_status: 0,
        address: '', //文书送达地址
        email: '', //邮箱
    },
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
    "enterpriseVerification.address": [
        {
            required: true,
            message: '请输入文书送达地址',
            trigger: ['blur']
        }
    ],
    "enterpriseVerification.email": [
        {
            required: true,
            message: '请输入电子邮箱',
            trigger: ['blur']
        }
    ],
})



// 获取个人设置
const getUser = async () => {
    await userStore.updateUserInfo()
    const userInfo = userStore.userInfo
    for (const key in formData) {
        if (key == 'enterpriseVerification' && formData[key] == null) {
            continue
        }
        if (userInfo[key] != null) {
            //@ts-ignore
            formData[key] = userInfo[key]
        }
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




const handleAuthSubmit = async () => {
    if (formData.enterpriseVerification.realname_status < 4) {
        await formAuthRef.value?.validate()
        const ret = await setAuth(formData)

        if (ret.status == 2 || ret.status == 3) {
            showConfirm("签约授权需跳转到e签宝", ret.response.data?.authShortUrl)
        }
        if (ret.status == 4) {
            //刷新当前页面渲染
            alert('实名认证成功')
            window.location.reload()
        }
    } else if (formData.enterpriseVerification.realname_status == 4) {
        const ret = await signContract(formData)
        showConfirm("签署合同需跳转到e签宝", ret.data.url)
        //alert(ret)

    } else if (formData.enterpriseVerification.realname_status == 5) {
        router.push({
            path: '/operate/contracts/e_contract', // 确保在路由配置中有名称为 'e_contract' 的路由
            query: {
                type: "plant_contract"
            }
        });
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
        window.location.href = link;
        //window.open(externalUrl, ''); // 在新标签页中打开外部链接
    }).catch(() => {
        // 用户点击取消，不做任何处理
    });
};
onMounted(async () => {
    await getUser()
})
</script>

<style lang="scss" scoped></style>
