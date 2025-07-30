<template>
  <div class="bg-white p-4 rounded-lg shadow mb-6">
    <form @submit.prevent="onSearch" class="space-y-4">

      <!-- Linha 1: Título e Tipo -->
      <div class="flex flex-col md:flex-row md:space-x-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
          <input
              v-model="local.query"
              type="text"
              placeholder="Busque por título..."
              class="w-full border rounded px-3 py-2"
          />
        </div>
        <div class="mt-2 md:mt-0">
          <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
          <select
              v-model="local.type"
              class="w-full border rounded px-3 py-2"
          >
            <option value="movie">Filmes</option>
            <option value="tv">Séries</option>
          </select>
        </div>
      </div>

      <!-- Linha 2: Gênero e Ordenação -->
      <div class="flex flex-col md:flex-row md:space-x-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Gênero</label>
          <select
              v-model="local.genre"
              class="w-full border rounded px-3 py-2"
          >
            <option value="">Todos os gêneros</option>
            <option v-for="g in genres" :key="g.value" :value="g.value">
              {{ g.label }}
            </option>
          </select>
        </div>
        <div class="mt-2 md:mt-0">
          <label class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
          <select
              v-model="local.sort_by"
              class="w-full border rounded px-3 py-2"
          >
            <option value="popularity.desc">Mais populares</option>
            <option value="release_date.desc">Mais recentes</option>
            <option value="vote_average.desc">Melhor avaliação</option>
          </select>
        </div>
      </div>

      <!-- Linha 3: Datas e Nota Mínima -->
      <div class="flex flex-col md:flex-row md:space-x-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
          <input
              v-model="local.release_date_gte"
              type="date"
              class="w-full border rounded px-2 py-1"
          />
        </div>
        <div class="flex-1 mt-2 md:mt-0">
          <label class="block text-sm font-medium text-gray-700 mb-1">Data de Fim</label>
          <input
              v-model="local.release_date_lte"
              type="date"
              class="w-full border rounded px-2 py-1"
          />
        </div>
        <div class="mt-2 md:mt-0 w-40">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nota Mínima</label>
          <input
              v-model.number="local.vote_average_gte"
              type="number"
              min="0" max="10" step="0.1"
              placeholder="0 - 10"
              class="w-full border rounded px-3 py-2"
          />
        </div>
      </div>

      <!-- Botões -->
      <div class="flex justify-end space-x-2">
        <button
            @click.prevent="resetFilters"
            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
        >
          Limpar
        </button>
        <button
            type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500"
        >
          Aplicar
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'

// 1. Captura as props e o emit
const props = defineProps({
  modelValue: { type: Object, required: true },
  genres:     { type: Array,  default: () => [] }
})
const emit = defineEmits(['update:modelValue', 'search'])

// 2. Clona o modelValue num reactive local
const local = reactive({ ...props.modelValue })

// 3. Sempre que local mudar, atualiza o v-model lá no pai
watch(local, (nv) => {
  emit('update:modelValue', { ...nv })
}, { deep: true })

// 4. Quando apertar “Aplicar”
function onSearch() {
  emit('search')
}

// 5. Reseta para os defaults
function resetFilters() {
  Object.assign(local, {
    query: '',
    type: 'movie',
    page: 1,
    genre: '',
    sort_by: 'popularity.desc',
    release_date_gte: '',
    release_date_lte: '',
    vote_average_gte: ''
  })
}
</script>