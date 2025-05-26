import { Pagination } from '@/interfaces/paginacion';

export interface LocalResource {
    id: number;
    name: string;
    address: string | null;
    status: boolean;
}

export interface LocalTable {
    success: boolean;
    locals: LocalResource[];
    pagination: Pagination;
}

export interface storeLocalRequest {
    name: string;
    address?: string | null;
    status: boolean;
}

export interface updateLocalRequest {
    name: string;
    address?: string | null;
    status: boolean;
}

export interface ResponseLocalStore {
    success: boolean;
    message: string;
    local: LocalResource;
}

export interface ResponseLocalUpdate {
    success: boolean;
    message: string;
    local: LocalResource;
}

export interface ResponseLocalDelete {
    success: boolean;
    message: string;
}
