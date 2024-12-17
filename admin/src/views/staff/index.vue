<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form
                class="mb-[-16px]"
                :model="queryParams"
                inline
            >
                <el-form-item label="业务员名称" prop="name">
    <el-input class="w-[280px]" v-model="queryParams.name" clearable placeholder="请输入业务员名称" />
</el-form-item>
                <el-form-item label="业务员邀请码" prop="invitecode">
    <el-input class="w-[280px]" v-model="queryParams.invitecode" clearable placeholder="请输入业务员邀请码" />
</el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['staff/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                新增
            </el-button>
            <el-button
                v-perms="['staff/delete']"
                :disabled="!selectData.length"
                @click="handleDelete(selectData)"
            >
                删除
            </el-button>
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="所属业务公司" prop="admin_id" show-overflow-tooltip />
                    <el-table-column label="业务员名称" prop="name" show-overflow-tooltip />
                  <el-table-column label="业务员邀请码" prop="invitecode" show-overflow-tooltip/>
                  <el-table-column label="邀请码" prop="base64" show-overflow-tooltip >
                    <template #default="{ row }">
                      <el-image
                          style="width: 100px; height: 100px"
                          :src="row.base64"
                          :preview-src-list=[row.base64]>
                      </el-image>
                    </template>
                  </el-table-column>

                  <el-table-column label="操作" width="300" fixed="right">
                        <template #default="{ row }">
                          <el-button
                              v-perms="['staff/staff_layer']"
                              type="primary"
                              link
                              @click="handleLayer(row)"
                          >
                            邀请列表
                          </el-button>
                             <el-button
                                v-perms="['staff/edit']"
                                type="primary"
                                link
                                @click="handleEdit(row)"
                            >
                                编辑
                            </el-button>
                            <el-button
                                v-perms="['staff/delete']"
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

<script lang="ts" setup name="staffLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiStaffLists, apiStaffDelete } from '@/api/staff'
import { useRouter } from 'vue-router';

import { timeFormat } from '@/utils/util'
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()

const router = useRouter()


// 是否显示编辑框
const showEdit = ref(false)


// 查询条件
const queryParams = reactive({
    admin_id: '',
    name: '',
    invitecode: '',
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
    fetchFun: apiStaffLists,
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

const handleLayer = async (row: any) => {
  router.push({ path: '/business/staff_layer', query: { staff_id: row.id }});
}

// 删除
const handleDelete = async (id: number | any[]) => {
    await feedback.confirm('确定要删除？')
    await apiStaffDelete({ id })
    getLists()
}

getLists()
</script>

