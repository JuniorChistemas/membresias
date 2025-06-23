<template>
    <div class="flex flex-wrap items-center gap-3 p-2">
        <!-- Exportar Excel -->
        <a href="/panel/reports/export-excel-customers" download>
            <Button
                variant="ghost"
                size="sm"
                class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 h-8"
                title="Exportar a Excel"
            >
                <FileSpreadsheet class="w-5 h-5 text-white" />
                <span>Exportar Excel</span>
            </Button>
        </a>

        <!-- Importar Excel -->
        <div>
            <input type="file" ref="fileRef" accept=".xlsx" class="hidden" @change="handleFileChange" />
            <Button
                @click="handleImportClick"
                variant="default"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 h-8"
                title="Importar Excel"
            >
                <FileInput class="w-5 h-5 text-white" />
                <span>Importar Excel</span>
            </Button>
        </div>

        <!-- Exportar PDF -->
        <a href="/panel/reports/export-pdf-customers" download>
            <Button
                variant="destructive"
                class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-3 h-8"
                title="Exportar PDF"
            >
                <FileType2 class="w-5 h-5 text-white" />
                <span>Exportar PDF</span>
            </Button>
        </a>
    </div>
</template>


<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
// NUEVOS ICONOS:
import { FileSpreadsheet, FileInput, FileType2 } from 'lucide-vue-next'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const fileRef = ref<HTMLInputElement | null>(null)
const toast = useToast()

const emit = defineEmits<{
    (e: 'import-success'): void
}>()

const handleImportClick = () => {
    fileRef.value?.click()
}

const handleFileChange = async (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]
    if (!file) return
    const formData = new FormData()
    formData.append('archivo', file)
    
    try {
        await axios.post('/panel/reports/import-excel-customers', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        toast.success('¡Importación exitosa! Los clientes fueron importados correctamente.')
        emit('import-success')
        target.value = ''
    } catch (error) {
        toast.error('❌ Error al importar. Revisa que el archivo sea válido (.xlsx) y vuelve a intentarlo.')
        console.error(error)
    }
}
</script>
