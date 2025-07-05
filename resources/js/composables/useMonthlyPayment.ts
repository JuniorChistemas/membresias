import { Pagination } from "@/interfaces/paginacion";
import {
    MonthlyPaymentResource,
    StoreMonthlyPaymentRequest,
    UpdateMonthlyPaymentRequest
} from "@/pages/Panel/MonthlyPayments/interfaces/MonthlyPayment";
import { MonthlyPaymentService } from "@/services/MonthlyPaymentService";
import { showErrorMessage, showSuccessMessage } from "@/utils/messages";
import { reactive, toRefs } from "vue";

// Importa los recursos de catálogos
import { CustomerResource } from "@/pages/Panel/Customers/interfaces/Customer";
import { TypeMembershipResource } from "@/pages/Panel/TypeMemberships/interfaces/TypeMembership";
import { PaymentMethodResource } from "@/pages/Panel/PaymentMethods/interfaces/PaymentMethod";
import { CoachResource } from "@/pages/Panel/Coaches/interfaces/Coach";

// Importa los servicios de catálogos
import { CustomerService } from "@/services/CustomerService";
import { TypeMembershipService } from "@/services/TypeMembershipService";
import { PaymentMethodService } from "@/services/PaymentMethodService";
import { CoachService } from "@/services/CoachService";

type ModalType = 'createEdit' | 'delete';

export const useMonthlyPayment = () => {
    // State
    const state = reactive({
        monthlyPayments: [] as MonthlyPaymentResource[],
        monthlyPayment: null as MonthlyPaymentResource | null,
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
        customers: [] as CustomerResource[],
        typeMemberships: [] as TypeMembershipResource[],
        paymentMethods: [] as PaymentMethodResource[],
        coaches: [] as CoachResource[],
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
            state.monthlyPayment = null;
        }
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
        if (modalType === 'createEdit') {
            state.monthlyPayment = null;
        }
    };

    // Métodos para cargar catálogos
    const getCustomers = async () => {
        try {
            const response = await CustomerService.getAll();
            if (response.success) {
                state.customers = response.customers;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener clientes');
        }
    };

    const getTypeMemberships = async () => {
        try {
            const response = await TypeMembershipService.getAll();
            if (response.success) {
                state.typeMemberships = response.typeMemberships;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener tipos de membresía');
        }
    };

    const getPaymentMethods = async () => {
        try {
            const response = await PaymentMethodService.getAll();
            if (response.success) {
                state.paymentMethods = response.paymentMethods;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener métodos de pago');
        }
    };

    const getCoaches = async () => {
        try {
            const response = await CoachService.getAll();
            if (response.success) {
                state.coaches = response.coaches;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener coaches');
        }
    };

    // Methods
    const getMonthlyPayments = async (page: number = 1, filter: any = {}) => {
        try {
            state.loading = true;
            const response = await MonthlyPaymentService.listMonthlyPayments(page, filter);
            if (response.success) {
                state.monthlyPayments = response.monthlyPayments;            }
        } catch (error) {
            handleApiError(error, 'Error al obtener las mensualidades');
        } finally {
            state.loading = false;
        }
    };

    const storeMonthlyPayment = async (monthlyPaymentData: StoreMonthlyPaymentRequest) => {
        try {
            const response = await MonthlyPaymentService.storeMonthlyPayment(monthlyPaymentData);
            if (handleApiResponse(response, 'Mensualidad creada exitosamente')) {
                closeModal('createEdit');
                await getMonthlyPayments();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear la mensualidad');
        }
    };

    const deleteMonthlyPayment = async (id: number) => {
        try {
            const response = await MonthlyPaymentService.deleteMonthlyPayment(id);
            if (handleApiResponse(response, 'Mensualidad eliminada exitosamente')) {
                closeModal('delete');
                await getMonthlyPayments();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar la mensualidad');
        }
    };

    const getMonthlyPaymentById = async (id: number) => {
        try {
            const response = await MonthlyPaymentService.getMonthlyPaymentById(id);
            if (response.success) {
                state.monthlyPayment = response.monthlyPayment;
                state.modals.createEdit = true;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener la mensualidad');
            closeModal('createEdit');
        }
    };

    const updateMonthlyPayment = async (data: UpdateMonthlyPaymentRequest) => {
        try {
            const response = await MonthlyPaymentService.updateMonthlyPayment(data);
            if (handleApiResponse(response, 'Mensualidad actualizada exitosamente')) {
                closeModal('createEdit');
                await getMonthlyPayments();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar la mensualidad');
        }
    };

    return {
        ...toRefs(state),
        getMonthlyPayments,
        storeMonthlyPayment,
        deleteMonthlyPayment,
        getMonthlyPaymentById,
        updateMonthlyPayment,
        openModal,
        closeModal,
        getCustomers,
        getTypeMemberships,
        getPaymentMethods,
        getCoaches,
    };
};