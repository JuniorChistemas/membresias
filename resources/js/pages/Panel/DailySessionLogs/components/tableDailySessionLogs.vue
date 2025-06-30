<template>
  <div class="table-container">
    <LoadingTable v-if="loading" :headers="5" :row-count="10" />
    <div v-else class="table-content">
      <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">
        <Table class="table-responsive">
          <!-- CABECERA -->
          <TableHeader class="table-header-row">
            <TableRow>
              <TableHead class="table-head-id">ID</TableHead>
              <TableHead class="table-head">Nombre</TableHead>
              <TableHead class="table-head">Precio</TableHead>
              <TableHead class="table-head">Método de Pago</TableHead>
              <TableHead class="table-head">Registrado en</TableHead>
              <TableHead class="table-head-actions">Acciones</TableHead>
            </TableRow>
          </TableHeader>

          <!-- CUERPO -->
          <TableBody class="table-body">
            <TableRow
              v-for="session in props.dailySessionLogs"
              :key="session.id"
              class="table-row"
            >
              <TableCell class="cell-id">{{ session.id }}</TableCell>
              <TableCell class="cell-data">{{ session.name }}</TableCell>
              <TableCell class="cell-data">{{ currencyFormat(session.price) }}</TableCell>
              <TableCell class="cell-data">{{ session.payment_method?.name || 'N/A' }}</TableCell>
              <TableCell class="cell-data">{{ formatDate(session.registered_at) }}</TableCell>
              <TableCell class="cell-actions">
                
                <!-- BOTON PARA EDITAR -->
                <Button
                  @click="openModalEdit(session.id)"
                  variant="ghost"
                  size="sm"
                  class="h-8 w-8 p-0 text-orange-600 hover:bg-orange-50 hover:text-orange-700 dark:text-orange-400 dark:hover:bg-orange-900/30 dark:hover:text-orange-300"
                  title="Editar registro"
                >
                  <UserPen class="h-4 w-4" />
                  <span class="sr-only">Editar registro de sesión</span>
                </Button>

                <!-- BOTON PARA ELIMINAR -->
                <Button
                  @click="openModalDelete(session.id)"
                  variant="ghost"
                  size="sm"
                  class="h-8 w-8 p-0 text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                  title="Eliminar registro"
                >
                  <Trash class="h-4 w-4" />
                  <span class="sr-only">Eliminar registro de sesión</span>
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
import { DailySessionLogResource } from '../interfaces/DailySessionLog';
import { Pagination } from '@/interfaces/paginacion';
import LoadingTable from '@/components/loadingTable.vue';
import Table from '@/components/ui/table/Table.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import Button from '@/components/ui/button/Button.vue';
import PaginationUser from '@/components/paginate.vue';
import { Trash, UserPen } from 'lucide-vue-next';

const props = defineProps<{
  dailySessionLogs: DailySessionLogResource[];
  pagination: Pagination;
  loading: boolean;
}>();

const emit = defineEmits<{
  (e: 'page-change', page: number): void;
  (e: 'open-modal-edit', id_daily_session_log: number): void;
  (e: 'open-modal-delete', id_daily_session_log: number): void;
}>();

const openModalEdit = (id: number) => {
  emit('open-modal-edit', id);
};

const openModalDelete = (id: number) => {
  emit('open-modal-delete', id);
};

function currencyFormat(value: number) {
  return new Intl.NumberFormat('es-PE', {
    style: 'currency',
    currency: 'PEN',
    minimumFractionDigits: 2,
  }).format(value ?? 0);
}

function formatDate(date: string) {
  return new Date(date).toLocaleString('es-PE', {
    timeZone: 'America/Lima', // ✅ Esto fuerza horario de Perú
    dateStyle: 'short',
    timeStyle: 'short',
    hour12: true,
  });
}
</script>

<style scoped></style>
