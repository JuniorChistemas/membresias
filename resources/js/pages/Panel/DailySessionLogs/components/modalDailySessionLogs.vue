<template>
  <Dialog :open="statusModal" @update:open="closeModal">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>
          {{ isEditing ? 'Editar sesión diaria' : 'Registrar sesión diaria' }}
        </DialogTitle>
        <DialogDescription>Asegúrate de que la información sea correcta.</DialogDescription>
      </DialogHeader>

      <form @submit.prevent="submitForm" class="flex flex-col gap-4 py-3">
        <FormField v-slot="{ componentField }" name="name">
          <FormItem>
            <FormLabel>Nombre de la sesión</FormLabel>
            <FormControl>
              <Input id="name" type="text" v-bind="componentField" disabled/>
            </FormControl>
            <FormMessage />
          </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="price">
          <FormItem>
            <FormLabel>Precio</FormLabel>
            <FormControl>
              <Input id="price" type="number" step="0.01" v-bind="componentField" disabled/>
            </FormControl>
            <FormMessage />
          </FormItem>
        </FormField>

        <FormField v-slot="{ componentField }" name="payment_method_id">
            <FormItem>
                <FormLabel>Método de Pago</FormLabel>
                <FormControl>
                <select id="payment_method_id" class="input-class" v-bind="componentField">
                    <option disabled value="">Seleccione un método</option>
                    <option v-for="method in paymentMethods" :key="method.id" :value="method.id">
                    {{ method.name }}
                    </option>
                </select>
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>

        <DialogFooter>
          <Button type="submit" variant="default">Guardar</Button>
          <Button type="button" variant="secondary" @click="closeModal">Cancelar</Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import {
  DailySessionLogResource,
  storeDailySessionLogRequest,
  updateDailySessionLogRequest
} from '../interfaces/DailySessionLog';
import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod';
import { z } from 'zod';
import { computed, watch } from 'vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import { FormField } from '@/components/ui/form';
import FormItem from '@/components/ui/form/FormItem.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormControl from '@/components/ui/form/FormControl.vue';
import FormMessage from '@/components/ui/form/FormMessage.vue';
import Input from '@/components/ui/input/Input.vue';
import Button from '@/components/ui/button/Button.vue';
import { PaymentMethodResource } from '../../PaymentMethods/interfaces/PaymentMethod';

// Props
interface Props {
  statusModal: boolean;
  dailySessionLog?: DailySessionLogResource | null;
  paymentMethods: PaymentMethodResource[];
}
const props = defineProps<Props>();

const emit = defineEmits<{
  (e: 'close-modal', open: boolean): void;
  (e: 'create', data: storeDailySessionLogRequest): void;
  (e: 'update', data: updateDailySessionLogRequest): void;
}>();

const isEditing = computed(() => !!props.dailySessionLog?.id);

const closeModal = () => {
  emit('close-modal', false);
};

// Validación de formulario
const formSchema = z.object({
  name: z.literal('Sesión'),
  price: z.literal(5.0),
  payment_method_id: z.number({ required_error: 'Seleccione un método de pago válido' }),
});

type FormValues = z.infer<typeof formSchema>;

const { handleSubmit, setValues } = useForm<FormValues>({
  validationSchema: toTypedSchema(formSchema),
  initialValues: {
    name: 'Sesión',
    price: 5.0,
    payment_method_id: props.dailySessionLog?.payment_method?.id ?? undefined,
  },
});

// Enviar formulario
const submitForm = handleSubmit((values) => {
  const payload = {
    name: values.name,
    price: values.price,
    payment_method_id: values.payment_method_id,
  };

  if (isEditing.value && props.dailySessionLog) {
    emit('update', {
      ...payload,
      id: props.dailySessionLog.id,
      registered_at: props.dailySessionLog.registered_at, // 🔥 ¡aquí estaba el error!
    });
  } else {
    emit('create', {
      ...payload,
      registered_at: new Date().toISOString(), // si necesitas enviarlo también al crear
    });
  }
});

// Rellenar datos si se edita
watch(
  () => props.dailySessionLog,
  (newValue) => {
    if (newValue) {
      setValues({
        name: 'Sesión',
        price: 5.0,
        payment_method_id: newValue.payment_method?.id || 0,
      });
    }
  }
);

watch(
  () => props.statusModal,
  (newValue) => {
    if (newValue && !props.dailySessionLog && props.paymentMethods.length > 0) {
      setValues({
        name: 'Sesión',
        price: 5.0,
        payment_method_id: props.paymentMethods[0].id, // Primer método de pago por defecto
      });
    }
  }
);
</script>


<style scoped>
.input-class {
  padding: 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  width: 100%;
}
</style>
