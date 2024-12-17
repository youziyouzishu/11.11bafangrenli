<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>


                <el-form-item label="参与合同" prop="to_user_id">
                    <el-input class="w-[200px]" v-model="queryParams.to_user_id" clearable placeholder="请输入参与合同" />
                </el-form-item>

                <el-form-item label="合同名称" prop="name">
                    <el-input class="w-[200px]" v-model="queryParams.name" clearable placeholder="请输入合同名称" />
                </el-form-item>

                <el-form-item label="签署状态" prop="type">
                    <el-select class="w-[280px]" v-model="queryParams.status" clearable placeholder="请选签署状态">
                        <el-option label="全部" value=""></el-option>
                        <el-option v-for="(item, index) in dictData.sign_status" :key="index" :label="item.name"
                            :value="item.value" />
                    </el-select>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-alert type="error" class="mb-3" title="合同发起签署请在项目审核中发起" :closable="false" show-icon></el-alert>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-tabs v-model="activeName" @tab-click="handleTabClick">
                <el-tab-pane label="我发起的合同" name="send_contract">

                    <!-- <el-button v-perms="['e_contract/add']" type="primary" @click="handleAdd">
                        <template #icon>
                            <icon name="el-icon-Plus" />
                        </template>
发起
</el-button> -->

                    <div class="mt-4">
                        <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                            <el-table-column width="320" label="合同 ID" prop="signFlowId" show-overflow-tooltip />
                            <el-table-column label="合同名称" prop="name" show-overflow-tooltip />
                            <!-- <el-table-column width="320" label="合同模版 ID" prop="template_id" show-overflow-tooltip /> -->
                            <el-table-column label="参与者名称" prop="to_user_id">
                                <template #default="{ row }">

                                    <el-popover effect="light" trigger="hover" placement="top" width="auto">
                                        <template #default>

                                            <el-descriptions :title="row.personalVerification?.name" :column="2" border>
                                                <el-descriptions-item label="姓名" label-align="right" align="center"
                                                    label-class-name="my-label" class-name="my-content">{{
                row.personalVerification?.psn_name }}
                                                    <el-icon @click.prevent="_copy(row.personalVerification?.psn_name)"
                                                        style="cursor: pointer">
                                                        <CopyDocument />
                                                    </el-icon>
                                                </el-descriptions-item>


                                                <el-descriptions-item label="电话" label-align="right" align="center">{{
                row.personalVerification?.psn_mobile
            }}<el-icon
                                                        @click.prevent="_copy(row.personalVerification?.psn_mobile)"
                                                        style="cursor: pointer">
                                                        <CopyDocument />
                                                    </el-icon>
                                                </el-descriptions-item>
                                                <el-descriptions-item label="身份证号" label-align="right" align="center">
                                                    {{
                row.personalVerification?.psn_id_card_num }}
                                                    <el-icon
                                                        @click.prevent="_copy(row.personalVerification?.psn_id_card_num)"
                                                        style="cursor: pointer">
                                                        <CopyDocument />
                                                    </el-icon>
                                                </el-descriptions-item>

                                            </el-descriptions>
                                        </template>
                                        <template #reference>
                                            <el-tag>{{ row.personalVerification?.psn_name }}</el-tag>
                                        </template>
                                    </el-popover>
                                </template>
                            </el-table-column>
                            <el-table-column label="参与者类型" prop="type">
                                <template #default="{ row }">
                                    <dict-value :options="dictData.sign_type" :value="row.type" />
                                </template>
                            </el-table-column>

                            <el-table-column label="状态" prop="status">
                                <template #default="{ row }">
                                    <dict-value :options="dictData.sign_status" :value="row.status" />
                                </template>
                            </el-table-column>
                            <el-table-column label="操作" width="120" fixed="right">
                                <template #default="{ row }">
                                    <el-button v-perms="['e_contract/edit']" type="primary" link
                                        @click="handleEdit(row)">
                                        编辑
                                    </el-button>

                                    <el-button v-perms="['e_contract/delete']"
                                        v-show="row.status == 0 || row.status == 1" type="danger" link
                                        @click="handleSignContract(row.id)">
                                        去签署
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <div class="flex mt-4 justify-end">
                        <pagination v-model="pager" @change="getLists" />
                    </div>
                </el-tab-pane>

                <el-tab-pane label="八方人力平台合作合同" name="plant_contract">

                    <div class="mt-4">
                        <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                            <el-table-column width="320" label="合同 ID" prop="signFlowId" show-overflow-tooltip />
                            <el-table-column label="合同名称" prop="name" show-overflow-tooltip />
                            <!-- <el-table-column width="320" label="合同模版 ID" prop="template_id" show-overflow-tooltip /> -->
                            <el-table-column label="参与者名称" prop="to_user_id">
                                <template #default="{ row }">
                                    {{ row.to_user_id == 1 ? '八方平台' : row.to_user_id }}
                                </template>
                            </el-table-column>
                            <el-table-column label="参与者类型" prop="type">
                                <template #default="{ row }">
                                    <dict-value :options="dictData.sign_type" :value="row.type" />
                                </template>
                            </el-table-column>

                            <el-table-column label="状态" prop="status">
                                <template #default="{ row }">
                                    <dict-value :options="dictData.sign_status" :value="row.status" />
                                </template>
                            </el-table-column>
                            <el-table-column label="操作" width="200" fixed="right">
                                <template #default="{ row }">
                                    <el-button v-perms="['e_contract/edit']" type="primary" link
                                        @click="handleEdit(row)">
                                        编辑
                                    </el-button>
                                    <el-button v-perms="['e_contract/delete']"
                                        v-show="row.status == 0 || row.status == 1" type="danger" link
                                        @click="handleSignContract(row.id)">
                                        去签署
                                    </el-button>
                                    <el-button v-perms="['e_contract/delete']" v-show="row.status == 2" type="success"
                                        link @click="handleGetContract(row.id)">
                                        查看合同
                                    </el-button>
                                    <el-button v-perms="['e_contract/delete']" v-show="row.status == 2" type="success"
                                        link @click="handleGetDownloadContract(row.id)">
                                        下载合同
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <div class="flex mt-4 justify-end">
                        <pagination v-model="pager" @change="getLists" />
                    </div>
                </el-tab-pane>
            </el-tabs>
        </el-card>

        <edit-popup v-if="showEdit" ref="editRef" :dict-data="dictData" @success="getLists" @close="showEdit = false" />
    </div>
