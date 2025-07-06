<template>
    <div class="monthly-payments-table">
        <h2>Pagos Mensuales</h2>
        <div class="table-container">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Tipo de Membresía</th>
                        <th>Precio</th>
                        <th>Meses</th>
                        <th>Total</th>
                        <th>Método de Pago</th>
                        <th>Entrenador</th>
                        <th>Fecha de Registro</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in monthlyPayments" :key="payment.id">
                        <td>{{ payment.id }}</td>
                        <td>{{ payment.customer_name }}</td>
                        <td>
                            <div class="membership-info">
                                <span class="membership-name">{{ payment.type_membership_name }}</span>
                                <small class="membership-id">(ID: {{ payment.type_membership_id }})</small>
                            </div>
                        </td>
                        <td class="price">${{ parseFloat(payment.type_membership_price).toFixed(2) }}</td>
                        <td class="months">{{ payment.months_total }}</td>
                        <td class="total">${{ parseFloat(payment.total).toFixed(2) }}</td>
                        <td>{{ payment.payment_method_name }}</td>
                        <td>{{ payment.coach_name }}</td>
                        <td class="date">{{ formatDate(payment.date_registration) }}</td>
                        <td>
                            <span class="status-badge" :class="payment.status ? 'status-active' : 'status-inactive'">
                                {{ payment.status ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mensaje cuando no hay datos -->
        <div v-if="monthlyPayments.length === 0" class="no-data">
            <p>No hay pagos registrados</p>
        </div>
    </div>
</template>

<script setup lang="ts">
interface MonthlyPayment {
    id: number;
    customer_id: number;
    customer_name: string;
    type_membership_id: number;
    type_membership_name: string;
    type_membership_price: string;
    months_total: number;
    total: string;
    payment_method_id: number;
    payment_method_name: string;
    coach_id: number;
    coach_name: string;
    date_registration: string;
    status: boolean;
    created_at: string;
}

const props = defineProps<{
    monthlyPayments: any[];
}>();

// Función para formatear fecha
const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};
</script>
<style scoped lang="css"></style>
