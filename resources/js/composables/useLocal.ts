import { Pagination } from '@/interfaces/paginacion';
import { LocalResource, storeLocalRequest, updateLocalRequest } from '@/pages/Panel/Locals/interfaces/Local';
import { LocalService } from '@/services/LocalService';
import { showErrorMessage, showSuccessMessage } from '@/utils/messages';
import { reactive, toRefs } from 'vue';

type ModalType = 'create' | 'edit' | 'delete';

export const useLocal = () => {
    // State
    const state = reactive({
        locals: [] as LocalResource[],
        local: null as LocalResource | null,
        search: '',
        pagination: undefined as Pagination | undefined,
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

    const refreshLocals = async () => {
        await getLocals(state.pagination?.current_page || 1, state.search);
    };

    // Methods
    const getLocals = async (page: number = 1, searchTerm: string = '') => {
        try {
            state.loading = true;
            const response = await LocalService.listLocals(page, searchTerm);
            if (response.success) {
                state.locals = response.locals;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, 'Error al cargar locales');
        } finally {
            state.loading = false;
        }
    };

    const storeLocal = async (localData: storeLocalRequest) => {
        try {
            const response = await LocalService.storeLocal(localData);
            if (handleApiResponse(response, 'Local creado exitosamente')) {
                closeModal('create');
                await refreshLocals();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear local');
        }
    };

    const deleteLocal = async (id: number) => {
        try {
            const response = await LocalService.deleteLocal(id);
            if (handleApiResponse(response, 'Local eliminado exitosamente')) {
                closeModal('delete');
                await refreshLocals();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar local');
        }
    };

    const getLocalById = async (id: number) => {
        try {
            const response = await LocalService.getLocalById(id);
            // response is LocalResource, not an object with success/local
            state.local = response;
            state.modals.edit = true;
        } catch (error) {
            handleApiError(error, 'Error al cargar local');
            closeModal('edit');
        }
    };

    const updateLocal = async (id: number, localData: updateLocalRequest) => {
        try {
            const response = await LocalService.updateLocal(id, localData);
            if (handleApiResponse(response, 'Local actualizado exitosamente')) {
                closeModal('edit');
                await refreshLocals();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar local');
        }
    };

    return {
        ...toRefs(state),
        getLocals,
        storeLocal,
        deleteLocal,
        getLocalById,
        updateLocal,
    };
};
