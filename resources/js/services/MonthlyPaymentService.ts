import {
    MonthlyPaymentTable,
    ResponseMonthlyPaymentDelete,
    ResponseMonthlyPaymentGetId,
    ResponseMonthlyPaymentStore,
    ResponseMonthlyPaymentUpdate,
    StoreMonthlyPaymentRequest,
    UpdateMonthlyPaymentRequest
} from '@/pages/Panel/MonthlyPayments/interfaces/MonthlyPayment';

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

    // Non-Axios errors
    throw error instanceof Error ? error : new Error('Unknown error');
};

export const MonthlyPaymentService = {
    // List monthly payments
    async listMonthlyPayments(page: number, search: string): Promise<MonthlyPaymentTable> {
        try {
            const response = await axios.get(`/panel/list-monthlyPayments?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create monthly payment
    async storeMonthlyPayment(data: StoreMonthlyPaymentRequest): Promise<ResponseMonthlyPaymentStore> {
        try {
            const response = await axios.post('/panel/monthlyPayments', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update monthly payment
    async updateMonthlyPayment(data: UpdateMonthlyPaymentRequest): Promise<ResponseMonthlyPaymentUpdate> {
        try {
            const response = await axios.put(`/panel/monthlyPayments/${data.id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete monthly payment
    async deleteMonthlyPayment(id: number): Promise<ResponseMonthlyPaymentDelete> {
        try {
            const response = await axios.delete(`/panel/monthlyPayments/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get monthly payment by ID
    async getMonthlyPaymentById(id: number): Promise<ResponseMonthlyPaymentGetId> {
        try {
            const response = await axios.get(`/panel/monthlyPayments/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    }
};