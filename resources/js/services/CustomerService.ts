import {
    CustomerTable,
    ResponseCustomerDelete,
    ResponseCustomerGetId,
    ResponseCustomerStore,
    ResponseCustomerUpdate,
    storeCustomerRequest,
    updateCustomerRequest,
} from '@/pages/Panel/Customers/interfaces/Customer';
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

export const CustomerService = {
    // List customers
    async listCustomers(page: number, search: string): Promise<CustomerTable> {
        try {
            const response = await axios.get(`/panel/list-customers?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create customer
    async storeCustomer(data: storeCustomerRequest): Promise<ResponseCustomerStore> {
        try {
            const response = await axios.post('/panel/customers', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete customer
    async deleteCustomer(id: number): Promise<ResponseCustomerDelete> {
        try {
            const response = await axios.delete(`/panel/customers/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get customer by ID
    async getCustomerById(id: number): Promise<ResponseCustomerGetId> {
        try {
            const response = await axios.get(`/panel/customers/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update customer
    async updateCustomer(id: number, data: updateCustomerRequest): Promise<ResponseCustomerUpdate> {
        try {
            const response = await axios.put(`/panel/customers/${id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },
};
