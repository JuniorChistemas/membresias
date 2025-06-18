<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue'
</script>

<template>
  <div class="flex items-center gap-3">
    <!-- Logo: fondo y color adaptativo -->
    <div
      class="bg-white dark:bg-gray-900 flex items-center justify-center rounded-full size-12
      border border-gray-200 dark:border-gray-700
      shadow-[0_1.5px_12px_0_rgba(30,41,59,0.08)] dark:shadow-[0_1.5px_12px_0_rgba(30,41,59,0.17)] transition-all duration-300"
    >
      <AppLogoIcon class="size-7 text-gray-800 dark:text-white" />
    </div>
    <div class="flex flex-col justify-center">
      <span class="font-extrabold text-lg tracking-wide text-gray-900 dark:text-white drop-shadow-[0_2px_8px_rgba(0,0,0,0.12)]">
        GYM - T&J
      </span>
      <span class="text-sm text-gray-400 dark:text-gray-400 font-medium tracking-tight">
        Membresías & Control
      </span>
    </div>
  </div>
</template>
