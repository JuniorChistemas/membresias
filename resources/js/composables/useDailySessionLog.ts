import { Pagination } from "@/interfaces/paginacion";
import {
    DailySessionLogResource,
    storeDailySessionLogRequest,
    updateDailySessionLogRequest,
} from "@/pages/Panel/DailySessionLogs/interfaces/DailySessionLog";
import { DailySessionLogService } from "@/services/DailySessionLogService";
import { showErrorMessage, showSuccessMessage } from "@/utils/messages";
import { reactive, toRefs } from "vue";
import axios from 'axios';
import { PaymentMethodResource } from "@/pages/Panel/PaymentMethods/interfaces/PaymentMethod";


type ModalType = 'createEdit' | 'delete';

export const useDailySessionLog = () => {
    const state = reactive({
        paymentMethods: [] as PaymentMethodResource[],
        dailySessionLogs: [] as DailySessionLogResource[],
        dailySessionLog: null as DailySessionLogResource | null,
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

    const getPaymentMethods = async () => {
        try {
            const response = await axios.get('/panel/paymentMethods-all');
            if (response.data.success) {
                // Filtra solo los métodos de pago activos
                state.paymentMethods = response.data.paymentMethods.filter(
                    (method: PaymentMethodResource) => method.status === true
                );
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener métodos de pago');
        }
    };

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
            state.dailySessionLog = null;
        }
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
        if (modalType === 'createEdit') {
            state.dailySessionLog = null;
        }
    };

    const refreshDailySessionLogs = async () => {
        await getDailySessionLogs(state.pagination.current_page || 1, {});    
    };

        const getDailySessionLogs = async (page = 1, filter: { payment_method_id?: number; start_date?: string; end_date?: string } = {}) => {
        try {
            state.loading = true;
            const response = await DailySessionLogService.listDailySessionLogs(page, filter);
            if (response.success) {
                state.dailySessionLogs = response.dailySessionLogs;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener los registros diarios de sesión');
        } finally {
            state.loading = false;
        }
    };

    const storeDailySessionLog = async (data: storeDailySessionLogRequest) => {
        try {
            const response = await DailySessionLogService.storeDailySessionLog(data);
            if (handleApiResponse(response, 'Registro de sesión creado')) {
                closeModal('createEdit');
                await refreshDailySessionLogs();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear el registro');
        }
    };

    const deleteDailySessionLog = async (id: number) => {
        try {
            const response = await DailySessionLogService.deleteDailySessionLog(id);
            if (handleApiResponse(response, 'Registro de sesión eliminado')) {
                closeModal('delete');
                await refreshDailySessionLogs();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar el registro');
        }
    };

    const getDailySessionLogById = async (id: number) => {
        try {
            const response = await DailySessionLogService.getDailySessionLogById(id);
            if (response.success) {
                state.dailySessionLog = response.daily_session_log;
                state.modals.createEdit = true;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener el registro');
            closeModal('createEdit');
        }
    };

    const updateDailySessionLog = async (data: updateDailySessionLogRequest) => {
        try {
            const response = await DailySessionLogService.updateDailySessionLog(data);
            if (handleApiResponse(response, 'Registro de sesión actualizado')) {
                closeModal('createEdit');
                await refreshDailySessionLogs();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar el registro');
        }
    };

    return {
        ...toRefs(state),
        getDailySessionLogs,
        storeDailySessionLog,
        deleteDailySessionLog,
        getDailySessionLogById,
        updateDailySessionLog,
        openModal,
        closeModal,
        getPaymentMethods,
    };
};
