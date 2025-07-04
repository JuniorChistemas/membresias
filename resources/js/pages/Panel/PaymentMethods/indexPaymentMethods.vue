    <template>
        <Head title="Métodos de pago" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">

                    <!-- Titulo y descripción del modulo -->
                    <div class="px-6 pt-4 pb-1 mb-4">
                        <CardTitle class="text-2xl">Registro de metodos de pago</CardTitle>
                        <CardDescription class="text-base">Visualiza, crea y gestiona los metodos de pago del sistema.</CardDescription>
                    </div>

                    <!-- Boton de crear, filtro y exportaciones -->
                    <div class="flex flex-wrap justify-between items-center mb-4 px-6 mt-4 gap-2">
                        <Button @click="handleOpenModalCreate">Nuevo método de pago</Button>
                        <div class="flex items-center gap-2">
                            <FilterPaymentMethods @search="handleSearch" />
                            <ToolsPaymentMethods @import-success="getPaymentMethods" />
                        </div>
                    </div>

                    <div class="mb-4 px-6 py-2">
                        <TablePaymentMethods
                            :paymentMethods="paymentMethods"
                            :pagination="pagination"
                            :loading="loading"
                            @page-change="handlePageChange"
                            @open-modal-edit="handleOpenModalUpdate"
                            @open-modal-delete="handleOpenModalDelete"
                        />
                        <ModalPaymentMethods
                            :status-modal="modals.createEdit"
                            @close-modal="handleCloseModalCreate"
                            :paymentMethod="paymentMethod"
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
    import { Head } from '@inertiajs/vue3';
    import { usePaymentMethod } from '@/composables/usePaymentMethod';
    import { storePaymentMethodRequest, updatePaymentMethodRequest } from './interfaces/PaymentMethod';
    import { onMounted } from 'vue';
    import Button from '@/components/ui/button/Button.vue';
    import FilterPaymentMethods from '@/components/filter.vue';
    import CardTitle from '@/components/ui/card/CardTitle.vue';
    import CardDescription from '@/components/ui/card/CardDescription.vue';
    import ToolsPaymentMethods from './components/toolsPaymentMethods.vue';
    import TablePaymentMethods from './components/tablePaymentMethods.vue';
import ModalPaymentMethods from './components/modalPaymentMethods.vue';

    const breadcrumbs = [
        {
            title: 'Métodos de pago',
            href: '/panel/paymentMethods',
        },
    ];

    const {
        paymentMethod,
        getPaymentMethods,
        pagination,
        loading,
        deletePaymentMethod,
        modals,
        getPaymentMethodById,
        paymentMethods,
        openModal,
        closeModal,
        storePaymentMethod,
        updatePaymentMethod,
    } = usePaymentMethod();

    // function to handle search
    const handleSearch = (searchText: string) => {
        getPaymentMethods(1, searchText);
    };

    // function to handle modal create
    const handleOpenModalCreate = () => {
        openModal('createEdit');
    };

    const handleCloseModalCreate = () => {
        closeModal('createEdit');
    };

    // function to handle page change
    const handlePageChange = (page: number) => {
        getPaymentMethods(page);
    };

    const handleOpenModalUpdate = (paymentMethod_id: number) => {
        console.log('update: ' + paymentMethod_id);
        getPaymentMethodById(paymentMethod_id)
    };

    const handleOpenModalDelete = (paymentMethod_id: number) => {
        console.log('delete: ' + paymentMethod_id);
        getPaymentMethodById(paymentMethod_id);
    };

    const handleCreate = (data: storePaymentMethodRequest) => {
        storePaymentMethod(data);
    };

    const handleUpdate = (data: updatePaymentMethodRequest) => {
        updatePaymentMethod(data);
    };

    onMounted(() => {
        getPaymentMethods(1);
    });

    </script>

    <style scoped lang="scss">
    </style>