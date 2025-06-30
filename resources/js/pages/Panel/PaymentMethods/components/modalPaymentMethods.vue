<template>
    <Dialog :open="statusModal" @update:open="closeModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ isEditing ? 'Editar Método de Pago' : 'Crear Método de Pago' }}
                </DialogTitle>
                <DialogDescription>Asegurate de que la información sea correcta</DialogDescription>
            </DialogHeader>

            <!-- Formulario -->
            <form @submit.prevent="submitForm" class="flex flex-col gap-4 py-3">

                <!-- Ingresar el nombre -->
                 <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Nombre del metodo</FormLabel>
                        <FormControl>
                            <Input id="name" type="text" v-bind="componentField"/>
                        </FormControl>
                        <FormMessage/>
                    </FormItem>
                </FormField>

                <!-- Ingresar la descripción -->
                <FormField v-slot="{ componentField }" name="description">
                    <FormItem>
                        <FormLabel>Descripción</FormLabel>
                        <FormControl>
                            <Input id="description" type="text" v-bind="componentField"/>
                        </FormControl>
                        <FormMessage/>
                    </FormItem>
                </FormField>

                <!-- Ingresar el estado -->
                <FormField v-slot="{ value, handleChange }" type="checkbox" name="status">
                    <FormItem class="flex flex-row items-start space-y-0 gap-x-3 rounded-md border p-4">
                        <FormControl> <Checkbox :model-value="value" @update:model-value="handleChange" /> </FormControl>
                        <div class="space-y-1 leading-none">
                            <FormLabel>Estado</FormLabel>
                            <FormMessage />
                        </div>
                    </FormItem>
                </FormField>

                <!-- Botones -->
                <DialogFooter>
                    <Button type="submit" variant="default"> Guardar </Button>
                    <Button type="button" variant="secondary" @click="closeModal">Cancelar</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue';
import { PaymentMethodFormData, PaymentMethodResource, updatePaymentMethodRequest } from '../interfaces/PaymentMethod';
import { z } from 'zod';
import { useForm } from 'vee-validate';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import { FormField } from '@/components/ui/form';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormControl from '@/components/ui/form/FormControl.vue';
import Input from '@/components/ui/input/Input.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import { toTypedSchema } from '@vee-validate/zod';


interface Props {
    statusModal: boolean;
    paymentMethod?: PaymentMethodResource | null;
}
const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'close-modal', open: boolean): void;
    (e: 'create', data: any): void;
    (e: 'update', data: any): void;
}>();

// Computed para determinar si estamos editando
const isEditing = computed(() => !!props.paymentMethod?.id);

const closeModal = () => {
    emit('close-modal', false);
};

const formShema = toTypedSchema(
    z.object({
        name: z.string().min(3, 'El nombre de la membresia es requerido'),
        description: z.string().min(3, 'La descripción es requerida'),
        status: z.boolean(),
    })
);

const { handleSubmit, setValues } = useForm({
    validationSchema: formShema,
    initialValues: {
        name: props.paymentMethod?.name || '',
        description: props.paymentMethod?.description || '',
        status: props.paymentMethod?.status || true,
    },
});

const submitForm = handleSubmit((values: PaymentMethodFormData) => {
    console.log('Formulario enviado: ', values);
    if (props.paymentMethod) {
        const updatePaymentMethod: updatePaymentMethodRequest = {
            ...values,
            id: props.paymentMethod.id,
        };
        emit('update', updatePaymentMethod);
    } else {
        const newPaymentMethod = {
            ...values,
        };
        emit('create', newPaymentMethod);
    }
});

watch(
    () => props.paymentMethod,
    (newValue) => {
        if (newValue) {
            setValues({
                name: newValue.name,
                description: newValue.description,
                status: newValue.status || true,
            });
        }
    }
);

</script>

<style scoped>
</style>