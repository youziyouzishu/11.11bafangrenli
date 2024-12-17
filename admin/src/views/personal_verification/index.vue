<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form
                class="mb-[-16px]"
                :model="queryParams"
                inline
            >
                <el-form-item label="用户ID" prop="user_id">
                    <el-input class="w-[280px]" v-model="queryParams.user_id" clearable placeholder="请输入用户ID" />
                </el-form-item>
                <el-form-item label="用户信息授权状态" prop="authorize_user_info">
                    <el-input class="w-[280px]" v-model="queryParams.authorize_user_info" clearable placeholder="请输入用户信息授权状态" />
                </el-form-item>
                <el-form-item label="实名状态: 0-未实名, 1-已实名" prop="realname_status">
                    <el-input class="w-[280px]" v-model="queryParams.realname_status" clearable placeholder="请输入实名状态: 0-未实名, 1-已实名" />
                </el-form-item>
                <el-form-item label="个人ID" prop="psn_id">
                    <el-input class="w-[280px]" v-model="queryParams.psn_id" clearable placeholder="请输入个人ID" />
                </el-form-item>
                <el-form-item label="账户手机号" prop="account_mobile">
                    <el-input class="w-[280px]" v-model="queryParams.account_mobile" clearable placeholder="请输入账户手机号" />
                </el-form-item>
                <el-form-item label="账户电子邮箱" prop="account_email">
                    <el-input class="w-[280px]" v-model="queryParams.account_email" clearable placeholder="请输入账户电子邮箱" />
                </el-form-item>
                <el-form-item label="个人姓名" prop="psn_name">
                    <el-input class="w-[280px]" v-model="queryParams.psn_name" clearable placeholder="请输入个人姓名" />
                </el-form-item>
                <el-form-item label="国籍" prop="psn_nationality">
                    <el-input class="w-[280px]" v-model="queryParams.psn_nationality" clearable placeholder="请输入国籍" />
                </el-form-item>
                <el-form-item label="身份证号码" prop="psn_id_card_num">
                    <el-input class="w-[280px]" v-model="queryParams.psn_id_card_num" clearable placeholder="请输入身份证号码" />
                </el-form-item>
                <el-form-item label="证件类型" prop="psn_id_card_type">
                    <el-input class="w-[280px]" v-model="queryParams.psn_id_card_type" clearable placeholder="请输入证件类型" />
                </el-form-item>
                <el-form-item label="银行卡号" prop="bank_card_num">
                    <el-input class="w-[280px]" v-model="queryParams.bank_card_num" clearable placeholder="请输入银行卡号" />
                </el-form-item>
                <el-form-item label="手机号" prop="psn_mobile">
                    <el-input class="w-[280px]" v-model="queryParams.psn_mobile" clearable placeholder="请输入手机号" />
                </el-form-item>
                <el-form-item label="创建时间" prop="created_at">
                    <el-input class="w-[280px]" v-model="queryParams.created_at" clearable placeholder="请输入创建时间" />
                </el-form-item>
                <el-form-item label="更新时间" prop="updated_at">
                    <el-input class="w-[280px]" v-model="queryParams.updated_at" clearable placeholder="请输入更新时间" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['personal_verification/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <el-button
                v-perms="['personal_verification/delete']"
                :disabled="!selectData.length"
                @click="handleDelete(selectData)"
            >
                删除
            </el-button>
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="用户ID" prop="user_id" show-overflow-tooltip />
                    <el-table-column label="用户信息授权状态" prop="authorize_user_info" show-overflow-tooltip />
                    <el-table-column label="实名状态: 0-未实名, 1-已实名" prop="realname_status" show-overflow-tooltip />
                    <el-table-column label="个人ID" prop="psn_id" show-overflow-tooltip />
                    <el-table-column label="账户手机号" prop="account_mobile" show-overflow-tooltip />
                    <el-table-column label="账户电子邮箱" prop="account_email" show-overflow-tooltip />
                    <el-table-column label="个人姓名" prop="psn_name" show-overflow-tooltip />
                    <el-table-column label="国籍" prop="psn_nationality" show-overflow-tooltip />
                    <el-table-column label="身份证号码" prop="psn_id_card_num" show-overflow-tooltip />
                    <el-table-column label="证件类型" prop="psn_id_card_type" show-overflow-tooltip />
                    <el-table-column label="银行卡号" prop="bank_card_num" show-overflow-tooltip />
                    <el-table-column label="手机号" prop="psn_mobile" show-overflow-tooltip />
                    <el-table-column label="创建时间" prop="created_at" show-overflow-tooltip />
                    <el-table-column label="更新时间" prop="updated_at" show-overflow-tooltip />
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                             <el-button
                                v-perms="['personal_verification/edit']"
                                type="primary"
                                link
                                @click="handleEdit(row)"
                            >
                                编辑
                            </el-button>
                            <el-button
                                v-perms="['personal_verification/delete']"
                                type="danger"
                                link
                                @click="handleDelete(row.id)"
                            >
                                删除
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <div class="flex mt-4 justify-end">
                <pagination v-model="pager" @change="getLists" />
            </div>
        </el-card>
        <edit-popup v-if="showEdit" ref="editRef" :dict-data="dictData" @success="getLists" @close="showEdit = false" />
    </div>
</template>

<script lang="ts" setup name="personalVerificationLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiPersonalVerificationLists, apiPersonalVerificationDelete } from '@/api/personal_verification'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
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
    updated_at: ''
})

// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

// 获取字典数据
const { dictData } = useDictData('')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiPersonalVerificationLists,
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
    await apiPersonalVerificationDelete({ id })
    getLists()
}

getLists()
</script>

