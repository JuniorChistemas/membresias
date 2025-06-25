<template>
    <div class="table-container">
        <LoadingTable v-if="loading" :headers="9" :row-count="10"/>
        <div v-else class="table-content">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">
                <Table class="table-responsive">
                    <TableHeader class="table-header-row">
                        <TableRow>
                            <TableHead class="table-head-id">ID</TableHead>
                            <TableHead class="table-head">Nombre</TableHead>
                            <TableHead class="table-head">DNI</TableHead>
                            <TableHead class="table-head">Especialidad</TableHead>
                            <TableHead class="table-head">Celular</TableHead>
                            <TableHead class="table-head">Correo</TableHead>
                            <TableHead class="table-head">Dirección</TableHead>
                            <TableHead class="table-head-status">Estado</TableHead>
                            <TableHead class="table-head-actions"></TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody class="table-body">
                        <TableRow v-for="coach in props.coaches" :key="coach.id" class="table-row">
                            <TableCell class="cell-id">{{ coach.id }}</TableCell>
                            <TableCell class="cell-data">{{ coach.name }}</TableCell>
                            <TableCell class="cell-data">{{ coach.dni }}</TableCell>
                            <TableCell class="cell-data">{{ coach.specialty }}</TableCell>
                            <TableCell class="cell-data">{{ coach.phone }}</TableCell>
                            <TableCell class="cell-data">{{ coach.email }}</TableCell>
                            <TableCell class="cell-data">{{ coach.address }}</TableCell>
                            <TableCell>
                                <span
                                    v-if="coach.status === true"
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
                                    @click="openModalEdit(coach.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-orange-600 hover:bg-orange-50 hover:text-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/30 dark:hover:text-orange-300"
                                    title="Editar cliente"
                                >
                                    <UserPen class="h-4 w-4" />
                                    <span class="sr-only">Editar entrenador</span>
                                </Button>

                                <Button
                                    @click="openModalDelete(coach.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                                    title="Eliminar cliente"
                                >
                                    <Trash class="h-4 w-4" />
                                    <span class="sr-only">Eliminar entrenador</span>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
        <PaginationCoach :meta="pagination" @page-change="$emit('page-change', $event)" class="mt-6" />
    </div>
</template>

<script setup lang="ts">
import LoadingTable from '@/components/loadingTable.vue';
import Table from '@/components/ui/table/Table.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import { CoachResource } from '../interfaces/Coach';
import { Pagination } from '@/interfaces/paginacion';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import Button from '@/components/ui/button/Button.vue';
import { UserPen } from 'lucide-vue-next';
import PaginationCoach from '../../../../components/paginate.vue';

const props = defineProps<{
    coaches: CoachResource[];
    pagination: Pagination;
    loading: boolean;
}>();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal-edit', id_coach: number): void;
    (e: 'open-modal-delete', id_coach: number): void;
}>();

const openModalEdit = (id: number) => {
    emit('open-modal-edit', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};

</script>

<style scoped lang="scss">

</style>