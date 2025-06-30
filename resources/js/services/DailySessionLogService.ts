import {
    DailySessionLogTable,
    ResponseDailySessionLogDelete,
    ResponseDailySessionLogGetId,
    ResponseDailySessionLogStore,
    ResponseDailySessionLogUpdate,
    storeDailySessionLogRequest,
    updateDailySessionLogRequest,
} from '@/pages/Panel/DailySessionLogs/interfaces/DailySessionLog';

import axios, { AxiosError } from 'axios';

// Tipado para errores de validación y API
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

        if (axiosError.response?.status === 422) {
            const validationError = axiosError.response.data as ValidationError;
            const errorMessages = Object.entries(validationError.errors)
                .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                .join('\n');

            throw new Error(errorMessages);
        }

        const apiError = axiosError.response?.data as ApiError;
        throw new Error(apiError?.message || axiosError.message);
    }

    throw error instanceof Error ? error : new Error('Error desconocido');
};

export const DailySessionLogService = {
    async listDailySessionLogs(page: number, search: string): Promise<DailySessionLogTable> {
        try {
            const response = await axios.get(`/panel/list-dailySessionLogs?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async storeDailySessionLog(data: storeDailySessionLogRequest): Promise<ResponseDailySessionLogStore> {
        try {
            const response = await axios.post('/panel/dailySessionLogs', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async updateDailySessionLog(data: updateDailySessionLogRequest): Promise<ResponseDailySessionLogUpdate> {
        try {
            const response = await axios.put(`/panel/dailySessionLogs/${data.id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async deleteDailySessionLog(id: number): Promise<ResponseDailySessionLogDelete> {
        try {
            const response = await axios.delete(`/panel/dailySessionLogs/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async getDailySessionLogById(id: number): Promise<ResponseDailySessionLogGetId> {
        try {
            const response = await axios.get(`/panel/dailySessionLogs/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },
};
