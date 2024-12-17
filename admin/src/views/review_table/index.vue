<template>
    <PermissionWrapper>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="项目名称" prop="project_id" disabled>
                    <el-input class="w-[280px]" v-model="queryParams.project_name" clearable placeholder="请输入项目名称" />
                </el-form-item>

                <el-form-item label="评价内容" prop="review_content">
                    <el-input class="w-[280px]" v-model="queryParams.review_content" clearable placeholder="请输入评价内容" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">搜索</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <!-- <el-button v-perms="['review_table/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
新增
</el-button> -->
            <el-button v-perms="['review_table/delete']" :disabled="!selectData.length"
                @click="handleDelete(selectData)">
                删除
            </el-button>
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="编号ID" prop="id" width="70" show-overflow-tooltip />
                    <el-table-column label="项目名称" prop="project_name" width="105" show-overflow-tooltip />
                    <el-table-column label="评价类型" width="105" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ ClientRoleMap[row['reviewer_type']] }}
                        </template>
                    </el-table-column>

                    <el-table-column label="目标类型" width="105" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ ClientRoleMap[row['target_type']] }}
                        </template>
                    </el-table-column>
                    <el-table-column label="评价人" prop="reviewer_name" show-overflow-tooltip />
                    <el-table-column label="被评价人" prop="target_name" show-overflow-tooltip />
                    <el-table-column label="评分" prop="score" show-overflow-tooltip>
                        <template #default="{ row }">
                            <el-form-item label="" prop="score">
                                <el-rate v-model="row.score" :max="5" :disabled="true" />
                            </el-form-item>
                        </template>
                    </el-table-column>

                    <el-table-column label="评价内容" width="305">
                        <template #default="{ row }">
                            <overflow-tooltip class="w-60 m-4" :content="row['review_content']" />
                        </template>
                    </el-table-column>
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['review_table/edit']" type="primary" link @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <el-button v-perms="['review_table/delete']" type="danger" link
                                @click="handleDelete(row.id)">
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
    </PermissionWrapper>
</template>

<script lang="ts" setup name="reviewTableLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiReviewTableLists, apiReviewTableDelete } from '@/api/review_table'
import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import { ClientRoleMap } from "@/enums/appEnums";
import PermissionWrapper from '@/components/auth.vue';

const editRef = shallowRef<InstanceType<typeof EditPopup>>()
// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    project_id: '',
    reviewer_id: '',
    reviewer_type: '',
    target_type: '',
    target_id: '',
    score: '',
    review_content: ''
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
    fetchFun: apiReviewTableLists,
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
    await apiReviewTableDelete({ id })
    getLists()
}

getLists()
</script>
