import {
    PaymentMethodTable,
    ResponsePaymentMethodDelete,
    ResponsePaymentMethodGetId,
    ResponsePaymentMethodStore,
    ResponsePaymentMethodUpdate,
    storePaymentMethodRequest,
    updatePaymentMethodRequest
} from '@/pages/Panel/PaymentMethods/interfaces/PaymentMethod';

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

export const PaymentMethodService = {
    // List payment methods
    async listPaymentMethods(page: number, search: string): Promise<PaymentMethodTable> {
        try {
            const response = await axios.get(`/panel/list-paymentMethods?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create payment method
    async storePaymentMethod(data: storePaymentMethodRequest): Promise<ResponsePaymentMethodStore> {
        try {
            const response = await axios.post('/panel/paymentMethods', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update payment method
    async updatePaymentMethod(data: updatePaymentMethodRequest): Promise<ResponsePaymentMethodUpdate> {
        try {
            const response = await axios.put(`/panel/paymentMethods/${data.id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete payment method
    async deletePaymentMethod(id: number): Promise<ResponsePaymentMethodDelete> {
        try {
            const response = await axios.delete(`/panel/paymentMethods/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get payment method by ID
    async getPaymentMethodById(id: number): Promise<ResponsePaymentMethodGetId> {
        try {
            const response = await axios.get(`/panel/paymentMethods/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    }
};