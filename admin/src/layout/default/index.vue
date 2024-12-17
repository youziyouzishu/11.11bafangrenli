<template>
    <div class="layout-default flex h-screen w-full">
        <!-- <button @click="requestNotificationPermission">启用通知</button> -->
        <div class="app-aside">
            <layout-sidebar />
        </div>

        <div class="flex-1 flex flex-col min-w-0">
            <div class="app-header">
                <layout-header />
            </div>
            <div class="app-main flex-1 min-h-0">
                <el-alert type="warning" v-if="false" show-icon>
                    <template v-slot:title>
                        <div class="container">
                            <div class="left">不可关闭的 alert</div>
                            <div class="right"><el-pagination @size-change="handleSizeChange" class="custom_pagination"
                                    @current-change="handleCurrentChange" :current-page="currentPage" :page-sizes="[1]"
                                    :page-size="pageSize" layout=" prev,slot, next" :total="total">
                                    <template v-slot:default>
                                        <!-- 自定义内容 -->
                                        {{ currentPage }}/{{ total }}
                                    </template>
                                </el-pagination></div>
                        </div>
                    </template>

                </el-alert>


                <layout-main />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import LayoutMain from './components/main.vue'
import LayoutSidebar from './components/sidebar/index.vue'
import LayoutHeader from './components/header/index.vue'
const item = ref([1, 2, 3, 4])

const currentPage = ref(1) // 当前页码
const pageSize = ref(1)   // 每页显示条目个数
const total = ref(1000)     // 总条目数

const handleSizeChange = (val: number) => {
    // 当每页显示条目个数变化时会触发
    pageSize.value = val;
    // 这里可以执行相关的操作，比如重新加载数据等
}
const handleCurrentChange = (val: number) => {
    // 当前页码改变时会触发
    currentPage.value = val;
    // 这里可以执行相关的操作，比如重新加载数据等
}
</script>

<style lang="scss">
.custom_pagination {
    height: 10px;
    //float: right;
    /* 右浮动 */
}

.custom_pagination button:disabled {
    background: var(--el-alert-bg-color);
}

.custom_pagination button {
    color: var(--el-text-color-primary);
    background: var(--el-alert-bg-color);
}

.container {
    color: var(--el-text-color-regular);
    justify-content: space-between;
    display: flex;
}

.left {


    flex: 10;
}

.right {
    margin-top: 4px;
    flex: 1;
    /* 子元素各自占据相同的空间 */
}

.el-alert__content {
    width: 100%;
}
</style>