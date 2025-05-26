<template>
    <Head title="Locales" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="mt-4 mb-2 px-6">
                    <FilterLocals @search="handleSearch" />
                </div>
                <div class="mb-4 px-6 py-2">
                    <TableLocals
                        :locals="locals"
                        :pagination="safePagination"
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
import { useLocal } from '@/composables/useLocal';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import FilterLocals from './components/filterLocals.vue';
import TableLocals from './components/tableLocals.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Locales',
        href: '/panel/locals',
    },
];

const { locals, getLocals, pagination, loading } = useLocal();

// Provide a default pagination object to avoid undefined
const safePagination = computed(
    () =>
        pagination.value ?? {
            total: 0,
            current_page: 1,
            per_page: 10,
            last_page: 1,
            from: 0,
            to: 0,
        },
);

const handleSearch = (searchText: string) => {
    getLocals(1, searchText);
};

const handlePageChange = (page: number) => {
    getLocals(page);
};
const handleOpenModalUpdate = (local_id: number) => {
    console.log('update: ' + local_id);
};
const handleOpenModalDelete = (local_id: number) => {
    console.log('delete: ' + local_id);
};

onMounted(() => {
    getLocals();
});
</script>
<style scoped></style>
