<template>
    <Head title="Registro diario de sesiones"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">

                <!-- Titulo y descripción del modulo -->
                <div class="px-6 pt-4 pb-1 mb-4">
                    <CardTitle class="text-2xl">Registro diario de sesiones</CardTitle>
                    <CardDescription class="text-base">Aquí puedes gestionar el registro diario de sesiones de los usuarios.</CardDescription>
                </div>

                <!-- Boton para crear, filtro y exportaciones -->
                 <div class="flex flex-wrap justify-between items-center mb-4 px-6 mt-4 gap-2">
                    <Button @click="handleOpenModalCreate">Nuevo registro</Button>
                    <div class="flex items-center gap-2">
                        <FilterDailySessiongLogs
                            :payment-methods="paymentMethods"
                            @filter="handleFilter" />
                    </div>
                 </div>

                 <div class="mb-4 px-6 py-2">
                    <TableDailySessionLogs
                        :dailySessionLogs="dailySessionLogs"
                        :pagination="pagination"
                        :loading="loading"
                        @page-change="handlePageChange"
                        @open-modal-edit="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                        />

                    <ModalDailySessionLogs
                        :status-modal="modals.createEdit"
                        :dailySessionLog="dailySessionLog"
                        :payment-methods="paymentMethods"
                        @close-modal="handleCloseModalCreate"
                        @create="handleCreate"
                        @update="handleUpdate"
                        />
                 </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import TableDailySessionLogs from './components/tableDailySessionLogs.vue';
import { useDailySessionLog } from '@/composables/useDailySessionLog';
import { storeDailySessionLogRequest, updateDailySessionLogRequest } from './interfaces/DailySessionLog';
import { onMounted } from 'vue';
import { ref } from 'vue';
import ModalDailySessionLogs from './components/modalDailySessionLogs.vue';
import FilterDailySessiongLogs from './components/filterDailySessionLogs.vue';

const breadcrumbs = [
    {
        title: 'Registro diario de sesiones',
        href: '/panel/dailySessionLogs',
    },
];

const {
    dailySessionLog,
    getDailySessionLogs,
    pagination,
    loading,
    deleteDailySessionLog,
    modals,
    getDailySessionLogById,
    dailySessionLogs,
    openModal,
    closeModal,
    storeDailySessionLog,
    updateDailySessionLog,
    getPaymentMethods, // ✅ ya puedes usar esta función
    paymentMethods,    // ✅ ya puedes pasarla al modal
} = useDailySessionLog();

const filter = ref<{ payment_method_id?: number; start_date?: string; end_date?: string }>({});

const handleFilter = (newFilter: typeof filter.value) => {
  filter.value = newFilter;
  getDailySessionLogs(1, filter.value);
};

    // function to handle modal create
    const handleOpenModalCreate = () => {
        openModal('createEdit');
    };

    const handleCloseModalCreate = () => {
        closeModal('createEdit');
    };

    // function to handle page change
    const handlePageChange = (page: number) => {
        getDailySessionLogs(page);
    };

    const handleOpenModalUpdate = (dailySessionLogId: number) => {
        console.log('update: ' + dailySessionLogId);
        getDailySessionLogById(dailySessionLogId)
    };

    const handleOpenModalDelete = (dailySessionLogId: number) => {
        console.log('delete: ' + dailySessionLogId);
        getDailySessionLogById(dailySessionLogId);
    };

    const handleCreate = (data: storeDailySessionLogRequest) => {
        storeDailySessionLog(data);
    };

    const handleUpdate = (data: updateDailySessionLogRequest) => {
        updateDailySessionLog(data);
    };

    onMounted(() => {
        getDailySessionLogs(1);
        getPaymentMethods();
    });

</script>

<style scoped lang="scss">

</style>