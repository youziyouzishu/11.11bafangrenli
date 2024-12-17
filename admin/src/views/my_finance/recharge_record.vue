<template>
    <div>

        <el-card class="!border-none mb-4" shadow="never">

            <div class="flex flex-wrap">
                <div class="w-1/2 md:w-1/4">
                    <div class="leading-10">我的余额 (元)</div>
                    <div class="text-6xl">{{ balance }}</div>
                </div>
                <!-- <div class="w-1/2 md:w-1/4">
                    <div class="leading-10">当前总退款 (元)</div>
                    <div class="text-6xl">{{ '100.00' }}</div>
                </div> -->
                <div class="flex items-center">
                    <el-button type="primary" @click="showDialog" class="">充值</el-button>
                </div>
                <!-- <div class="w-1/2 md:w-1/4">
                    <div class="leading-10">提现中 (元)</div>
                    <div class="text-6xl">{{ 100.00 }}</div>
                </div> -->
            </div>

        </el-card>
        <el-card class="!border-none mt-4" shadow="never">

            <el-form ref="formRef" class="mb[-16px] mt-[16px] flex justify-between items-center" :model="queryParams"
                :inline="true">
                <!-- 左侧部分 -->
                <!-- <div class="flex items-center">
                    <el-button type="primary" @click="showDialog" class="mb-4">充值</el-button>
                </div> -->
                <!-- 右侧部分 -->
                <div class="flex items-center">
                    <el-form-item label="充值时间">
                        <daterange-picker v-model:startTime="queryParams.start_time"
                            v-model:endTime="queryParams.end_time" />
                    </el-form-item>
                    <el-form-item>

                        <el-button type="primary" @click="resetPage">查询</el-button>
                        <!-- <export-data class="ml-2.5" :fetch-fun="rechargeLists" :params="queryParams"
                            :page-size="pager.size" /> -->
                    </el-form-item>
                </div>

            </el-form>

            <el-table size="large" v-loading="pager.loading" :data="pager.lists">
                <el-table-column label="充值单号" prop="sn" min-width="190" />
                <el-table-column label="客户名称" prop="" min-width="300">
                    <template #default="{ row }">
                        {{ row.nickname }}
                    </template>
                </el-table-column>
                <el-table-column label="客户类型" prop="" min-width="100">
                    <template #default="{ row }">
                        {{ row.type == "ORG" ? "企业" : "个人" }}
                    </template>
                </el-table-column>
                <el-table-column label="充值金额" prop="order_amount" min-width="100">
                </el-table-column>
                <el-table-column label="支付方式" prop="pay_way_text" min-width="100" />
                <el-table-column label="支付状态" prop="" min-width="100">
                    <template #default="{ row }">
                        <span :class="{
                        'text-error': row.pay_status == 0
                    }">
                            {{ row.pay_status_text }}
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="提交时间" prop="create_time" min-width="180" />
                <el-table-column label="支付时间" prop="pay_time" min-width="180" />
                <el-table-column label="操作" width="120" fixed="right">
                    <template #default="{ row }">
                        <el-button v-perms="['recharge.recharge/refund']" type="primary" link
                            :disabled="row.pay_status != 1 || row.refund_status == 1" @click="handleRefund(row.id)">
                            退款
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-4">
                <pagination v-model="pager" @change="getLists" />
            </div>

        </el-card>

    </div>
    <PaymentDialog v-model:dialogVisible="isDialogVisible" />
</template>
<script lang="ts" setup name="articleLists">
import { rechargeLists, refund } from '@/api/finance'
import { usePaging } from '@/hooks/usePaging'
import feedback from '@/utils/feedback'
import PaymentDialog from '@/components/pay/pay.vue';
import { ref } from 'vue';
import { getBalance } from '@/api/perms/admin'
const queryParams = reactive({
    sn: '',
    user_info: '',
    pay_way: '',
    pay_status: '',
    start_time: '',
    end_time: ''
})

// 控制 PaymentDialog 显示状态
const isDialogVisible = ref(false);

// 打开 PaymentDialog
const showDialog = () => {
    isDialogVisible.value = true;
};
const { pager, getLists, resetPage, resetParams } = usePaging({
    fetchFun: rechargeLists,
    params: queryParams
})
const handleRefund = async (id: number) => {
    await feedback.confirm('确认重新退款？')
    await refund({
        recharge_id: id
    })
    getLists()
}

getLists()

const balance = ref(null);
const getBalanceApi = async () => {
    const data = await getBalance()
    balance.value = data.user_money
    console.log(data)
}
onMounted(() => {
    getBalanceApi();
});
</script>
