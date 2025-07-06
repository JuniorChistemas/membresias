<template>
    <div class="flex flex-wrap items-center gap-3 p-2">
        <!-- Exportar Excel -->
        <a href="/panel/reports/export-excel-locals" download>
            <Button
                variant="ghost"
                size="sm"
                class="flex h-8 items-center gap-2 bg-green-600 px-3 text-white hover:bg-green-700"
                title="Exportar a Excel"
            >
                <FileSpreadsheet class="h-5 w-5 text-white" />
                <span>Exportar Excel</span>
            </Button>
        </a>

        <!-- Importar Excel -->
        <div>
            <input type="file" ref="fileRef" accept=".xlsx" class="hidden" @change="handleFileChange" />
            <Button
                @click="handleImportClick"
                variant="default"
                class="flex h-8 items-center gap-2 bg-blue-600 px-3 text-white hover:bg-blue-700"
                title="Importar Excel"
            >
                <FileInput class="h-5 w-5 text-white" />
                <span>Importar Excel</span>
            </Button>
        </div>

        <!-- Exportar PDF -->
        <a href="/panel/reports/export-pdf-locals" download>
            <Button variant="destructive" class="flex h-8 items-center gap-2 bg-red-600 px-3 text-white hover:bg-red-700" title="Exportar PDF">
                <FileType2 class="h-5 w-5 text-white" />
                <span>Exportar PDF</span>
            </Button>
        </a>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ref } from 'vue';
// NUEVOS ICONOS:
import axios from 'axios';
import { FileInput, FileSpreadsheet, FileType2 } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';

const fileRef = ref<HTMLInputElement | null>(null);
const toast = useToast();

const emit = defineEmits<{
    (e: 'import-success'): void;
}>();

const handleImportClick = () => {
    fileRef.value?.click();
};

const handleFileChange = async (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('archivo', file);

    try {
        await axios.post('/panel/reports/import-excel-locals', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        toast.success('¡Importación exitosa! Los locales fueron importados correctamente.');
        emit('import-success');
        target.value = '';
    } catch (error) {
        toast.error('❌ Error al importar. Revisa que el archivo sea válido (.xlsx) y vuelve a intentarlo.');
        console.error(error);
    }
};
</script>
