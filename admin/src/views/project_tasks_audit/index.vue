<template>
    <PermissionWrapper>
        <el-alert type="error" class="mb-3" title="注意： 合同签署成功，自动扣扣款合同费用每份 1.00 元。 余不足 100.00 元时，无法发起合同签署流程。"
            :closable="false" show-icon></el-alert>
        <el-card class="!border-none mt-2" v-loading="pager.loading" shadow="never">

            <el-tabs class="mt-[-20px]" v-model="activeTab" @tab-click="handleTabClick">
                <el-tab-pane label="招聘专员" name="2">
                    <!-- 待审核状态的数据 -->
                </el-tab-pane>
                <el-tab-pane label="驻场经理" name="3">
                    <!-- 待签署状态的数据 -->
                </el-tab-pane>
                <el-tab-pane label="入职员工" name="4">
                    <!-- 已完成状态的数据 -->
                </el-tab-pane>
            </el-tabs>

            <el-card class="!border-none mr-20" shadow="never">
                <el-form class="ml-[-16px]" :model="queryParams" inline>
                    <el-form-item label="" prop="project_id">
                        <el-select collapse-tags class="w-[280px]" v-model="queryParams.project_id" clearable
                            @change="handleSelectChange" placeholder="筛选项目">
                            <el-option label="不限" value=""></el-option>
                            <el-option v-for=" (item, index) in projectNames.lists" :key="item.id"
                                :label="item?.project_name" :value="String(item.id)" />
                        </el-select>
                    </el-form-item>
                    <el-form-item label="" prop="project_id">
                        <!-- 状态筛选器 -->
                        <el-select v-model="queryParams.status" class="w-[140px]" placeholder="筛选状态">
                            <el-option v-for="item in statusOptions" :key="item.value" :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="" prop="project_id">
                        <!-- 状态筛选器 -->
                        <el-select v-model="queryParams.work_status" class="w-[160px]" placeholder="筛选工作状态">
                            <el-option v-for="item in work_statusOptions" :key="item.value" :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>


                    <el-form-item>
                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <el-button @click="resetParams">重置</el-button>
                    </el-form-item>
                </el-form>
            </el-card>
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column label="项目名称" prop="projectinfo.project_name" show-overflow-tooltip fixed="left" />
                    <el-table-column :label="roleNames[activeTab]" prop="userinfo.psn_name" show-overflow-tooltip
                        fixed="left">
                        <template #default="{ row }">
                            <router-link :to="`/im?conversationId=C2Ccl_` + row?.user_sn" class="link">
                                {{ row.userinfo.psn_name }}
                            </router-link>

                        </template>
                    </el-table-column>
                    <el-table-column v-if="activeTab == '4'" :label="roleNames[2]" prop="recruit_name" width="90"
                        show-overflow-tooltip>
                        <template #default="{ row }">
                            <router-link v-if="row?.recruit_sn" :to="`/im?conversationId=C2Ccl_` + row?.recruit_sn"
                                class="link">
                                <icon name="el-icon-Avatar" :size="15" />
                            </router-link>
                            {{ row.recruit_name }}
                        </template>
                    </el-table-column>
                    <el-table-column v-if="activeTab == '4'" :label="roleNames[3]" prop="onsite_name" width="140"
                        show-overflow-tooltip>
                        <template #default="{ row }">
                            <router-link v-if="row?.onsite_sn" :to="`/im?conversationId=C2Ccl_` + row?.onsite_sn"
                                class="link">
                                <icon name="el-icon-Avatar" :size="15" />
                            </router-link>
                            <el-select v-model="row.onsite_user_id" class="w-[90px]" placeholder="变更驻场"
                                @change="changeOnsiteUser(row)">
                                <el-option v-for="item in  filterClientNamesData(row.project_id, 3) "
                                    :key="item.user_id" :label="item.psn_name" :value="item.user_id"
                                    :disabled="row.status != 2 || row?.contractinfo?.status != 2">
                                </el-option>
                            </el-select>
                        </template>
                    </el-table-column>


                    <el-table-column v-if="activeTab != '4'" label="电话" prop="userinfo.psn_mobile"
                        show-overflow-tooltip />

                    <el-table-column label="审核状态" prop="status" width="105" show-overflow-tooltip>
                        <template #default="{ row }">
                            <el-tag v-if="row.status == 0">待审核</el-tag>
                            <el-tag v-if="row.status == 1">待签署</el-tag>
                            <el-tag type="success" v-else-if="row.status == 2">完成</el-tag>
                            <el-tag type="danger" v-else-if="row.status == 3">拒绝</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="activeTab == '4'" width="120" label="工作状态" prop="work_status"
                        show-overflow-tooltip>
                        <template #default="{ row }">
                            <!-- 状态筛选器 -->
                            <el-select v-model="row.work_status" class="w-[90px]" placeholder="筛选工作状态"
                                @change="changeWorkStatus(row)">
                                <el-option v-for="item in  work_statusOptions " :key="item.value" :label="item.label"
                                    :value="item.value" :disabled="row.status != 2 || row?.contractinfo?.status != 2">
                                </el-option>
                            </el-select>
                        </template>
                    </el-table-column>
                    <el-table-column label="备注说明" prop="remarks" show-overflow-tooltip />
                    <el-table-column label="申请时间" prop="create_time" width="200px" show-overflow-tooltip />
                    <el-table-column label="审核时间" prop="update_time" width="200px" show-overflow-tooltip />
                    <el-table-column label="操作" width="130" fixed="left">
                        <template #default="{ row }">

                            <div v-if="row.contractinfo == null">
                                <el-button :disabled="row.status > 1" v-perms="['project_tasks_audit/audited']"
                                    type="primary" link @click="handleEnact(row)">
                                    发起
                                </el-button>
                                <el-button :disabled="row.status > 1" v-perms="['project_tasks_audit/audited']"
                                    type="danger" link @click="handleRefuse(row.id)">
                                    拒绝
                                </el-button>
                            </div>
                            <div v-if="row?.contractinfo != null">
                                <el-button v-perms="['e_contract/delete']"
                                    v-if="row.contractinfo?.status == 0 || row?.contractinfo?.status == 1" type="danger"
                                    link @click="handleSignContract(row.contractinfo.id)">
                                    签署
                                </el-button>
                                <el-button v-perms="['e_contract/delete']" v-if="row?.contractinfo?.status == 2"
                                    type="success" link @click="handleGetContract(row.contractinfo.id)">
                                    查看
                                </el-button>
                                <el-button v-perms="['e_contract/delete']" v-if="row?.contractinfo?.status == 2"
                                    type="success" link @click="handleGetDownloadContract(row.contractinfo.id)">
                                    下载
                                </el-button>

                            </div>
                            <el-button type="primary" link @click="showLogs(row.id)">
                                审计日志
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <!-- 日志记录组件 -->
        <AuditLogDialog :activeTab="activeTab" ref="formDrawerRef" :title="'审核日志'" :formData="formData"
            :formRules="formRules" :auditId="selectedAuditId" :visible.sync="drawerVisible" :popupTitle="'审计日志'"
            @close="handleFormClose" />

        <edit-popup v-if="showEdit" ref="editRef" :dict-data="dictData" @success="getLists" @close="showEdit = false" />
        <contract-popup v-if="showContract" ref="contractRef" :dict-data="dictData" @close="showEdit = false" />

    </PermissionWrapper>
