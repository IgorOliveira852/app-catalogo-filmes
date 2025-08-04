<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import axiosClient from '../axios.js'
import { useToast } from 'vue-toastification'

import MovieList from '../components/MovieList.vue'
import Pagination from '../components/Pagination.vue'

const router    = useRouter()
const toast     = useToast()

// estado
const searchTerm = ref('')
const favorites  = ref([])
const loading    = ref(false)
const errorMsg   = ref('')

const page        = ref(1)
const perPage     = 10
const totalPages  = ref(0)

// busca
async function fetchFavorites() {
  loading.value = true
  errorMsg.value = ''
  try {
    const { data } = await axiosClient.get('/movies/favorites', {
      params: { search: searchTerm.value, page: page.value }
    })
    favorites.value = data.data
    totalPages.value = data.last_page
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Erro ao carregar favoritos'
    toast.error(errorMsg.value)
  } finally {
    loading.value = false
  }
}

// re-busca quando mudar página ou termo
watch([searchTerm, page], () => {
  fetchFavorites()
})

onMounted(fetchFavorites)

// redireciona pro detalhes
function showDetails(id) {
  router.push({ name: 'Movies', query: { id } })
}
</script>

<template>
  <section class="px-6 py-8 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Meus Favoritos</h2>

    <!-- busca simples -->
    <div class="mb-6 flex items-center space-x-3">
      <input
          v-model="searchTerm"
          type="text"
          placeholder="Buscar nos favoritos..."
          class="flex-1 border rounded px-3 py-2"
      />
      <button
          @click="page = 1"
          class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500"
      >
        Buscar
      </button>
    </div>

    <!-- estados -->
    <div v-if="loading" class="text-center py-10">
      Carregando favoritos...
    </div>
    <div v-else-if="errorMsg" class="text-red-600 text-center py-10">
      {{ errorMsg }}
    </div>

    <!-- lista + paginação -->
    <div v-else>
      <MovieList
          :movies="favorites"
          @save-favorite="() => {}"
          @show-details="showDetails"
      />

      <Pagination
          :current-page="page"
          :total-pages="totalPages"
          @change-page="newPage => page = newPage"
      />
    </div>
  </section>
</template>