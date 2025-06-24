<template>
    <div class="table-container">
        <LoadingTable v-if="loading" :headers="5" :row-count="10"/>
        <div v-else class="table-content">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">

                <!-- TABLA PARA MOSTRAR LOS DATOS DE METODOS DE PAGO -->
                 <Table class="table-responsive">

                    <!-- CABECERA DE LA TABLA CON LOS NOMBRES -->
                    <TableHeader class="table-header-row">
                        <TableRow>
                            <TableHead class="table-head-id">ID</TableHead>
                            <TableHead class="table-head">Nombre</TableHead>
                            <TableHead class="table-head">Descripción</TableHead>
                            <TableHead class="table-head-status">Estado</TableHead>
                            <TableHead class="table-head-actions">Acciones</TableHead>
                        </TableRow>
                    </TableHeader>

                    <!-- CUERPO DE LA TABLA CON LOS DATOS -->
                    <TableBody class="table-body">
                        <TableRow v-for="paymentMethod in props.paymentMethods" :key="paymentMethod.id" class="table-row">
                            <TableCell class="cell-id">{{ paymentMethod.id }}</TableCell>
                            <TableCell class="cell-data">{{ paymentMethod.name }}</TableCell>
                            <TableCell class="cell-data">{{ paymentMethod.description || 'Sin descripción' }}</TableCell>
                            <TableCell>
                                <span
                                    v-if="paymentMethod.status === true"
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
                                    @click="openModalEdit(paymentMethod.id)"
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
                                    @click="openModalDelete(paymentMethod.id)"
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
        <PaginationPaymentMethods :meta="pagination" @page-change="emit('page-change', $event)" class="mt-6"/>
    </div>
</template>

<script setup lang="ts">
import LoadingTable from '@/components/loadingTable.vue';
import { PaymentMethodResource } from '../interfaces/PaymentMethod';
import { Pagination } from '@/interfaces/paginacion';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import Table from '@/components/ui/table/Table.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import Button from '@/components/ui/button/Button.vue';
import { UserPen, Trash } from 'lucide-vue-next';
import PaginationPaymentMethods from '@/components/paginate.vue';

const props = defineProps<{
    paymentMethods: PaymentMethodResource[];
    pagination: Pagination;
    loading: boolean;
}>();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal-edit', id_payment_method: number): void;
    (e: 'open-modal-delete', id_payment_method: number): void;
}>();

const openModalEdit = (id: number) => {
    emit('open-modal-edit', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};

</script>

<style scoped>
</style>