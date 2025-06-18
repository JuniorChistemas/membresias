<template>
    <div class="container mx-auto px-4 py-2">
        <LoadingTable v-if="loading" :headers="7" :row-count="10" />
        <div v-else class="table-content">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">
                <Table class="table-responsive">
                    <TableHeader class="table-header-row">
                        <TableRow>
                            <TableHead class="table-head-id">ID</TableHead>
                            <TableHead class="table-head">Foto</TableHead>
                            <TableHead class="table-head">Nombre</TableHead>
                            <TableHead class="table-head">Usuario</TableHead>
                            <TableHead class="table-head">Email</TableHead>
                            <TableHead class="table-head">Local</TableHead>
                            <TableHead class="table-head">Estado</TableHead>
                            <TableHead class="table-head">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody class="cursor-pointer">
                        <TableRow v-for="user in props.usersList" :key="user.id">
                            <TableCell class="cell-id">{{ user.id }}</TableCell>
                            <TableCell class="cell-data">
                                <img v-if="user.photo" :src="user.photo" alt="Foto no" class="h-10 w-10 rounded-full object-cover" />
                                <span v-else>Sin foto</span>
                            </TableCell>
                            <TableCell class="cell-data">{{ user.name }}</TableCell>
                            <TableCell class="cell-data">{{ user.username }}</TableCell>
                            <TableCell class="cell-data">{{ user.email }}</TableCell>
                            <TableCell class="cell-data">{{ user.local }}</TableCell>
                            <TableCell class="cell-data">
                                <span
                                    v-if="user.status === true"
                                    class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-green-500 dark:bg-green-400"></span>Activo
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-red-500 dark:bg-red-400"></span>
                                    Inactivo
                                </span>
                            </TableCell>
                            <TableCell class="cell-actions">
                                <Button
                                    @click="openModalUpdate(user.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-orange-600 hover:bg-orange-50 hover:text-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/30 dark:hover:text-orange-300"
                                    title="Editar usuario"
                                >
                                    <UserPen class="h-4 w-4" />
                                    <span class="sr-only">Editar usuario</span>
                                </Button>
                                <Button
                                    @click="openModalDelete(user.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                                    title="Eliminar usuario"
                                >
                                    <Trash class="h-4 w-4" />
                                    <span class="sr-only">Eliminar usuario</span>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
        <PaginationUser :meta="pagination" @page-change="$emit('page-change', $event)" class="mt-6" />
    </div>
</template>

<script setup lang="ts">
import LoadingTable from '@/components/loadingTable.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Pagination } from '@/interfaces/paginacion';
import { Trash, UserPen } from 'lucide-vue-next';
import PaginationUser from '../../../../components/paginate.vue';
import { UserResource } from '../interfaces/User';
const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal', id_user: number): void;
    (e: 'open-modal-delete', id_user: number): void;
}>();

const props = defineProps<{
    usersList: UserResource[];
    pagination: Pagination;
    loading: boolean;
}>();

const openModalUpdate = (id: number) => {
    emit('open-modal', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};
</script>

<style scoped></style>
