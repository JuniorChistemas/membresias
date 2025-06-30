<template>
    <Head title="Tipo de membresias"/>
    <AppLayout :breadcrumbs="breadcrumbs">
       <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                
                <!-- Titulo y descripción del modulo -->
                <div class="px-6 pt-4 pb-1 mb-4">
                    <CardTitle class="text-2xl">Registro de tipo de membresias</CardTitle>
                    <CardDescription class="text-base">Visualiza, crea y gestiona los tipos de membresia del sistema.</CardDescription>
                </div>

                <!-- Boton de crear, filtro y exportaciones -->
                <div class="flex flex-wrap justify-between items-center mb-4 px-6 mt-4 gap-2">
                    <Button @click="handleOpenModalCreate">Nuevo tipo de membresia</Button>
                    <div class="flex items-center gap-2">
                        <FilterTypeMemberships @search="handleSearch" />
                        <ToolsTypeMemberships @import-success="getTypeMemberships"/>
                    </div>
                </div>

                <div class="mb-4 px-6 py-2">
                    <TableTypeMemberships
                        :typeMemberships="typeMemberships"
                        :pagination="pagination"
                        :loading="loading"
                        @page-change="handlePageChange"
                        @open-modal-edit="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                    />
                    <ModalTypeMemberships
                        :status-modal="modals.createEdit"
                        @close-modal="handleCloseModalCreate"
                        :type-membership="typeMembership"
                        @create="handleCreate"
                        @update="handleUpdate"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import TableTypeMemberships from './components/tableTypeMemberships.vue';
import ModalTypeMemberships from './components/modalTypeMemberships.vue';
import { useTypeMembership } from '@/composables/useTypeMembership';
import { storeTypeMembershipRequest, updateTypeMembershipRequest } from './interfaces/TypeMembership';
import { onMounted } from 'vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import Button from '@/components/ui/button/Button.vue';
import FilterTypeMemberships from '../../../components/filter.vue';
import ToolsTypeMemberships from './components/toolsTypeMemberships.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tipo de membresias',
        href: '/panel/typeMemberships',
    },
];

const {
    typeMembership,
    getTypeMemberships,
    pagination,
    loading,
    deleteTypeMembership,
    modals,
    getTypeMembershipById,
    typeMemberships,
    openModal,
    closeModal,
    storeTypeMembership,
    updateTypeMembership,
} = useTypeMembership();

// function to handle search
const handleSearch = (searchText: string) => {
    getTypeMemberships(1, searchText);
};

//function to handle modal create
const handleOpenModalCreate = () => {
    openModal('createEdit');
};

const handleCloseModalCreate = () => {
    closeModal('createEdit');
};

// function to handle page change
const handlePageChange = (page: number) => {
    getTypeMemberships(page);
};

const handleOpenModalUpdate = (typeMembership_id: number) => {
    console.log('update: ' + typeMembership_id)
    getTypeMembershipById(typeMembership_id);
};

const handleOpenModalDelete = (typeMembership_id: number) => {
    console.log('delete: ' + typeMembership_id);
    deleteTypeMembership(typeMembership_id);
};

const handleCreate = (data: storeTypeMembershipRequest) => {
    storeTypeMembership(data);
};

const handleUpdate = (data: updateTypeMembershipRequest) => {
    updateTypeMembership(data.id, data);
};

onMounted(() => {
    getTypeMemberships();
});


</script>
<style scoped>
</style>