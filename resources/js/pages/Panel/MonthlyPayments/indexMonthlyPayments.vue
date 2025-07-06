<template>
    <!-- filtro -->
    <!-- tabla -->
    <Head title="meses pagos"></Head>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <TableMonthlyPayments :monthly-payments="monthlyPayments" class="m-5" />
            </div>
        </div>
    </AppLayout>

    <!-- modales -->
</template>

<script setup lang="ts">
import { useMonthlyPayment } from '@/composables/useMonthlyPayment';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import TableMonthlyPayments from './components/tableMonthlyPayments.vue';

const { deleteMonthlyPayment, getMonthlyPayments, monthlyPayments } = useMonthlyPayment();

onMounted(() => {
    getMonthlyPayments();
});
</script>

<style scoped lang="css"></style>
