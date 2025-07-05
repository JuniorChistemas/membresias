<template>
  <div class="flex gap-2 items-end">
    <!-- Método de pago -->
    <div>
      <label class="block text-xs mb-1">Método de pago</label>
      <select v-model="selectedPaymentMethod" class="input-class">
        <option value="">Todos</option>
        <option v-for="method in paymentMethods" :key="method.id" :value="method.id">
          {{ method.name }}
        </option>
      </select>
    </div>
    <!-- Fecha desde -->
    <div>
      <label class="block text-xs mb-1">Desde</label>
      <input type="date" v-model="dateFrom" class="input-class" />
    </div>
    <!-- Fecha hasta -->
    <div>
      <label class="block text-xs mb-1">Hasta</label>
      <input type="date" v-model="dateTo" class="input-class" />
    </div>
    <button class="btn" @click="emitFilter">Filtrar</button>
    <button class="btn" @click="resetFilter" type="button">Limpiar</button>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { PaymentMethodResource } from '@/pages/Panel/PaymentMethods/interfaces/PaymentMethod';

const props = defineProps<{
  paymentMethods: PaymentMethodResource[];
}>();

const emit = defineEmits<{
  (e: 'filter', filter: { payment_method_id?: number; start_date?: string; end_date?: string }): void;
}>();

const selectedPaymentMethod = ref<string | number>('');
const dateFrom = ref('');
const dateTo = ref('');

const emitFilter = () => {
  emit('filter', {
    payment_method_id: selectedPaymentMethod.value ? Number(selectedPaymentMethod.value) : undefined,
    start_date: dateFrom.value || undefined,
    end_date: dateTo.value || undefined,
  });
};

const resetFilter = () => {
  selectedPaymentMethod.value = '';
  dateFrom.value = '';
  dateTo.value = '';
  emitFilter();
};
</script>

<style scoped>
.input-class {
  padding: 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
}
.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  background: #2563eb;
  color: white;
  border: none;
  cursor: pointer;
  margin-left: 0.5rem;
}
.btn[type="button"] {
  background: #64748b;
}
</style>