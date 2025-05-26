<template>
    <div class="table-container">
        <LoadingTable v-if="loading" :headers="7" :row-count="10" />
        <div v-else class="table-content">
            <div class="table-container">
                <Table class="table-responsive">
                    <TableHeader class="table-header-row">
                        <TableRow>
                            <TableHead class="table-head-id">ID</TableHead>
                            <TableHead class="table-head">Nombre</TableHead>
                            <TableHead class="table-head">Apellido</TableHead>
                            <TableHead class="table-head">Código</TableHead>
                            <TableHead class="table-head">Teléfono</TableHead>
                            <TableHead class="table-head">Email</TableHead>
                            <TableHead class="table-head">Dirección</TableHead>
                            <TableHead class="table-head-status">Estado</TableHead>
                            <TableHead class="table-head-actions">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody class="table-body">
                        <TableRow v-for="customer in props.customers" :key="customer.id" class="table-row">
                            <TableCell class="cell-id">{{ customer.id }}</TableCell>
                            <TableCell class="cell-data">{{ customer.first_name }}</TableCell>
                            <TableCell class="cell-data">{{ customer.last_name }}</TableCell>
                            <TableCell class="cell-data">{{ customer.code }}</TableCell>
                            <TableCell class="cell-data">{{ customer.phone || 'Sin teléfono' }}</TableCell>
                            <TableCell class="cell-data">{{ customer.email || 'Sin email' }}</TableCell>
                            <TableCell class="cell-data">{{ customer.address || 'Sin dirección' }}</TableCell>
                            <TableCell>
                                <span
                                    v-if="customer.status === true"
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
                                    @click="openModalEdit(customer.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-orange-600 hover:bg-orange-50 hover:text-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/30 dark:hover:text-orange-300"
                                    title="Editar cliente"
                                >
                                    <UserPen class="h-4 w-4" />
                                    <span class="sr-only">Editar cliente</span>
                                </Button>
                                <Button
                                    @click="openModalDelete(customer.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                                    title="Eliminar cliente"
                                >
                                    <Trash class="h-4 w-4" />
                                    <span class="sr-only">Eliminar cliente</span>
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
import { CustomerResource } from '../interfaces/Customer';

const props = defineProps<{
    customers: CustomerResource[];
    pagination: Pagination;
    loading: boolean;
}>();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal-edit', id_customer: number): void;
    (e: 'open-modal-delete', id_customer: number): void;
}>();

const openModalEdit = (id: number) => {
    emit('open-modal-edit', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};
</script>

<style scoped></style>