</template>

<script lang="ts" setup name="projectTasksAuditLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiProjectTasksAuditLists, apiProjectTasksAudited, apiProjectTasksWorkAudited, apiGetNamesByType, apiProjectTaskChangeOnsiteUser } from '@/api/project_tasks_audit'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import ContractPopup from './contract.vue'
import { apiSignContractUrl, apiDownloadContractUrl } from '@/api/e_contract'
import { apiProjectTasksLists } from '@/api/project_tasks'
import AuditLogDialog from './AuditLogDialog.vue'  // 导入组件

import PermissionWrapper from '@/components/auth.vue';
import { ref, nextTick } from 'vue';

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
const contractRef = shallowRef<InstanceType<typeof ContractPopup>>()
// 是否显示编辑框
const showEdit = ref(false)
const showContract = ref(false)

const route = useRoute()
const router = useRouter();
const activeTab = ref(route.query.type)

// 状态选项
const statusOptions = [
    { label: '全部', value: null },
    { label: '待审核', value: 0 },
    { label: '待签署', value: 1 },
    { label: '完成', value: 2 },
    { label: '拒绝', value: 3 },
];

const work_statusOptions = [
    { label: '待到岗', value: 1 },
    { label: '已到岗', value: 2 },
    { label: '流失', value: 3 },
    { label: '离职', value: 4 },
];

