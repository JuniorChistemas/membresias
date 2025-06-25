<template>
    <Dialog :open="statusModal" @update:open="closeModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ isEditing ? 'Editar entrenador' : 'Nuevo entrenador' }}
                </DialogTitle>
                <DialogDescription> Asegúrate de que la información sea correcta. </DialogDescription>
            </DialogHeader>
            <!-- formulario -->
            <form @submit.prevent="submitForm" class="flex flex-col gap-4 py-3">
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Nombre</FormLabel>
                        <FormControl>
                            <Input id="name" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="dni">
                    <FormItem>
                        <FormLabel>DNI</FormLabel>
                        <FormControl>
                            <Input id="dni" type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="specialty">
                    <FormItem>
                        <FormLabel>Especialidad</FormLabel>
                        <FormControl>
                            <Input id="specialty" type="text" v-bind="componentField" />
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
import { CoachFormData, CoachResource, storeCoachRequest, updateCoachRequest } from '../interfaces/Coach';

interface Props {
    statusModal: boolean;
    coach?: CoachResource | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'close-modal', open: boolean): void;
    (e: 'create', data: storeCoachRequest): void;
    (e: 'update', data: updateCoachRequest): void;
}>();

// Computed para determinar si estamos editando
const isEditing = computed(() => !!props.coach?.id);

const closeModal = () => {
    emit('close-modal', false);
};

const formShema = toTypedSchema(
    z.object({
        name: z.string().min(3, 'El nombre es requerido'),
        dni: z.string().min(3, 'El dni es requerido'),
        specialty: z.string().min(3, 'La especialidad es requerido'),
        phone: z.string().nullable().default(null),
        email: z.string().email('El email no es válido').min(3, 'El email es requerido'),
        address: z.string().nullable().default(null),
        status: z.boolean(),
    }),
);

const { handleSubmit, setValues } = useForm({
    validationSchema: formShema,
    initialValues: {
        name: props.coach?.name || '',
        dni: props.coach?.dni || '',
        specialty: props.coach?.specialty || '',
        phone: props.coach?.phone || null,
        email: props.coach?.email || '',
        address: props.coach?.address || null,
        status: props.coach?.status || false,
    },
});

const submitForm = handleSubmit((values: CoachFormData) => {
    console.log('Formulario enviado:', values);
    if (props.coach) {
        const updateCoach: updateCoachRequest = {
            ...values,
            id: props.coach.id,
        };
        emit('update', updateCoach);
    } else {
        const newCoach: storeCoachRequest = {
            ...values,
        };
        emit('create', newCoach);
    }
});

watch(
    () => props.coach,
    (newValue) => {
        if (newValue) {
            setValues({
                name: newValue.name,
                dni: newValue.dni,
                specialty: newValue.specialty || '',
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
