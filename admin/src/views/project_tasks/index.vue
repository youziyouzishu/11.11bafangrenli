<template>
    <PermissionWrapper>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="" prop="project_name">
                    <el-input class="w-[280px]" v-model="queryParams.project_name" clearable placeholder="项目名称模糊搜索" />
                </el-form-item>
                <el-form-item label="" prop="project_id">
                    <!-- 状态筛选器 -->
                    <el-select v-model="queryParams.is_show" class="w-[140px]" placeholder="筛选状态" @change="filterTable">
                        <el-option v-for="item in statusOptions" :key="item.value" :label="item.label"
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
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['project_tasks/add']" type="primary" @click="handleAdd">
                <template #icon>
                    <icon name="el-icon-Plus" />
                </template>
                发布
            </el-button>
            <el-button v-perms="['project_tasks/close']" type="danger" :disabled="!selectData.length"
                @click="handleClose(selectData)">
                关闭
            </el-button>

            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" :selectable="rowSelectable" fixed="left" />
                    <el-table-column label="项目名称" prop="project_name" width="160" show-overflow-tooltip fixed="left" />
                    <el-table-column label="状态" prop="is_show" width="115" show-overflow-tooltip>
                        <template #default="{ row }">
                            <el-tag type="waring" v-if="row.is_show == 0">待审核</el-tag>
                            <el-tag type="success" v-if="row.is_show == 2">审核通过</el-tag>
                            <el-tag type="danger" v-if="row.is_show == 1">已关闭</el-tag>
                            <div class="remarks"> <el-tooltip v-if="row.is_show == 3" effect="dark"
                                    :content="row.show_remarks" placement="top">
                                    <el-tag type="danger">
                                        拒绝
                                        <i class="el-icon-info"></i>
                                    </el-tag>

                                </el-tooltip></div>


                        </template>
                    </el-table-column>
                    <el-table-column label="招聘成本" prop="salary_range" show-overflow-tooltip>
                        <template #default="{ row }">

                            {{ row.recruitment_settlement_value
                            }} 元
                        </template>
                    </el-table-column>

                    <el-table-column label="驻场成本" prop="site_settlement_value" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.site_settlement_value
                            }} 元
                        </template>
                    </el-table-column>
                    <el-table-column label="薪资范围" prop="salary_range" show-overflow-tooltip>
                        <template #default="{ row }">
                            {{ row.is_salary == 2 ? row.salary_range + " ~ " + row.salary_range_end + " K / 月" :
                "面议" }}
                        </template>
                    </el-table-column>
                    <el-table-column label="人力公司" prop="org_name" width="150" show-overflow-tooltip />
                    <el-table-column label="上岗城市" prop="up_city" show-overflow-tooltip width="150">
                        <template #default="{ row }">
                            {{ getCityLabel(row.up_city) }}
                        </template>
                    </el-table-column>
                    <el-table-column label="阅读数" prop="click_actual" show-overflow-tooltip />
                    <el-table-column label="发布日期" prop="recruitment_start_time" width="205" show-overflow-tooltip />
                    <el-table-column label="综合评分" prop="project_score" width="135">
                        <template #default="{ row }">
                            <el-form-item label="" prop="score" style="margin-top: 17px;">
                                <el-rate v-model="row.project_score" :max="5" :disabled="true" />
                            </el-form-item>
                        </template>
                    </el-table-column>


                    <el-table-column label="操作" width="160" fixed="left">
                        <template #default="{ row }">
                            <el-button v-perms="['project_tasks/edit']" type="primary" @click.parent.stop link>
                                <router-link :to="{
                path: getRoutePath('project_tasks_audit/index'),
                query: {
                    project_id: row.id,
                    type: '2',
                },
            }">
                                    管理

                                </router-link>
                            </el-button>
                            <el-button :disabled="row.is_show == 1" v-perms="['project_tasks/edit']" type="primary" link
                                @click="handleEdit(row)">
                                编辑
                            </el-button>
                            <!-- <el-button :disabled="row.is_show == 1" v-perms="['project_tasks/close']" type="danger" link
                                @click="handleClose(row.id)">
                                关闭
                            </el-button> -->
                            <AuditPopup :id="row.id" :contract_url="row.cooperative_contract" :org_name="row.org_name"
                                :buttonDisabled="!selectData.length" @audit-success="getLists" />
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

<script lang="ts" setup name="projectTasksLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiProjectTasksLists, apiProjectTasksDelete, apiProjectTasksClose } from '@/api/project_tasks'
import { timeFormat } from '@/utils/util'
import { getRoutePath } from "@/router";
import feedback from '@/utils/feedback'
import EditPopup from './edit.vue'
import AuditPopup from './audit.vue'
import PermissionWrapper from '@/components/auth.vue';
import { time } from 'echarts/core'
import { regionData, codeToText } from 'element-china-area-data'

const editRef = shallowRef<InstanceType<typeof EditPopup>>()

const auditPopupRef = ref(null);


// 是否显示编辑框
const showEdit = ref(false)
const showAudit = ref(false)
const auditResult = ref("pass")

// 状态选项
const statusOptions = [
    { label: '全部', value: null },
    { label: '待审核', value: 0 },
    { label: '关闭', value: 1 },
    { label: '通过', value: 2 },
    { label: '拒绝', value: 3 },
];

// 计算属性，用于过滤三级数据
// 将嵌套的三级数据转为扁平化的二级数据
const flattenedRegionData = computed(() => {
    const flattenData = [];

    regionData.forEach(province => {
        province.children.forEach(city => {
            flattenData.push({
                ...city,
                label: `${province.label}/${city.label}`
            });
        });
    });

    return flattenData;
});

const getCityLabel = (cityValue: string) => {
    const city = flattenedRegionData.value.find(city => city.value === cityValue);
    return city ? city.label : '';
}
// 查询条件
const queryParams = reactive({
    project_name: '',
    task_description: '',
    is_show: ''
})


// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}
const handleChange = (value: any[]) => {
    // 如果当前选择了'不限'，则清空其他已选择的选项
    if (value.includes('unlimited') && value.length > 1) {
        queryParams.project_name.value = ['unlimited'];
    } else if (value.length === 0 || !value.includes('unlimited')) {
        // 如果未选择'不限'，则可以正常进行多选
        value = value;
    }
}

// 获取字典数据
const { dictData } = useDictData('education,experience,welfare')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiProjectTasksLists,
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

// 关闭
const handleClose = async (id: number | any[]) => {
    await feedback.confirm('确定要关闭招聘项目？')
    await apiProjectTasksClose({ id })
    getLists()
}

// 审核
const handleAudit = async () => {
    showAudit.value = true

}

// 删除
const handleDelete = async (id: number | any[]) => {
    await feedback.confirm('确定要删除项目？')
    await apiProjectTasksDelete({ id })
    getLists()
}
// 是否可选中
const rowSelectable = (row: any, index: number): boolean => {
    return row.is_show !== 1;
}

getLists()
</script>

<style scoped>
.remarks .el-tag {
    cursor: help !important;
    /* 设置鼠标样式为默认 */

    /* 禁用元素的鼠标事件 */
}
</style>