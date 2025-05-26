import { Pagination } from '@/interfaces/paginacion';

export interface CustomerResource {
    id: number;
    first_name: string;
    last_name: string;
    code: string;
    phone: string | null;
    email: string | null;
    address: string | null;
    status: boolean;
}

export interface CustomerTable extends CustomerResource {
    success: boolean;
    customers: CustomerResource[];
    pagination: Pagination;
}

export interface storeCustomerRequest {
    first_name: string;
    last_name: string;
    code: string;
    phone?: string | null;
    email?: string | null;
    address?: string | null;
    status: boolean;
}
export interface updateCustomerRequest {
    first_name: string;
    last_name: string;
    code: string;
    phone?: string | null;
    email?: string | null;
    address?: string | null;
    status: boolean;
}

export interface ResponseCustomerStore {
    success: boolean;
    message: string;
    customer: CustomerResource;
}

export interface ResponseCustomerUpdate {
    success: boolean;
    message: string;
    customer: CustomerResource;
}

export interface ResponseCustomerDelete {
    success: boolean;
    message: string;
}

export interface ResponseCustomerGetId {
    success: boolean;
    customer: CustomerResource;
}
