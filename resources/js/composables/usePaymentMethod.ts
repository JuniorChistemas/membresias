import { Pagination } from "@/interfaces/paginacion";
import { PaymentMethodResource, storePaymentMethodRequest, updatePaymentMethodRequest } from "@/pages/Panel/PaymentMethods/interfaces/PaymentMethod";
import { PaymentMethodService } from "@/services/PaymentMethodService";
import { showErrorMessage, showSuccessMessage } from "@/utils/messages";
import { reactive, toRefs } from "vue";

type ModalType = 'createEdit' | 'delete';

export const usePaymentMethod = () => {
    // State
    const state = reactive({
        paymentMethods: [] as PaymentMethodResource[],
        paymentMethod: null as PaymentMethodResource | null,
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
            createEdit: false,
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

    const openModal = (modalType: ModalType) => {
        state.modals[modalType] = true;
        if (modalType === 'createEdit') {
            state.paymentMethod = null;
        }
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
        if (modalType === 'createEdit') {
            state.paymentMethod = null;
        }
    };

    const refreshPaymentMethods = async () => {
        await getPaymentMethods(state.pagination?.current_page || 1, state.search);
    };

    // Methods
    const getPaymentMethods = async (page: number = 1, searchTerm: string = '') => {
        try {
            state.loading = true;
            const response = await PaymentMethodService.listPaymentMethods(page, searchTerm);
            if (response.success){
                state.paymentMethods = response.paymentMethods;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener los métodos de pago');
        } finally {
            state.loading = false;
        }
    };

    const storePaymentMethod = async (paymentMethodData: storePaymentMethodRequest) => {
        try{
            const response = await PaymentMethodService.storePaymentMethod(paymentMethodData);
            if (handleApiResponse(response, 'Método de pago creado exitosamente')) {
                closeModal('createEdit');
                await refreshPaymentMethods();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear el método de pago');
        }
    };
    
    const deletePaymentMethod = async (id: number) => {
        try {
            const response = await PaymentMethodService.deletePaymentMethod(id);
            if (handleApiResponse(response, 'Método de pago eliminado exitosamente')) {
                closeModal('delete');
                await refreshPaymentMethods();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar el método de pago');
        }
    };

    const getPaymentMethodById = async (id: number) => {
        try {
            const response = await PaymentMethodService.getPaymentMethodById(id);
            if (response.success) {
                state.paymentMethod = response.payment_method;
                state.modals.createEdit = true;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener el método de pago');
            closeModal('createEdit');
        }
    };

    const updatePaymentMethod = async (data: updatePaymentMethodRequest) => {
        try {
            const response = await PaymentMethodService.updatePaymentMethod(data);
            if (handleApiResponse(response, 'Método de pago actualizado exitosamente')) {
                closeModal('createEdit');
                await refreshPaymentMethods();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar el método de pago');
        }
    };

    return {
        ...toRefs(state),
        getPaymentMethods,
        storePaymentMethod,
        deletePaymentMethod,
        getPaymentMethodById,
        updatePaymentMethod,
        openModal,
        closeModal,
    };
}