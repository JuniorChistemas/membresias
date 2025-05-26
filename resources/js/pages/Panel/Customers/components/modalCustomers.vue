<template>
    <Dialog :open="statusModal" @update:open="closeModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ isEditing ? 'Editar Cliente' : 'Nuevo Cliente' }}
                </DialogTitle>
                <DialogDescription> Asegúrate de que la información sea correcta. </DialogDescription>
            </DialogHeader>
            <!-- formulario -->
            <form @submit.prevent="submitForm" class="flex flex-col gap-4 py-3">
                <FormField v-slot="{ componentField }" name="first_name">
                    <FormItem>
                        <FormLabel>Nombre</FormLabel>
                        <FormControl>
                            <Input id="first_name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="last_name">
                    <FormItem>
                        <FormLabel>Apellidos</FormLabel>
                        <FormControl>
                            <Input id="last_name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="code">
                    <FormItem>
                        <FormLabel>Código</FormLabel>
                        <FormControl>
                            <Input id="code" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="phone">
                    <FormItem>
                        <FormLabel>Teléfono</FormLabel>
                        <FormControl>
                            <Input id="phone" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input id="email" type="email" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="address">
                    <FormItem>
                        <FormLabel>Dirección</FormLabel>
                        <FormControl>
                            <Input id="address" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ value, handleChange }" type="checkbox" name="status">
                    <FormItem class="flex flex-row items-start space-y-0 gap-x-3 rounded-md border p-4">
                        <FormControl> <Checkbox :model-value="value" @update:model-value="handleChange" /> </FormControl>
                        <div class="space-y-1 leading-none">
                            <FormLabel>Estado</FormLabel>
                            <FormMessage />
                        </div>
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
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import { FormField } from '@/components/ui/form';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import Input from '@/components/ui/input/Input.vue';
import { toTypedSchema } from '@vee-validate/zod';
import { useForm } from 'vee-validate';
import { computed, watch } from 'vue';
import { z } from 'zod';
import { CustomerFormData, CustomerResource, storeCustomerRequest, updateCustomerRequest } from '../interfaces/Customer';

interface Props {
    statusModal: boolean;
    customer?: CustomerResource | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'close-modal', open: boolean): void;
    (e: 'create', data: storeCustomerRequest): void;
    (e: 'update', data: updateCustomerRequest): void;
}>();

// Computed para determinar si estamos editando
const isEditing = computed(() => !!props.customer?.id);

const closeModal = () => {
    emit('close-modal', false);
};

const formShema = toTypedSchema(
    z.object({
        first_name: z.string().min(3, 'El nombre es requerido'),
        last_name: z.string().min(3, 'El apellidos es requerido'),
        code: z.string(),
        phone: z.string().nullable().default(null),
        email: z.string().email('El email no es válido').min(3, 'El email es requerido'),
        address: z.string().nullable().default(null),
        status: z.boolean(),
    }),
);

const { handleSubmit, setValues } = useForm({
    validationSchema: formShema,
    initialValues: {
        first_name: props.customer?.first_name || '',
        last_name: props.customer?.last_name || '',
        code: props.customer?.code || '',
        phone: props.customer?.phone || null,
        email: props.customer?.email || '',
        address: props.customer?.address || null,
        status: props.customer?.status || false,
    },
});

const submitForm = handleSubmit((values: CustomerFormData) => {
    console.log('Formulario enviado:', values);
    if (props.customer) {
        const updatedCustomer: updateCustomerRequest = {
            ...values,
            id: props.customer.id,
        };
        emit('update', updatedCustomer);
    } else {
        const newCustomer: storeCustomerRequest = {
            ...values,
        };
        emit('create', newCustomer);
    }
});

watch(
    () => props.customer,
    (newValue) => {
        if (newValue) {
            setValues({
                first_name: newValue.first_name,
                last_name: newValue.last_name,
                code: newValue.code || '',
                phone: newValue.phone || null,
                email: newValue.email || '',
                address: newValue.address || null,
                status: newValue.status || false,
            });
        }
    },
);
</script>
<style scoped></style>
