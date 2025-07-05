import { Pagination } from '@/interfaces/paginacion';

export interface MonthlyPaymentBase {
    customerId: number;
    typeMembershipId: number;
    paymentMethodId: number;
    coachId: number;
    monthsTotal: number;
    price: number;
    total: number;
    dateRegistration: string; // formato YYYY-MM-DD
    status: boolean;
}

export interface MonthlyPaymentResource extends MonthlyPaymentBase {
    id: number;
    // Relacionales para mostrar en tablas y formularios
    customerName?: string;
    typeMembershipName?: string;
    typeMembershipPrice?: number;
    paymentMethodName?: string;
    coachName?: string;
}

export interface MonthlyPaymentTable {
    success: boolean;
    monthlyPayments: MonthlyPaymentResource[];
    pagination: Pagination;
}

export interface StoreMonthlyPaymentRequest extends MonthlyPaymentBase {}
export interface UpdateMonthlyPaymentRequest extends MonthlyPaymentBase {
    id: number;
}

export type MonthlyPaymentFormData = StoreMonthlyPaymentRequest | UpdateMonthlyPaymentRequest;

export interface ResponseMonthlyPaymentStore {
    success: boolean;
    message: string;
    monthlyPayment: MonthlyPaymentResource;
}

export interface ResponseMonthlyPaymentUpdate {
    success: boolean;
    message: string;
    monthlyPayment: MonthlyPaymentResource;
}

export interface ResponseMonthlyPaymentDelete {
    success: boolean;
    message: string;
}

export interface ResponseMonthlyPaymentGetId {
    success: boolean;
    monthlyPayment: MonthlyPaymentResource;
}