<!-- 网站信息 -->
<template>
  <div class="website-information">
    <el-form ref="formRef" class="ls-form" :model="formData" label-width="120px">
      <el-card shadow="never" class="!border-none">
        <el-form-item label="合同说明" prop="contract_explain">
          <div class="w-80">
            <el-input
                v-model.trim="formData.contract_explain"
                placeholder="请输入合同说明"
                maxlength="30"
                show-word-limit
            />
          </div>
        </el-form-item>
        <el-form-item label="网站图标" prop="web_favicon" required>
          <div>
            <material-picker v-model="formData.web_favicon" :limit="1" />
            <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
          </div>
        </el-form-item>
        <el-form-item label="网站LOGO" prop="web_logo" required>
          <div>
            <material-picker v-model.trim="formData.web_logo" :limit="1" />
            <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
          </div>
        </el-form-item>
        <el-form-item label="登录页广告图" prop="login_image" required>
          <div>
            <material-picker v-model.trim="formData.login_image" :limit="1" />
            <div class="form-tips">建议尺寸：100*100像素，支持jpg，jpeg，png格式</div>
          </div>
        </el-form-item>
      </el-card>
    </el-form>
    <footer-btns>
      <el-button type="primary" @click="handleSubmit">保存</el-button>
    </footer-btns>
  </div>
</template>

<script lang="ts" setup name="webInformation">
import { getWebsite, setWebsite } from '@/api/setting/website'
import useAppStore from '@/stores/modules/app'
import type { FormInstance } from 'element-plus'
const formRef = ref<FormInstance>()

const appStore = useAppStore()
// 表单数据
const formData = reactive({
  contract_explain: '', // 合同说明
  web_favicon: '', // 网站图标
  web_logo: '', // 网站logo
  login_image: '', // 登录页广告图
  shop_name: '',
  shop_logo: '',
  pc_logo: '',
  pc_title: '',
  pc_desc: '',
  pc_ico: '',
  pc_keywords: ''
})



// 获取备案信息
const getData = async () => {
  const data = await getWebsite()
  for (const key in formData) {
    //@ts-ignore
    formData[key] = data[key]
  }
}

// 设置备案信息
const handleSubmit = async () => {
  await formRef.value?.validate()
  await setWebsite(formData)
  appStore.getConfig()
  getData()
}

getData()
</script>

<style lang="scss" scoped></style>
