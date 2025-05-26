import {
    ResponseUpdateUser,
    ResponseUserDelete,
    ResponsoUserStore,
    StoreUserRequest,
    UpdateUserRequest,
    UserId,
    UsersTable,
} from '@/pages/Panel/Users/interfaces/User';
import axios from 'axios';

export const UserService = {
    //  list users
    async listUsers(page: number, name: string): Promise<UsersTable> {
        const response = await axios.get(`/panel/list-users?page=${page}&name=${name}`);
        return response.data;
    },
    // create user
    async StoreUser(data: StoreUserRequest): Promise<ResponsoUserStore> {
        try {
            const formData = new FormData();

            formData.append('name', data.name);
            formData.append('username', data.username);
            if (data.photo) {
                formData.append('photo', data.photo);
            }
            formData.append('email', data.email);
            if (data.password) {
                formData.append('password', data.password);
            }
            formData.append('local_id', data.local_id.toString());
            formData.append('role', data.role);

            const response = await axios.post('/panel/users', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            return response.data;
        } catch (error) {
            if (axios.isAxiosError(error)) {
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMessage = '';
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += `${key}: ${errors[key].join(', ')}\n`;
                        }
                    }
                    throw new Error(errorMessage);
                }
            }
            throw error;
        }
    },
    // delete user
    async deleteUser(id: number): Promise<ResponseUserDelete> {
        const response = await axios.delete(`/panel/users/${id}`);
        return response.data;
    },
    // get user by id
    async getUserById(id: number): Promise<UserId> {
        const response = await axios.get(`/panel/users/${id}`);
        return response.data;
    },
    // update user
    async updateUser(id: number, data: UpdateUserRequest): Promise<ResponseUpdateUser> {
        try {
            const formData = new FormData();
            formData.append('name', data.name);
            formData.append('username', data.username);
            if (data.photo) {
                formData.append('photo', data.photo);
            }
            if (data.password) {
                formData.append('password', data.password);
            }
            formData.append('email', data.email);
            if (data.local_id !== null) {
                formData.append('local_id', data.local_id.toString());
            }
            formData.append('role', data.role);
            formData.append('status', data.status ? '1' : '0');
            formData.append('_method', 'PUT');
            const response = await axios.post(`/panel/users/${id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Accept: 'application/json',
                },
            });
            return response.data;
        } catch (error) {
            if (axios.isAxiosError(error)) {
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMessage = '';
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += `${key}: ${errors[key].join(', ')}\n`;
                        }
                    }
                    throw new Error(errorMessage);
                }
            }
            throw error;
        }
    },
};
