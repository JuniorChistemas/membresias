<template>
    <Dialog :open="open" @update:open="closeModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Editar usuario</DialogTitle>
                <DialogDescription> Asegúrate de que la información sea correcta. </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="onSubmit" class="flex flex-col gap-4 py-3">
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Nombre</FormLabel>
                        <FormControl>
                            <Input id="name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage class="bg-red-400" />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="username">
                    <FormItem>
                        <FormLabel>Nombre de usuario</FormLabel>
                        <FormControl>
                            <Input id="username" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage class="bg-red-400" />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input id="email" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage class="bg-red-400" />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="password">
                    <FormItem>
                        <FormLabel>Contraseña</FormLabel>
                        <FormControl>
                            <Input id="password" type="password" v-bind="componentField" />
                        </FormControl>
                        <FormMessage class="bg-red-400" />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="role">
                    <FormItem>
                        <FormLabel>Rol</FormLabel>
                        <Select v-bind="componentField">
                            <FormControl>
                                <SelectTrigger>
                                    <SelectValue placeholder="Rol" />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectItem value="administrador">Administrador</SelectItem>
                                <SelectItem value="personal">Personal</SelectItem>
                            </SelectContent>
                        </Select>
                    </FormItem>
                    <FormMessage class="bg-red-400" />
                </FormField>
                <FormField v-slot="{ value, handleChange }" type="checkbox" name="status">
                    <FormItem class="flex flex-row items-start space-y-0 gap-x-3 rounded-md border p-4">
                        <FormControl> <Checkbox :model-value="value" @update:model-value="handleChange" /> </FormControl>
                        <div class="space-y-1 leading-none">
                            <FormLabel>Estado</FormLabel>
                            <FormMessage class="bg-red-400" />
                        </div>
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="photo">
                    <FormItem>
                        <FormLabel>Foto</FormLabel>
                        <FormControl>
                            <Input type="file" accept="image/jpeg,image/jpg,image/png" @change="handlePhotoUpload" />
                        </FormControl>
                    </FormItem>
                </FormField>
                <DialogFooter>
                    <Button type="submit" variant="default"> Guardar </Button>
                    <Button type="button" variant="secondary" @click="closeModal">Cancelar</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { watch } from 'vue';
import { z } from 'zod';
import { UpdateUserRequest, UserResource } from '../interfaces/User';

const props = defineProps<{
    user: UserResource;
    open: boolean;
}>();

const emit = defineEmits<{
    (e: 'close-modal', open: boolean): void;
    (e: 'update-user', user_update: UpdateUserRequest, user_id: number): void;
}>();
const closeModal = () => {
    emit('close-modal', false);
};

const formShema = toTypedSchema(
    z.object({
        name: z.string().min(3, 'El nombre es requerido'),
        username: z.string().min(3, 'El nombre de usuario es requerido'),
        photo: z.any().nullable(),
        password: z.string().nullable().default(null),
        email: z.string().email('El email no es válido').min(3, 'El email es requerido'),
        local_id: z.number().nullable().default(null),
        role: z.enum(['administrador', 'personal'], { required_error: 'El rol es requerido' }),
        status: z.boolean(),
    }),
);

const { handleSubmit, setValues, setFieldValue } = useForm({
    validationSchema: formShema,
    initialValues: {
        name: props.user.name,
        email: props.user.email,
        username: props.user.username,
        local_id: null,
        password: null,
        status: props.user.status,
        role: props.user.role,
    },
});

watch(
    () => props.user,
    (newValue) => {
        setValues({
            name: newValue.name,
            email: newValue.email,
            username: newValue.username,
            local_id: null,
            password: null,
            status: newValue.status,
            role: newValue.role,
        });
    },
);

const onSubmit = handleSubmit((values) => {
    const user_update: UpdateUserRequest = {
        name: values.name,
        email: values.email,
        username: values.username,
        password: values.password,
        local_id: null,
        role: values.role,
        status: values.status,
        photo: values.photo,
    };
    emit('update-user', user_update, props.user.id);
    console.log(user_update);
});

const handlePhotoUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        setFieldValue('photo', target.files[0]);
    }
};
</script>
<style scoped></style>
