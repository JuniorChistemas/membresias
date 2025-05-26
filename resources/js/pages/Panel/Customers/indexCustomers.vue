<template>
    <Head title="Clientes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="mt-4 mb-2 px-6">
                    <FilterCustomers @search="handleSearch" />
                </div>
                <div class="mb-4 px-6 py-2">
                    <TableCustomers
                        :customers="customers"
                        :pagination="pagination"
                        :loading="loading"
                        @page-change="handlePageChange"
                        @open-modal-edit="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useCustomer } from '@/composables/useCustomer';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import FilterCustomers from './components/filterCustomers.vue';
import TableCustomers from './components/tableCustomers.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Exportar PDF',
        href: '#',
    },
    {
        title: 'Exportar Excel',
        href: '#',
    },
    {
        title: 'Importar CSV',
        href: '#',
    },
];

const { customers, getCustomers, pagination, loading } = useCustomer();

// funtion to handle search
const handleSearch = (searchText: string) => {
    getCustomers(1, searchText);
};

// funtion to handle page change
const handlePageChange = (page: number) => {
    getCustomers(page);
};
const handleOpenModalUpdate = (customer_id: number) => {
    console.log('update: ' + customer_id);
};
const handleOpenModalDelete = (customer_id: number) => {
    console.log('delete: ' + customer_id);
};

onMounted(() => {
    getCustomers();
});
</script>
<style scoped></style>
