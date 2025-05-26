<template>
    <div class="flex w-full items-center">
        <Button>Nuevo local</Button>
        <div class="relative ml-auto w-full max-w-sm">
            <Input id="search" type="text" v-model="searchText" placeholder="Buscar..." class="w-full pl-10" />
            <span class="absolute inset-y-0 start-0 flex items-center px-3">
                <Search class="text-muted-foreground size-5" />
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import { debounce } from 'lodash-es';
import { Search } from 'lucide-vue-next';
import { onUnmounted, ref, watch } from 'vue';

const emit = defineEmits<{
    (e: 'search', value: string): void;
}>();

const searchText = ref('');

const emitSearch = debounce((value: string) => {
    emit('search', value);
}, 400);
// Limpiar el debounce al desmontar
onUnmounted(() => {
    emitSearch.cancel();
});

// Watch efectivo
watch(searchText, (newValue) => {
    emitSearch(newValue);
});
</script>

<style scoped></style>
