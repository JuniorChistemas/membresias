<template>
    <div class="table-container">
        <LoadingTable v-if="loading" :headers="5" :row-count="10"/>
        <div v-else class="table-content">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">

                <!-- TABLA PARA MOSTRAR LOS DATOS DE TIPO DE MEMBRESIA -->
                <Table class="table-responsive">

                    <!-- LA CABECERA DE LA TABLA CON LOS NOMBRES -->
                    <TableHeader class="table-header-row">
                        <TableRow>
                            <TableHead class="table-head-id">ID</TableHead>
                            <TableHead class="table-head">Nombre</TableHead>
                            <TableHead class="table-head">Descripción</TableHead>
                            <TableHead class="table-head">Precio</TableHead>
                            <TableHead class="table-head-status">Estado</TableHead>
                            <TableHead class="table-head-actions">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>

                    <!-- CUERPO DE LA TABLA CON LOS DATOS -->
                    <TableBody class="table-body">
                        <TableRow v-for="typeMembership in props.typeMemberships" :key="typeMembership.id" class="table-row">
                            <TableCell class="cell-id">{{ typeMembership.id }}</TableCell>
                            <TableCell class="cell-data">{{ typeMembership.name }}</TableCell>
                            <TableCell class="cell-data">{{ typeMembership.description || 'Sin descripción' }}</TableCell>
                            <TableCell class="cell-data">{{ currencyFormat(typeMembership.price) }}</TableCell>
                            <TableCell>
                                <span
                                    v-if="typeMembership.status === true"
                                    class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-green-500 dark:bg-green-400"></span>Activo
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-red-500 dark:bg-red-400"></span>Inactivo
                                </span>
                            </TableCell>

                            <!-- Columnas para agregar los botones de editar y eliminar -->
                            <TableCell class="cell-actions">

                                <!-- BOTON PARA EDITAR -->
                                <Button
                                    @click="openModalEdit(typeMembership.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-orange-600 hover:bg-orange-50 hover:text-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/30 dark:hover:text-orange-300"
                                    title="Editar tipo de membresía"
                                >
                                    <UserPen class="h-4 w-4" />
                                    <span class="sr-only">Editar tipo de membresía</span>
                                </Button>

                                <!-- BOTON PARA ELIMINAR -->
                                <Button
                                    @click="openModalDelete(typeMembership.id)"
                                    variant="ghost"
                                    size="sm"
                                    class="h-8 w-8 p-0 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                                    title="Eliminar tipo de membresía"
                                >
                                    <Trash class="h-4 w-4" />
                                    <span class="sr-only">Eliminar tipo de membresía</span>
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
        <PaginationTypeMembership :meta="pagination" @page-change="$emit('page-change', $event)" class="mt-6" />
    </div>
</template>

<script setup lang="ts">
import LoadingTable from '@/components/loadingTable.vue';
import Button from '@/components/ui/button/Button.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import { Trash, Type, UserPen } from 'lucide-vue-next';
import PaginationTypeMembership from '@/components/paginate.vue';
import { TypeMembershipResource } from '../interfaces/TypeMembership';
import { Pagination } from '@/interfaces/paginacion';

const props = defineProps<{
    typeMemberships: TypeMembershipResource[];
    pagination: Pagination;
    loading: boolean;
}>();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal-edit', id_type_membership: number): void;
    (e: 'open-modal-delete', id_type_membership: number): void;
}>();

const openModalEdit = (id: number) => {
    emit('open-modal-edit', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};

function currencyFormat(value: number) {
  // Puedes ajustar a tu moneda/localización preferida
  return new Intl.NumberFormat('es-PE', {
    style: 'currency',
    currency: 'PEN', // Cambia a USD, EUR, etc, si deseas
    minimumFractionDigits: 2
  }).format(value ?? 0);
}


</script>

<style scoped>
</style>