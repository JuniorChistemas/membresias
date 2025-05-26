import {
    LocalResource,
    LocalTable,
    ResponseLocalDelete,
    ResponseLocalStore,
    ResponseLocalUpdate,
    storeLocalRequest,
    updateLocalRequest,
} from '@/pages/Panel/Locals/interfaces/Local';
import axios, { AxiosError } from 'axios';

type ValidationError = {
    errors: Record<string, string[]>;
    message: string;
};

type ApiError = {
    message: string;
    status?: number;
};

const handleApiError = (error: unknown): never => {
    if (axios.isAxiosError(error)) {
        const axiosError = error as AxiosError;

        // Error de validación (422)
        if (axiosError.response?.status === 422) {
            const validationError = axiosError.response.data as ValidationError;
            const errorMessages = Object.entries(validationError.errors)
                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                .join('\n');

            throw new Error(errorMessages);
        }

        // Otros errores HTTP
        const apiError = axiosError.response?.data as ApiError;
        throw new Error(apiError?.message || axiosError.message);
    }

    // Errores no relacionados a Axios
    throw error instanceof Error ? error : new Error('Error desconocido');
};

export const LocalService = {
    // List locals
    async listLocals(page: number, search: string): Promise<LocalTable> {
        try {
            const response = await axios.get(`/panel/list-locals?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create local
    async storeLocal(data: storeLocalRequest): Promise<ResponseLocalStore> {
        try {
            const response = await axios.post('/panel/locals', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete local
    async deleteLocal(id: number): Promise<ResponseLocalDelete> {
        try {
            const response = await axios.delete(`/panel/locals/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get local by ID
    async getLocalById(id: number): Promise<LocalResource> {
        try {
            const response = await axios.get(`/panel/locals/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update local
    async updateLocal(id: number, data: updateLocalRequest): Promise<ResponseLocalUpdate> {
        try {
            const response = await axios.put(`/panel/locals/${id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },
};