const roleNames = { "2": "招聘专员", "3": "驻场经理", "4": "入职员工" }


// 查询条件
const queryParams = reactive({
    project_id: route.query.project_id,
    user_id: '',
    type: route.query.type,
    status: '',
    work_status: '',
    remarks: ''
})

// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

//获取项目名称
const handleSelectChange = (val) => {
    //router.push({ query: { ...route.query, project_id: val } });
}

const projectNames = ref({
    lists: []
})
const getapiProjectTasksLists = async () => {
    projectNames.value = await apiProjectTasksLists()

}
getapiProjectTasksLists()
const ClientNames = ref<any>([])
const getApiGetNamesByType = async () => {
    ClientNames.value = await apiGetNamesByType()
}

const filterClientNamesData = (projectId, type) => {
    console.log("ClientNames.value before filtering: ", ClientNames.value);

    const filteredData = ClientNames.value.filter(item => {
        console.log(item.project_id, projectId, item.type, type)
        return item.project_id == projectId && item.type == type;
    }).map(item => {
        return { user_id: item.user_id, psn_name: item.psn_name };
    });

    console.log("Filtered data: ", filteredData);

    return filteredData;
};
getApiGetNamesByType()
// 表格选择后回调事件
const handleTabClick = (val) => {
    pager.lists = []
    queryParams.type = val.paneName
    //router.push({ query: { ...route.query, type: val.paneName } });
    getLists()
}

// 获取字典数据
const { dictData } = useDictData('')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiProjectTasksAuditLists,
    params: queryParams
})


const changeWorkStatus = async (row: any[]) => {
    await handleRefuseWorkStatus(row.id, row.work_status)
}

const changeOnsiteUser = async (row: any[]) => {
    await handleRefuseOnsiteUser(row.id, row.onsite_user_id)
}
// 拒绝
const handleRefuse = async (id: number | any[]) => {
    let remarks = await feedback.prompt('请输入拒绝内容', '提示', {
        confirmButtonText: '拒绝',
        cancelButtonText: '取消',
        inputErrorMessage: '拒绝理由不正确'
    })
    await apiProjectTasksAudited({ id, remarks: remarks.value, status: 3 })
    getLists()
}

// 变更工作状态
const handleRefuseWorkStatus = async (id: number | any[], status: number) => {
    let remarks
    if (status == 3 || status == 4) {
        remarks = await feedback.prompt('请输入备注说明', '提示', {
            confirmButtonText: '保存',
            cancelButtonText: '取消',
            inputErrorMessage: '备注不正确'
        })
    }
    await apiProjectTasksWorkAudited({ id: id, remarks: remarks?.value, work_status: status })
    getLists()
}


// 变更工作状态
const handleRefuseOnsiteUser = async (id: number | any[], user_id: number) => {
    let remarks
    remarks = await feedback.prompt('请输入变更驻场说明', '提示', {
        confirmButtonText: '保存',
        cancelButtonText: '取消',
        inputErrorMessage: '备注不正确'
    })
    await apiProjectTaskChangeOnsiteUser({ id: id, remarks: remarks?.value, onsite_user_id: user_id })
    getLists()
}
//通过
const handleEnact = async (data: any) => {
    showContract.value = true
    await nextTick()
    contractRef.value?.open(data.type)
    contractRef.value?.setFormData(data)
    // await apiProjectTasksAudited({ id, status: 2 })
    //getLists()
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

// 选中审计ID和对话框可见性
const selectedAuditId = ref(0)
const drawerVisible = ref(false);
const formDrawerRef = ref<InstanceType<typeof FormDrawer>>();
// 显示日志
const showLogs = async (auditId: number) => {
    console.log(auditId)
    selectedAuditId.value = auditId
    drawerVisible.value = true;
    // 确保对话框打开时，已经设置了选中的审计ID
    await nextTick();
    formDrawerRef.value?.open();
}

const handleFormClose = () => {
    //formDrawerRef.value?.close();
};
getLists()
</script>


<style scoped>
.link {
    color: #409eff;
    /* Element Plus 默认的主题色 */
    text-decoration: none;
    margin-top: -12px;
}

.link:hover {
    color: #66b1ff;
    /* 鼠标悬停时颜色 */
    /* text-decoration: underline;
    添加下划线 */
}

.el-table__cell .el-button {
    padding-bottom: 4px;
}
</style>