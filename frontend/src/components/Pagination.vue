<script setup>
import { computed, defineProps } from 'vue'

const props = defineProps({
  currentPage: { type: Number, required: true },
  totalPages:  { type: Number, required: true }
})

const pagesToShow = computed(() => {
  const pages = []
  const start = Math.max(1, props.currentPage - 2)
  const end   = Math.min(props.totalPages, props.currentPage + 2)
  for (let i = start; i <= end; i++) pages.push(i)
  return pages
})
</script>

<template>
  <nav class="flex justify-center mt-6 space-x-2">
    <button
        :disabled="currentPage === 1"
        @click="$emit('change-page', currentPage-1)"
        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
    >Anterior</button>

    <template v-for="p in pagesToShow" :key="p">
      <button
          :class="['px-3 py-1 rounded', p === currentPage ? 'bg-indigo-600 text-white' : 'bg-gray-100']"
          @click="$emit('change-page', p)"
      >{{ p }}</button>
    </template>

    <button
        :disabled="currentPage === totalPages"
        @click="$emit('change-page', currentPage+1)"
        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
    >Próxima</button>
  </nav>
</template>