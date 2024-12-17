<template>
    <div class="bg-white render-html p-[30px] w-[1200px]  mx-auto min-h-screen">
        <h1 class="text-center m-[30px]">{{ data.title }}</h1>
        <div class="mx-auto" v-html="data.content"></div>
    </div>
</template>
<script lang="ts" setup>
import { getPolicy } from '@/api/app'
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
// 使用 useRoute 钩子获取路由参数
const route = useRoute();

const data = ref({ title: '', content: '' });


const fetchPolicy = async () => {
    try {
        const response = await getPolicy({
            type: route.params.type as string,
        });
        data.value = response;
    } catch (error) {
        console.error('Error fetching policy:', error);
    }
};

onMounted(fetchPolicy);


</script>
<style lang="scss" scoped>
.render-html h1,
.render-html h2,
.render-html h3,
.render-html h4,
.render-html h5 {
    font-weight: 700;
}

.render-html h1 {
    font-size: 2em;
}

.render-html {
    overflow-y: scroll;
    /* 允许垂直滚动条 */
    height: 100vh;
    width: auto;
    /* 或其他高度值 */
}
</style>
