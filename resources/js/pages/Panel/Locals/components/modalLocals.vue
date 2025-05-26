<template>
    <Dialog :open="statusModal" @update:open="closeModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ isEditing ? 'Editar Local' : 'Nuevo Local' }}
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
import { LocalResource, storeLocalRequest, updateLocalRequest } from '../interfaces/Local';

interface Props {
    statusModal: boolean;
    local?: LocalResource | null;
}

const props = defineProps<Props>();
const emit = defineEmits<{
    (e: 'close-modal', open: boolean): void;
    (e: 'create', data: storeLocalRequest): void;
    (e: 'update', data: updateLocalRequest): void;
}>();

const isEditing = computed(() => !!props.local?.id);

const closeModal = () => {
    emit('close-modal', false);
};

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(3, 'El nombre es requerido'),
        address: z.string().nullable().default(null),
        status: z.boolean(),
    }),
);

const { handleSubmit, setValues } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: props.local?.name || '',
        address: props.local?.address || null,
        status: props.local?.status || false,
    },
});

const submitForm = handleSubmit((values: storeLocalRequest | updateLocalRequest) => {
    if (props.local) {
        const updatedLocal: updateLocalRequest = {
            ...values,
            id: props.local.id,
        };
        emit('update', updatedLocal);
    } else {
        const newLocal: storeLocalRequest = {
            ...values,
        };
        emit('create', newLocal);
    }
});

watch(
    () => props.local,
    (newValue) => {
        if (newValue) {
            setValues({
                name: newValue.name,
                address: newValue.address || null,
                status: newValue.status || false,
            });
        }
    },
);
</script>
<style scoped></style>
