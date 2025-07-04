import { Pagination } from "@/interfaces/paginacion";
import { CoachResource, storeCoachRequest, updateCoachRequest } from "@/pages/Panel/Coaches/interfaces/Coach";
import { CoachService } from "@/services/CoachService";
import { showErrorMessage, showSuccessMessage } from "@/utils/messages";
import { reactive, toRefs } from "vue";

type ModalType = "createEdit" | "delete";

export const useCoach = () => {
    // State
    const state = reactive({
        coaches: [] as CoachResource[],
        coach: null as CoachResource | null,
        search: "",
        pagination: {
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        } as Pagination,
        loading: false,
        message: "",
        modals: {
            createEdit: false,
            delete: false,
        },
    });

    // Helpers
    const handleApiResponse = (response: { success: boolean; message: string }, successMessage?: string) => {
        if (response.success) {
            state.message = response.message;
            showSuccessMessage("Éxito", successMessage || response.message);
            return true;
        }
        state.message = response.message;
        return false;
    };

    const handleApiError = (error: unknown, defaultMessage = "Error desconocido") => {
        console.error("API Error:", error);
        const errorMessage = error instanceof Error ? error.message : defaultMessage;
        state.message = errorMessage;
        showErrorMessage("Error", errorMessage);
    };

    const openModal = (modalType: ModalType) => {
        state.modals[modalType] = true;
        if (modalType === "createEdit") {
            state.coach = null;
        }
    };

    const closeModal = (modalType: ModalType) => {
        state.modals[modalType] = false;
        if (modalType === "createEdit") {
            state.coach = null;
        }
    };

    const refreshCoaches = async () => {
        await getCoaches(state.pagination?.current_page || 1, state.search);
    };

    const getCoaches = async (page: number = 1, searchTerm: string = '') => {
        try {
            state.loading = true;
            const response = await CoachService.listCoaches(page, searchTerm);
            if (response.success) {
                state.coaches = response.coaches;
                state.pagination = response.pagination;
            }
        } catch (error) {
            handleApiError(error, "Error al cargar los entrenadores");
        } finally {
            state.loading = false;
        }
    };

    const storeCoach = async (coachData: storeCoachRequest) => {
        try{
            const response = await CoachService.storeCoach(coachData);
            if (handleApiResponse(response, "Entrenador creado correctamente")) {
                closeModal("createEdit");
                await refreshCoaches();
            }
        } catch (error) {
            handleApiError(error, "Error al crear el coach");
        }
    };

    const deleteCoach = async (id: number) => {
        try {
            const response = await CoachService.deleteCoach(id);
            if (handleApiResponse(response, "Entrenador eliminado correctamente")) {
                closeModal("delete");
                await refreshCoaches();
            }
        } catch (error) {
            handleApiError(error, "Error al eliminar el coach");
        }
    };

    const getCoachById = async (id: number) => {
        try {
            const response = await CoachService.getCoachById(id);
            if (response.success) {
                state.coach = response.coach;
                state.modals.createEdit = true;
            }
        } catch (error) {
            handleApiError(error, "Error al cargar el entrenador");
            closeModal("createEdit");
        } 
    };

    const updateCoach = async (id: number, coachData: updateCoachRequest) => {
        try {
            const response = await CoachService.updateCoach(id, coachData);
            if (handleApiResponse(response, "Entrenador actualizado correctamente")) {
                closeModal("createEdit");
                await refreshCoaches();
            }
        } catch (error) {
            handleApiError(error, "Error al actualizar el entrenador");
        }
    };

    return {
        ...toRefs(state),
        getCoaches,
        storeCoach,
        deleteCoach,
        getCoachById,
        updateCoach,
        openModal,
        closeModal,
    };
}