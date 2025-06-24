import { Pagination } from "@/interfaces/paginacion";

export interface PaymentMethodBase {
    name: string;
    description?: string;
    status: boolean;
}

export interface PaymentMethodResource extends PaymentMethodBase {
    id: number;
}

export interface PaymentMethodTable extends PaymentMethodResource {
    success: boolean;
    paymentMethods: PaymentMethodResource[];
    pagination: Pagination;
}

export interface storePaymentMethodRequest extends PaymentMethodBase {}
export interface updatePaymentMethodRequest extends PaymentMethodBase {
    id: number;
}

export type PaymentMethodFormData = storePaymentMethodRequest | updatePaymentMethodRequest;

export interface ResponsePaymentMethodStore {
    success: boolean;
    message: string;
    payment_method: PaymentMethodResource;
}

export interface ResponsePaymentMethodUpdate {
    success: boolean;
    message: string;
    payment_method: PaymentMethodResource;
}

export interface ResponsePaymentMethodDelete {
    success: boolean;
    message: string;
}

export interface ResponsePaymentMethodGetId {
    success: boolean;
    payment_method: PaymentMethodResource;
}