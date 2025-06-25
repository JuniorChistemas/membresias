import { Pagination } from "@/interfaces/paginacion";

export interface CoachBase {
    name: string;
    dni: string;
    specialty: string;
    phone?: string | null;
    email?: string | null;
    address?: string | null;
    status: boolean;
}

export interface CoachResource extends CoachBase {
    id: number;
}

export interface CoachTable extends CoachResource {
    success: boolean;
    coaches: CoachResource[];
    pagination: Pagination;
}

export interface storeCoachRequest extends CoachBase {}
export interface updateCoachRequest extends CoachBase {
    id: number;
}

export type CoachFormData = storeCoachRequest | updateCoachRequest;

export interface ResponseCoachStore {
    success: boolean;
    message: string;
    coach: CoachResource;
}

export interface ResponseCoachUpdate {
    success: boolean;
    message: string;
    coach: CoachResource;
}

export interface ResponseCoachDelete {
    success: boolean;
    message: string;
}

export interface ResponseCoachGetId {
    success: boolean;
    coach: CoachResource;
}