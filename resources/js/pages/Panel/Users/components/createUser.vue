<template>
    <Head title="Nuevo Usuario"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card class="mt-4 flex flex-col gap-4">
                <CardHeader>
                    <CardTitle>NUEVO USUARIO</CardTitle>
                    <CardDescription>Complete los campos para registrar un nuevo usuario</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="onSubmit" class="flex flex-col gap-6">
                        <!-- Nombre -->
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem>
                                <FormLabel>Nombre</FormLabel>
                                <FormControl>
                                    <Input type="text" placeholder="Nombre" v-bind="componentField" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Usuario -->
                        <FormField v-slot="{ componentField }" name="username">
                            <FormItem>
                                <FormLabel>Nombre de usuario</FormLabel>
                                <FormControl>
                                    <Input type="text" placeholder="Nombre de usuario" v-bind="componentField" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Email -->
                        <FormField v-slot="{ componentField }" name="email">
                            <FormItem>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input type="email" placeholder="Email" v-bind="componentField" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Contraseña -->
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem>
                                <FormLabel>Contraseña</FormLabel>
                                <FormControl>
                                    <Input type="password" placeholder="Contraseña (mínimo 8 caracteres)" v-bind="componentField" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Foto -->
                        <FormField v-slot="{ componentField }" name="photo">
                            <FormItem>
                                <FormLabel>Foto de perfil</FormLabel>
                                <FormControl>
                                    <Input type="file" accept="image/jpeg,image/jpg,image/png" @change="handlePhotoUpload" />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Local -->
                        <FormField name="local_id">
                            <FormItem>
                                <FormLabel>Local</FormLabel>
                                <FormControl>
                                    <Select @update:modelValue="(id) => setFieldValue('local_id', id ? Number(id) : 0)">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Seleccionar local" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectLabel>Locales</SelectLabel>
                                                <SelectItem v-for="local in props.locals" :key="local.id" :value="local.id">
                                                    {{ local.name }}
                                                </SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Rol -->
                        <FormField v-slot="{ componentField }" name="role">
                            <FormItem>
                                <FormLabel>Rol</FormLabel>
                                <FormControl>
                                    <Select v-bind="componentField">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Seleccionar rol" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                                <SelectLabel>Roles</SelectLabel>
                                                <SelectItem value="administrador">Administrador</SelectItem>
                                                <SelectItem value="personal">Personal</SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Botones -->
                        <div class="container flex justify-end gap-4">
                            <Button type="submit" variant="default">Guardar</Button>
                            <Button type="reset" variant="outline">Cancelar</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { FormControl, FormField, FormLabel } from '@/components/ui/form';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useUser } from '@/composables/useUser';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { z } from 'zod';
import { StoreUserRequest } from '../interfaces/User';

const { storeUser } = useUser();

// Interfaces
interface Local {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
}

// Props
const props = defineProps<{
    locals: Local[];
    roles: Role[];
}>();

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuarios',
        href: '/panel/users',
    },
    {
        title: 'Crear Usuario',
        href: '/panel/users/create',
    },
];

// Validación del formulario con zod
const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(1, 'El nombre es requerido').max(100, 'El nombre no puede exceder 100 caracteres'),
        username: z.string().min(1, 'El nombre de usuario es requerido').max(30, 'El nombre de usuario no puede exceder 30 caracteres'),
        email: z.string().email('El email no es válido').min(1, 'El email es requerido'),
        password: z.string().min(8, 'La contraseña debe tener al menos 8 caracteres'),
        photo: z.any().nullable(),
        local_id: z.number().min(1, 'Debe seleccionar un local'),
        role: z.enum(['administrador', 'personal'], {
            errorMap: () => ({ message: 'Debe seleccionar un rol válido' }),
        }),
    }),
);

// Configuración del formulario con vee-validate
const { handleSubmit, setFieldValue } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: '',
        username: '',
        email: '',
        password: '',
        photo: null,
        local_id: 0,
        role: '' as 'administrador' | 'personal',
    },
});

// Manejador de subida de archivos
const handlePhotoUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        setFieldValue('photo', target.files[0]);
    }
};

// Envío del formulario
const onSubmit = handleSubmit((values) => {
    const userData: StoreUserRequest = {
        name: values.name,
        username: values.username,
        email: values.email,
        password: values.password,
        photo: values.photo,
        local_id: values.local_id,
        role: values.role,
    };
    console.log('Datos del usuario:', userData);
    storeUser(userData)
        .then(() => {
            console.log('Usuario creado con éxito');
        })
        .catch((error) => {
            // Manejar errores
            console.error('Error al crear el usuario:', error);
        });
});
</script>

<style scoped></style>
