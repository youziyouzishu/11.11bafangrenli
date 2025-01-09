<template>
  <template v-if="!route.meta?.hidden">
    <app-link v-if="!hasShowChild" :to="`${routePath}?${queryStr}`">
      <el-menu-item :index="routePath" style="position: relative;">
        <icon
            class="menu-item-icon"
            :size="16"
            v-if="routeMeta?.icon"
            :name="routeMeta?.icon"
        />
        <template #title>
          <span>{{ routeMeta?.title }}</span>
        </template>
        <div class="msg_tag" v-if="routePath=='/operate/project'&&noticeNum">
          {{noticeNum}}
        </div>
      </el-menu-item>
    </app-link>
    <el-sub-menu v-else :index="routePath" :popper-class="popperClass">
      <template #title>
        <icon
            class="menu-item-icon"
            :size="16"
            v-if="routeMeta?.icon"
            :name="routeMeta?.icon"
        />
        <span>{{ routeMeta?.title }}</span>
      </template>
      <menu-item
          v-for="item in route?.children"
          :key="resolvePath(item.path)"
          :route="item"
          :route-path="resolvePath(item.path)"
          :popper-class="popperClass"
      />
    </el-sub-menu>
  </template>
</template>

<script lang="ts" setup>
import {getNormalPath, objectToQuery} from '@/utils/util'
import {isExternal} from '@/utils/validate'
import { getNotice } from '@/api/message'

import type {RouteRecordRaw} from 'vue-router'

interface Props {
  route: RouteRecordRaw
  routePath: string
  popperClass: string
}

const props = defineProps<Props>()


// 项目管理定时器
let timerId = ref(null);
const timerCount = ref(0);

const startTimer = () => {
  timerId.value = setInterval(() => {
    timerCount.value++;
    // getNoticeNUm()
    console.log(`Called ${timerCount.value} times`);
  }, 5000);
};

const noticeNum = ref(0)
const getNoticeNUm = async () => {
  const res = await getNotice()
  noticeNum.value = res
}

// Start the timer when the component is mounted
onMounted(() => {
  startTimer();
});

// Clear the timer before the component is unmounted or page refreshed
onBeforeUnmount(() => {
  clearInterval(timerId.value);
});

// Optionally handle page refresh
window.addEventListener('beforeunload', () => {
  clearInterval(timerId.value);
});


const hasShowChild = computed(() => {
  const children: RouteRecordRaw[] = props.route.children ?? []
  return !!children.filter((item) => !item.meta?.hidden).length
})

const routeMeta = computed(() => {
  return props.route.meta
})

const resolvePath = (path: string) => {
  if (isExternal(path)) {
    return path
  }
  const newPath = getNormalPath(`${props.routePath}/${path}`)
  return newPath
}
const queryStr = computed<string>(() => {
  const query = props.route.meta?.query as string
  try {
    const queryObj = JSON.parse(query)
    return objectToQuery(queryObj)
  } catch (error) {
    // console.log(error)

    return query
  }
})
</script>
<style lang="scss" scoped>
.el-menu-item,
.el-sub-menu__title {
  .menu-item-icon {
    margin-right: 8px;
    width: var(--el-menu-icon-width);
    text-align: center;
    vertical-align: middle;
  }
}

.msg_tag {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: #fd0028;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

</style>
