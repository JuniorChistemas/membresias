import { Pagination } from "@/interfaces/paginacion";
import { PaymentMethodResource } from "@/pages/Panel/PaymentMethods/interfaces/PaymentMethod";


export interface DailySessionLogBase {
    name: string;
    price: number;
    payment_method_id: number;
    registered_at: string;
}

export interface DailySessionLogResource extends DailySessionLogBase {
    id: number;
    payment_method?: PaymentMethodResource;
}

export interface DailySessionLogTable extends DailySessionLogResource {
    success: boolean;
    dailySessionLogs: DailySessionLogResource[];
    pagination: Pagination;
}

export interface storeDailySessionLogRequest extends DailySessionLogBase {}
export interface updateDailySessionLogRequest extends DailySessionLogBase {
    id: number;
}

export type DailySessionLogFormData = storeDailySessionLogRequest | updateDailySessionLogRequest;

export interface ResponseDailySessionLogStore {
    success: boolean;
    message: string;
    daily_session_log: DailySessionLogResource;
}

export interface ResponseDailySessionLogUpdate {
    success: boolean;
    message: string;
    daily_session_log: DailySessionLogResource;
}
export interface ResponseDailySessionLogDelete {
    success: boolean;
    message: string;
}

export interface ResponseDailySessionLogGetId {
    success: boolean;
    daily_session_log: DailySessionLogResource;
}