</template>

<script lang="ts" setup name="eContractLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { signContract } from '@/api/user'
import { apiEContractLists, apiEContractDelete, apiSignContractUrl, apiDownloadContractUrl } from '@/api/e_contract'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import { watch } from 'vue'
import useUserStore from '@/stores/modules/user'
const userStore = useUserStore()

import { useRoute, useRouter } from 'vue-router';

const activeName = ref('send_contract')

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)

const route = useRoute()
const router = useRouter();

// 查询条件
const queryParams = reactive({
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
    active_name: 'send_contract',
})

// 选中数据
const selectData = ref<any[]>([])

activeName.value = route.query.type ?? "send_contract"
queryParams.active_name = activeName.value

watch(() => activeName.value, (newType, oldType) => {
    queryParams.active_name = newType
    router.push({ query: { ...route.query, type: queryParams.active_name } });
});

const handleTabClick = () => {

    //getLists()
}

userStore.updateUserInfo()
// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

// 获取字典数据
const { dictData } = useDictData('sign_status,sign_type')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiEContractLists,
    params: queryParams
})

// 添加
const handleAdd = async () => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('add')
}

// 编辑
const handleEdit = async (data: any) => {
    showEdit.value = true
    await nextTick()
    editRef.value?.open('edit')
    editRef.value?.setFormData(data)
}

// 删除
const handleDelete = async (id: number | any[]) => {
    await feedback.confirm('确定要删除？')
    await apiEContractDelete({ id })
    getLists()
}

const handleSignContract = async (id: number | any[]) => {
    await feedback.confirm('去签署合同？')
    const d = await apiSignContractUrl({ id })
    window.open(d.data.url, '_blank');
}
const handleGetContract = async (id: number | any[]) => {
    await feedback.confirm('支付宝扫码后查阅合同')
    const d = await apiSignContractUrl({ id })
    window.open(d.data.url, '_blank');
}
const handleGetDownloadContract = async (id: number | any[]) => {
    const d = await apiDownloadContractUrl({ id })
    window.open(d.data.files[0].downloadUrl, '_blank');
}
getLists()

// 复制操作
const _copy = async (context) => {
    // 创建输入框元素
    const oInput = document.createElement("input");
    // 将想要复制的值
    oInput.value = context;
    // 页面底部追加输入框
    document.body.appendChild(oInput);
    // 选中输入框
    oInput.select();
    // 执行浏览器复制命令
    document.execCommand("Copy");
    // 弹出复制成功信息
    ElMessage({
        type: "success",
        message: "复制成功",
    });
    // 复制后移除输入框
    oInput.remove();
};
</script>
