<template>
    <Head title="Entrenadores"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">

                <!-- Titulo y descripción del modulo -->
                <div class="px-6 pt-4 pb-1 mb-4">
                    <CardTitle class="text-2xl">Registro de entrenadores</CardTitle>
                    <CardDescription class="text-base">Visualiza, crea y gestiona los entrenadores del sistema.</CardDescription>
                </div>

                <!-- Boton de crear, filtro y exportaciones -->
                <div class="flex flex-wrap justify-between items-center mb-4 px-6 mt-4 gap-2">
                    <Button @click="handleOpenModalCreate">Nuevo entrenador</Button>
                    <div class="flex items-center gap-2">
                        <FilterCoaches @search="handleSearch" />
                        <ToolsCoaches @import-success="getCoaches"/>
                    </div>
                </div>

                <div class="mb-4 px-6 py-2">
                    <TableCoaches
                        :coaches="coaches"
                        :pagination="pagination"
                        :loading="loading"
                        @page-change="handlePageChange"
                        @open-modal-edit="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                    />
                    <ModalCoaches
                        :status-modal="modals.createEdit"
                        @close-modal="handleCloseModalCreate"
                        :coach="coach"
                        @create="handleCreate"
                        @update="handleUpdate"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useCoach } from '@/composables/useCoach';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { storeCoachRequest, updateCoachRequest } from './interfaces/Coach';
import { onMounted } from 'vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import ToolsCoaches from './components/toolsCoaches.vue';
import FilterCoaches from '../../../components/filter.vue';
import Button from '@/components/ui/button/Button.vue';
import TableCoaches from './components/tableCoaches.vue';
import ModalCoaches from './components/modalCoaches.vue';

const breadcrumbs = [
    {
        title: 'Entrenadores',
        href: '/panel/coaches',
    },
];

const {
    coaches,
    getCoaches,
    pagination,
    loading,
    deleteCoach,
    modals,
    getCoachById,
    coach,
    openModal,
    closeModal,
    storeCoach,
    updateCoach,
} = useCoach();

// funtion to handle search
const handleSearch = (searchText: string) => {
    getCoaches(1, searchText);
};

// funtion to handle modal create
const handleOpenModalCreate = () => {
    openModal('createEdit');
};

const handleCloseModalCreate = () => {
    closeModal('createEdit');
};

// funtion to handle page change
const handlePageChange = (page: number) => {
    getCoaches(page);
};

const handleOpenModalUpdate = (customer_id: number) => {
    console.log('update: ' + customer_id);
    getCoachById(customer_id);
};

const handleOpenModalDelete = (customer_id: number) => {
    console.log('delete: ' + customer_id);
    deleteCoach(customer_id);
};

const handleCreate = (data: storeCoachRequest) => {
    storeCoach(data);
};
const handleUpdate = (data: updateCoachRequest) => {
    updateCoach(data.id, data);
};
onMounted(() => {
    getCoaches();
});

</script>

<style scoped lang="scss">

</style>