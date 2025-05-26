import { Pagination } from '@/interfaces/paginacion';

export interface UserResource {
    id: number;
    name: string;
    username: string;
    photo: string;
    email: string;
    local: string;
    status: boolean;
    role: 'administrador' | 'personal';
}

export interface UsersTable {
    success: boolean;
    users: UserResource[];
    pagination: Pagination;
}

export interface StoreUserRequest {
    name: string;
    username: string;
    photo: File | null;
    email: string;
    password: string;
    local_id: number;
    role: 'administrador' | 'personal';
}

export interface UpdateUserRequest {
    name: string;
    username: string;
    photo: File | null;
    password: string | null;
    email: string;
    local_id: number | null;
    role: 'administrador' | 'personal';
    status: boolean;
}

export interface ResponsoUserStore {
    success: boolean;
    message: string;
    redirect_url: string;
    user: UserResource;
}
export interface ResponseUserDelete {
    success: boolean;
    message: string;
}

export interface ResponseUpdateUser {
    success: boolean;
    message: string;
    user: UserResource;
}

export interface UserId {
    success: string;
    user: UserResource;
}
