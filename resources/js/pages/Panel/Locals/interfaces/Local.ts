import { Pagination } from '@/interfaces/paginacion';

export interface LocalBase {
    name: string;
    address?: string | null;
    status: boolean;
}

export interface LocalResource extends LocalBase {
    id: number;
}

export interface LocalTable {
    success: boolean;
    locals: LocalResource[];
    pagination: Pagination;
}

export interface storeLocalRequest extends LocalBase {}

export interface updateLocalRequest extends LocalBase {
    id: number;
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

export interface ResponseLocalGetId {
    success: boolean;
    local: LocalResource;
}
