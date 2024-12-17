<template>
    <div>
        <el-card class="!border-none mb-4" shadow="never">
            <el-form class="mb-[-16px]" :model="queryParams" inline>
                <el-form-item label="任务审计ID" prop="task_audit_id">
                    <el-input class="w-[280px]" v-model="queryParams.task_audit_id" clearable placeholder="请输入任务审计ID" />
                </el-form-item>
                <el-form-item label="" prop="project_id">
                    <el-select collapse-tags class="w-[280px]" v-model="queryParams.project_id" clearable
                        @change="resetPage" placeholder="筛选项目">
                        <el-option label="不限" value=""></el-option>
                        <el-option v-for=" (item, index) in projectNames.lists" :key="item.id"
                            :label="item.project_name" :value="String(item.id)" />
                    </el-select>
                </el-form-item>
                <el-form-item label="人力公司ID" prop="uid">
                    <el-select class="w-[280px]" v-model="queryParams.uid" clearable placeholder="请选择人力公司ID">
                        <el-option label="全部" value=""></el-option>
                        <!-- <el-option v-for="(item, index) in dictData" :key="index" :label="item.name"
                            :value="item.value" /> -->
                    </el-select>
                </el-form-item>
                <el-form-item label="报告日期" prop="report_date">
                    <daterange-picker :start-time="queryParams.start_time" :end-time="queryParams.end_time"
                        @update:start-time="val => queryParams.start_time = val"
                        @update:end-time="val => queryParams.end_time = val" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>
        <el-card class="!border-none" v-loading="pager.loading" shadow="never">
            <el-button v-perms="['project_daily_report/delete']" :disabled="!selectData.length"
                @click="handleDelete(selectData)">
                删除
            </el-button>
            <div class="mt-4">
                <el-table :data="pager.lists" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55" />
                    <el-table-column label="编号" prop="id" show-overflow-tooltip />
                    <el-table-column label="审核ID" prop="task_audit_id" show-overflow-tooltip />
                    <el-table-column label="人力公司" prop="hr.org_name" show-overflow-tooltip>
                        <!-- <template #default="{ row }">
                            <dict-value :options="dictData" :value="row.uid" />
                        </template> -->
                    </el-table-column>
                    <el-table-column label="报告日期" prop="report_date" show-overflow-tooltip />
                    <el-table-column label="项目" prop="project.project_name" show-overflow-tooltip />
                    <el-table-column label="员工" prop="work.psn_name" show-overflow-tooltip />
                    <el-table-column label="招聘专员" prop="recruit.psn_name" show-overflow-tooltip />
                    <el-table-column label="驻场经理" prop="onsite.psn_name" show-overflow-tooltip />
                    <el-table-column label="日薪" prop="daily_salary" show-overflow-tooltip />
                    <el-table-column label="操作" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button v-perms="['project_daily_report/delete']" type="danger" link
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
    </div>
</template>

<script lang="ts" setup name="projectDailyReportLists">
import { ref, reactive, shallowRef, nextTick } from 'vue';
import { usePaging } from '@/hooks/usePaging';
import { useDictData } from '@/hooks/useDictOptions';
import { apiProjectDailyReportLists, apiProjectDailyReportDelete } from '@/api/project_daily_report';
import feedback from '@/utils/feedback';
import EditPopup from './edit.vue';
import { apiProjectTasksLists } from '@/api/project_tasks'

const editRef = shallowRef<InstanceType<typeof EditPopup>>();
const showEdit = ref(false);

const queryParams = reactive({
    task_audit_id: '',
    project_id: '',
    uid: '',
    start_time: '',
    end_time: ''
});

const projectNames = ref({
    lists: []
})
const getapiProjectTasksLists = async () => {
    projectNames.value = await apiProjectTasksLists()

}
getapiProjectTasksLists()

const selectData = ref<any[]>([]);

const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id);
};

const { dictData } = useDictData('');

const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiProjectDailyReportLists,
    params: queryParams
});

const handleAdd = async () => {
    showEdit.value = true;
    await nextTick();
    editRef.value?.open('add');
};

const handleEdit = async (data: any) => {
    showEdit.value = true;
    await nextTick();
    editRef.value?.open('edit');
    editRef.value?.setFormData(data);
};

const handleDelete = async (id: number | any[]) => {
    await feedback.confirm('确定要删除？');
    await apiProjectDailyReportDelete({ id });
    getLists();
};

getLists();
</script>