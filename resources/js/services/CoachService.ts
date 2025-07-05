import {
    CoachTable,
    ResponseCoachDelete,
    ResponseCoachGetId,
    ResponseCoachStore,
    ResponseCoachUpdate,
    storeCoachRequest,
    updateCoachRequest
} from "@/pages/Panel/Coaches/interfaces/Coach";

import axios, { AxiosError } from "axios";

type ValidationError = {
    errors: Record<string, string[]>;
    message: string;
};

type ApiError = {
    message: string;
    status?: number;
};

const handleApiError = (error: unknown): never => {
    if (axios.isAxiosError(error)){
        const axiosError = error as AxiosError;

        // Validation error (422)
        if (axiosError.response?.status === 422) {
            const validationError = axiosError.response.data as ValidationError;
            const errorMessages = Object.entries(validationError.errors)
                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                .join('\n');

            throw new Error(errorMessages);
        }

        // Other HTTP errors
        const apiError = axiosError.response?.data as ApiError;
        throw new Error(apiError?.message || axiosError.message);
    }

    // Errores no relacionados a Axios
    throw error instanceof Error ? error : new Error('Error desconocido');
}

export const CoachService = {
    // List coaches
    async listCoaches(page: number, search: string): Promise<CoachTable> {
        try {
            const response = await axios.get(`/panel/list-coaches?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create coach
    async storeCoach(data: storeCoachRequest): Promise<ResponseCoachStore> {
        try {
            const response = await axios.post('/panel/coaches', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete coach
    async deleteCoach(id: number): Promise<ResponseCoachDelete> {
        try {
            const response = await axios.delete(`/panel/coaches/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get coach by ID
    async getCoachById(id: number): Promise<ResponseCoachGetId> {
        try {
            const response = await axios.get(`/panel/coaches/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update coach
    async updateCoach(id: number, data: updateCoachRequest): Promise<ResponseCoachUpdate> {
        try {
            const response = await axios.put(`/panel/coaches/${id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async getAll() {
        try {
            const response = await axios.get('/panel/coaches-all');
            return response.data;
        } catch (error) {
            // Maneja el error como en tus otros métodos
            throw error;
        }
    },

};