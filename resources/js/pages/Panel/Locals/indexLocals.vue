<template>
    <Head title="Locales" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="mt-4 mb-2 px-6">
                    <FilterLocals @search="handleSearch" @open-modal-create="handleOpenModalCreate" />
                </div>
                <div class="mb-4 px-6 py-2">
                    <TableLocals
                        :locals="locals"
                        :pagination="pagination"
                        :loading="loading"
                        @page-change="handlePageChange"
                        @open-modal-edit="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                    />
                    <ModalLocals
                        :status-modal="modals.createEdit"
                        @close-modal="handleCloseModalCreate"
                        :local="local"
                        @create="handleCreate"
                        @update="handleUpdate"
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
import { onMounted } from 'vue';
import FilterLocals from './components/filterLocals.vue';
import ModalLocals from './components/modalLocals.vue';
import TableLocals from './components/tableLocals.vue';
import { storeLocalRequest, updateLocalRequest } from './interfaces/Local';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Locales',
        href: '/panel/locals',
    },
];

const { locals, getLocals, pagination, loading, deleteLocal, modals, getLocalById, local, openModal, closeModal, storeLocal, updateLocal } =
    useLocal();

const handleSearch = (searchText: string) => {
    getLocals(1, searchText);
};

const handleOpenModalCreate = () => {
    openModal('createEdit');
};
const handleCloseModalCreate = () => {
    closeModal('createEdit');
};
const handlePageChange = (page: number) => {
    getLocals(page);
};
const handleOpenModalUpdate = (local_id: number) => {
    getLocalById(local_id);
};
const handleOpenModalDelete = (local_id: number) => {
    deleteLocal(local_id);
};

const handleCreate = (data: storeLocalRequest) => {
    storeLocal(data);
};
const handleUpdate = (data: updateLocalRequest) => {
    updateLocal(data.id, data);
};

onMounted(() => {
    getLocals();
});
</script>
<style scoped></style>
