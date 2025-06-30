import { Pagination } from "@/interfaces/paginacion";
import { TypeMembershipResource, storeTypeMembershipRequest, updateTypeMembershipRequest } from '@/pages/Panel/TypeMemberships/interfaces/TypeMembership';
import { TypeMembershipService } from "@/services/TypeMembershipService";
import { showErrorMessage, showSuccessMessage } from "@/utils/messages";
import { reactive, toRefs } from "vue";

type ModalType = 'createEdit' | 'delete';

export const useTypeMembership = () => {
    // State
    const state = reactive({
        typeMemberships: [] as TypeMembershipResource[],
        typeMembership: null as TypeMembershipResource | null,
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
            state.typeMembership = null;
        }
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
        if (modalType === 'createEdit') {
            state.typeMembership = null;
        }
    };

    const refreshTypeMemberships = async () => {
        await getTypeMemberships(state.pagination?.current_page || 1, state.search);
    };

    // Methods

    const getTypeMemberships = async (page: number = 1, searchTerm: string = '') => {
        try {
            state.loading = true;
            const response = await TypeMembershipService.listTypeMemberships(page, searchTerm);
            if (response.success) {
                state.typeMemberships = response.typeMemberships;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, 'Error al obtener los tipos de membresía');
        } finally {
            state.loading = false;
        }
    };

    const storeTypeMembership = async (typeMembershipData: storeTypeMembershipRequest) => {
        try {
            const response = await TypeMembershipService.storeTypeMembership(typeMembershipData);
            if (handleApiResponse(response, 'Tipo de membresía creada')) {
                closeModal('createEdit');
                await refreshTypeMemberships();
            }
        } catch (error) {
            handleApiError(error, 'Error al crear el tipo de membresía');
        }
    };

    const deleteTypeMembership = async (id: number) => {
        try {
            const response = await TypeMembershipService.deleteTypeMembership(id);
            if (handleApiResponse(response, 'Tipo de membresía eliminada')) {
                closeModal('delete');
                await refreshTypeMemberships();
            }
        } catch (error) {
            handleApiError(error, 'Error al eliminar el tipo de membresía');
        }
    };

    const getTypeMembershipById = async (id: number) => {
        try {
            const response = await TypeMembershipService.getTypeMembershipById(id);
            if (response.success){
                state.typeMembership = response.type_membership;
                state.modals.createEdit = true;
            }
        } catch (error) {
            handleApiError(error, 'Error al cargar el tipo de membresía');
            closeModal('createEdit');
        }
    };

    const updateTypeMembership = async (id: number, typeMembershipData: updateTypeMembershipRequest) => {
        try {
            const response = await TypeMembershipService.updateTypeMembership(id, typeMembershipData);
            if (handleApiResponse(response, 'Tipo de membresía actualizada')) {
                closeModal('createEdit');
                await refreshTypeMemberships();
            }
        } catch (error) {
            handleApiError(error, 'Error al actualizar el tipo de membresía');
        }
    };

    return {
        ...toRefs(state),
        getTypeMemberships,
        storeTypeMembership,
        deleteTypeMembership,
        getTypeMembershipById,
        updateTypeMembership,
        openModal,
        closeModal,
    };
}