<template>
    <Head title="Usuarios"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="mt-4 mb-4 flex items-center justify-between px-6">
                    <TableUser
                        :users-list="principal.usersList"
                        :pagination="principal.pagination"
                        :loading="principal.loading"
                        @page-change="handlePageChange"
                        @open-modal="handleOpenModalUpdate"
                        @open-modal-delete="handleOpenModalDelete"
                    />
                </div>
                <UpdateUser
                    :user="principal.userId"
                    :open="principal.isEdit"
                    @close-modal="() => (principal.isEdit = false)"
                    @update-user="dataUpdateUser"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import { useUser } from '@/composables/useUser';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import TableUser from './components/tableUser.vue';
import UpdateUser from './components/updateUser.vue';
import { UpdateUserRequest } from './interfaces/User';

const { principal, getUsersData, deleteUser, getUserById, updateUser } = useUser();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Crear Usuario',
        href: '/panel/users/create',
    },
    {
        title: 'Usuarios',
        href: '/panel/users',
    },
];

const handlePageChange = (page: number) => {
    console.log(page);
    getUsersData(page);
};
const handleOpenModalUpdate = (user_id: number) => {
    getUserById(user_id);
    console.log('update: ' + user_id);
};
const handleOpenModalDelete = (user_id: number) => {
    deleteUser(user_id);
    console.log('delete: ' + user_id);
};

const dataUpdateUser = (data: UpdateUserRequest, id: number) => {
    console.log('dataUpdatePayment', data);
    updateUser(id, data);
};

onMounted(() => {
    getUsersData();
});
</script>
<style scoped></style>
