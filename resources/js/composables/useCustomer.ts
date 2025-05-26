import { Pagination } from '@/interfaces/paginacion';
import { CustomerResource, storeCustomerRequest, updateCustomerRequest } from '@/pages/Panel/Customers/interfaces/Customer';
import { CustomerService } from '@/services/CustomerService';
import { showErrorMessage, showSuccessMessage } from '@/utils/messages';
import { reactive, toRefs } from 'vue';

type ModalType = 'create' | 'edit' | 'delete';

export const useCustomer = () => {
    // State
    const state = reactive({
        customers: [] as CustomerResource[],
        customer: null as CustomerResource | null,
        search: '',
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        } as Pagination,
        loading: false,
        message: '',
        modals: {
            create: false,
            edit: false,
            delete: false,
        },
    });

    // Helpers
    const handleApiResponse = (response: { success: boolean; message: string }, successMessage?: string) => {
        if (response.success) {
            state.message = response.message;
            showSuccessMessage('Éxito', successMessage || response.message);
            return true;
        }
        state.message = response.message;
        return false;
    };

    const handleApiError = (error: unknown, defaultMessage = 'Error desconocido') => {
        console.error('API Error:', error);
        const errorMessage = error instanceof Error ? error.message : defaultMessage;
        state.message = errorMessage;
        showErrorMessage('Error', errorMessage);
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
    };

    const refreshCustomers = async () => {
        await getCustomers(state.pagination?.current_page || 1, state.search);
    };

    // Methods
    const getCustomers = async (page: number = 1, searchTerm: string = '') => {
        try {
            state.loading = true;
            const response = await CustomerService.listCustomers(page, searchTerm);
            if (response.success) {
                state.customers = response.customers;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, 'Error al cargar clientes');
        } finally {
            state.loading = false;
        }
    };

    const storeCustomer = async (customerData: storeCustomerRequest) => {
        try {
            const response = await CustomerService.storeCustomer(customerData);
            if (handleApiResponse(response, 'Cliente creado exitosamente')) {
                closeModal('create');
                await refreshCustomers();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear cliente');
        }
    };

    const deleteCustomer = async (id: number) => {
        try {
            const response = await CustomerService.deleteCustomer(id);
            if (handleApiResponse(response, 'Cliente eliminado exitosamente')) {
                closeModal('delete');
                await refreshCustomers();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar cliente');
        }
    };

    const getCustomerById = async (id: number) => {
        try {
            const response = await CustomerService.getCustomerById(id);
            if (response.success) {
                state.customer = response.customer;
                state.modals.edit = true;
            }
        } catch (error) {
            handleApiError(error, 'Error al cargar cliente');
            closeModal('edit');
        }
    };

    const updateCustomer = async (id: number, customerData: updateCustomerRequest) => {
        try {
            const response = await CustomerService.updateCustomer(id, customerData);
            if (handleApiResponse(response, 'Cliente actualizado exitosamente')) {
                closeModal('edit');
                await refreshCustomers();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar cliente');
        }
    };

    return {
        ...toRefs(state),
        getCustomers,
        storeCustomer,
        deleteCustomer,
        getCustomerById,
        updateCustomer,
    };
};
