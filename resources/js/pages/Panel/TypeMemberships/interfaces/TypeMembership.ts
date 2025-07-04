import { Pagination } from "@/interfaces/paginacion";

export interface TypeMembershipBase {
    name: string;
    description?: string;
    price: number;
    status: boolean;
}

export interface TypeMembershipResource extends TypeMembershipBase {
    id: number;
}

export interface TypeMembershipTable extends TypeMembershipResource {
    success: boolean;
    typeMemberships: TypeMembershipResource[];
    pagination: Pagination;
}

export interface storeTypeMembershipRequest extends TypeMembershipBase {}
export interface updateTypeMembershipRequest extends TypeMembershipBase {
    id: number;
}

export type TypeMembershipFormData = storeTypeMembershipRequest | updateTypeMembershipRequest;

export interface ResponseTypeMembershipStore {
    success: boolean;
    message: string;
    typeMembership: TypeMembershipResource;
}

export interface ResponseTypeMembershipUpdate {
    success: boolean;
    message: string;
    typeMembership: TypeMembershipResource;
}

export interface ResponseTypeMembershipDelete {
    success: boolean;
    message: string;
}

export interface ResponseTypeMembershipGetId {
    success: boolean;
    typeMembership: TypeMembershipResource;
}
