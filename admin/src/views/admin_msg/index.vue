<template>
    <div>
        <el-card class="!border-none">
            <div>
                <el-tabs v-if="notifications.length > 0" v-model="queryParams.msg_type" @tab-change="getLists">
                    <el-tab-pane v-for="(item, index) in notifications" :key="index"
                        :label="item.name + `(` + item.count + `)`" :name="item.type" lazy></el-tab-pane>
                </el-tabs>
                <el-button v-perms="['admin_msg/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                    标记已读
                </el-button>
                <el-button v-perms="['admin_msg/delete']" :disabled="!selectData.length" @click="handleDelete(selectData)">
                    <el-checkbox v-model="queryParams.review_time" @change="cgReview">未读</el-checkbox>
                </el-button>

                <el-table class="mt-4" :data="pager.lists" v-loading="pager.loading"
                    @selection-change="handleSelectionChange" @row-click="handleRowClick" row-class-name="clickable-row">
                    <el-table-column type="selection" width="55" :selectable="rowSelectable" />
                    <el-table-column label="消息详情" prop="review_time">
                        <template #default="{ row }">
                            <div>
                                <el-badge is-dot class="msg_item" v-if="row.review_time === 0"> </el-badge>
                                <span class="message-absolute-text">
                                    {{ row.title }}
                                </span>
                                <!-- <div class="red-dot" v-if="row.review_time === 0"></div>
                                <span class="message-absolute-text">
                                    {{ row.title }}
                                </span> -->

                                <br>
                                <div class="notification-describe ">{{ extractChineseAndPunctuation(row.message) }}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="消息时间" width="300" prop="create_time">
                        <template #default="{ row }">
                            <span>{{ row.create_time ? timeFormat(row.create_time, 'yyyy-mm-dd hh:MM:ss') : '' }}</span>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
        <div class="flex mt-4 justify-end">
            <pagination v-model="pager" @change="getLists" />
        </div>
    </div>
    <drawer ref="popupRef" title="消息详情" :async="true" width="50%" @close="handleClose">
        <div v-if="selectedRow">
            <h2 style="margin: 8px 0;font-weight: 600; font-size: 16px;line-height: 24px;color: #323335;">{{
                selectedRow.title }}</h2>
            <p style="margin-bottom: 24px;font-size: 14px; line-height: 20px;color: #969aa0;">
                {{ selectedRow.create_time ? timeFormat(selectedRow.create_time, 'yyyy-mm-ddhh: MM: ss') : '' }}
            </p>

            <div v-html="selectedRow.message"></div>
        </div>
    </drawer>
</template>

<script lang="ts" setup name="adminMsgLists">
import { usePaging } from '@/hooks/usePaging'
import { useDictData } from '@/hooks/useDictOptions'
import { apiAdminMsgLists, apiAdminMsgReview, apiAdminMsgNotificationList } from '@/api/admin_msg'
import { timeFormat } from '@/utils/util'
import Drawer from '@/components/drawer/index.vue'
import feedback from '@/utils/feedback'


const emit = defineEmits(['close'])
const popupRef = shallowRef<InstanceType<typeof Drawer>>()
const mode = ref('add')

const drawerVisible = ref(false);
const selectedRow = ref(null);
const notifications = ref([]);

onMounted(async () => {
    // Pre-load apiAdminMsgNotificationList
    notifications.value = await apiAdminMsgNotificationList();

});
const cgReview = (val) => {
    if (val == false) {
        queryParams.review_time = '';
    }
    getLists()
}
const handleRowClick = async (row) => {
    console.log(row, drawerVisible.value);
    selectedRow.value = row;
    drawerVisible.value = true;
    popupRef.value?.open()
    if (row.review_time === 0) {
        await apiAdminMsgReview({ id: row.id })
        notifications.value = await apiAdminMsgNotificationList();
        getLists()

    }
};

const extractChineseAndPunctuation = (text) => {
    // 正则表达式匹配中文字符及中文标点符号
    const regex = /[\u4e00-\u9fa5，。！？；：“”（）、—【】《》]/g;
    // 执行匹配操作
    let matches = text.match(regex);
    // 将匹配到的结果拼接成字符串返回
    return matches ? matches.join('') : '';
}

enum NoticeEnums {
    USER = 1,
    PLATFORM = 2
}

const tabsActive = ref(NoticeEnums.USER)
const tabsMap = [
    {
        name: '全部',
        type: ""
    },
    {
        name: '邀请&权限',
        type: "1"
    },
    {
        name: '审核信息',
        type: "2"
    },
    {
        name: '财务消息',
        type: "3"
    },
    {
        name: '日报消息',
        type: "4"
    },
    {
        name: '周报消息',
        type: "5"
    },
    {
        name: '功能上线',
        type: "6"
    },
    {
        name: '公告通知',
        type: "7"
    },
]

// 查询条件
const queryParams = reactive({
    user_id: '',
    msg_type: '',
    title: '',
    message: '',
    review_time: '',
    create_time: '',
    start_time: '',
    end_time: ''
})

// 选中数据
const selectData = ref<any[]>([])

// 表格选择后回调事件
const handleSelectionChange = (val: any[]) => {
    selectData.value = val.map(({ id }) => id)
}

// 获取字典数据
const { dictData } = useDictData('msg_type')

// 分页相关
const { pager, getLists, resetParams, resetPage } = usePaging({
    fetchFun: apiAdminMsgLists,
    params: queryParams,
    size: 10
})

// 是否可选中
const rowSelectable = (row: any, index: number): boolean => {
    return row.review_time === 0;
}


// 关闭回调
const handleClose = () => {
    emit('close')
}
getLists()
</script>


<style >
.clickable-row {
    cursor: pointer;
}

.notification-describe {
    width: 100%;

    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: 12px;
    line-height: 20px;
    padding-left: 10px;
    color: #969aa0;
}

.red-dot {
    width: 3px;
    height: 3px;
    background-color: red;
    border-radius: 10%;
    margin-right: 2px;
    display: inline-block;
    /* display: inline-block;
    margin-right: -5px; */
    /* 添加一些右边距，使得红点和标题之间有一些空间 */
}

.message-absolute-text {
    margin-right: 4px;
    max-width: calc(100% - 8px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    height: 20px;
    min-width: 52px;
    color: #323335;
    font-weight: 400;
    padding-left: 10px;
    margin-top: 4px;
}

.msg_item {
    margin-top: 8px;
    margin-right: -8px;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.2);
    }

    100% {
        transform: scale(1);
    }
}
</style>