import {
    TypeMembershipTable,
    ResponseTypeMembershipDelete,
    ResponseTypeMembershipGetId,
    ResponseTypeMembershipStore,
    ResponseTypeMembershipUpdate,
    storeTypeMembershipRequest,
    updateTypeMembershipRequest,
} from '@/pages/Panel/TypeMemberships/interfaces/TypeMembership';

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

export const TypeMembershipService = {
    // List type memberships
    async listTypeMemberships(page: number, search: string): Promise<TypeMembershipTable> {
        try {
            const response = await axios.get(`/panel/list-typeMemberships?page=${page}&search=${search}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Create type membership
    async storeTypeMembership(data: storeTypeMembershipRequest): Promise<ResponseTypeMembershipStore> {
        try {
            const response = await axios.post('/panel/typeMemberships', data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Update type membership
    async updateTypeMembership(id: number, data: updateTypeMembershipRequest): Promise<ResponseTypeMembershipUpdate> {
        try {
            const response = await axios.put(`/panel/typeMemberships/${id}`, data);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Delete type membership
    async deleteTypeMembership(id: number): Promise<ResponseTypeMembershipDelete> {
        try {
            const response = await axios.delete(`/panel/typeMemberships/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    // Get type membership by ID
    async getTypeMembershipById(id: number): Promise<ResponseTypeMembershipGetId> {
        try {
            const response = await axios.get(`/panel/typeMemberships/${id}`);
            return response.data;
        } catch (error) {
            return handleApiError(error);
        }
    },

    async getAll() {
        try {
            const response = await axios.get('/panel/typeMemberships-all');
            return response.data;
        } catch (error) {
            // Maneja el error como en tus otros métodos
            throw error;
        }
    },
};