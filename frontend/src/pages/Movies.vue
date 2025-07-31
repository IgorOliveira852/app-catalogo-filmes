<script setup>
import { ref, onMounted, computed } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'

import axiosClient from '../axios.js'
import MovieFilters from '../components/MovieFilters.vue'
import MovieList from '../components/MovieList.vue'
import MovieDetailsModal from '../components/MovieDetailsModal.vue'
import { useFilterOptions } from '../composables/useFilterOptions.js'

// 1. Estado de filtros
const filters = ref({
  query: '',
  type: 'movie',
  page: 1,
  genre: '',
  sort_by: 'popularity.desc',
  release_date_gte: '',
  release_date_lte: '',
  vote_average_gte: ''
})

// 2. Estado de filmes
const movies  = ref([])
const loading = ref(false)
const error   = ref('')

// 3. Usando o composable para buscar “genres”
const { options, init } = useFilterOptions()
const genres = computed(() => options.value.genres || [])

const showModal = ref(false)
const selectedMovieId = ref(null)

function openDetails(id) {
  selectedMovieId.value = id
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  selectedMovieId.value = null
}

// 4. Função de busca
async function fetchMovies() {
  loading.value = true
  error.value   = ''

  try {
    const { data } = await axiosClient.get('/movies/search', {
      params: { ...filters.value, page: filters.value.page }
    })
    movies.value = data
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao carregar filmes.'
  } finally {
    loading.value = false
  }
}

// 5. No mount, inicializa “genres” e busca filmes
onMounted(async () => {
  await init(['genres'])
  fetchMovies()
})
</script>

<template>
  <section class="px-6 py-8 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Meus filmes</h2>

    <!-- filtro -->
    <MovieFilters
        v-model="filters"
        :genres="genres"
        @search="fetchMovies"
    />

    <!-- 1) Skeleton enquanto carrega -->
    <div v-if="loading" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
      <div
          v-for="n in 10"
          :key="n"
          class="animate-pulse space-y-2"
      >
        <div class="bg-gray-300 h-48 rounded-lg"></div>
        <div class="h-4 bg-gray-300 rounded w-3/4"></div>
        <div class="h-4 bg-gray-300 rounded w-1/2"></div>
      </div>
    </div>

    <!-- 2) Mensagem de erro -->
    <div v-else-if="error" class="text-red-600 text-center py-10">
      {{ error }}
    </div>

    <!-- 3) Grid de filmes quando houver dados -->
    <MovieList
        v-else
        :movies="movies"
        @show-details="openDetails"
    />

    <!-- Modal de detalhes -->
    <MovieDetailsModal
        v-if="showModal"
        :movieId="selectedMovieId"
        @close="closeModal"
    />
  </section>
</template>

<style scoped>
:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
  @apply bg-black/50 text-white w-12 h-12 rounded-full flex items-center justify-center transition-all;
}

:deep(.swiper-button-next:hover),
:deep(.swiper-button-prev:hover) {
  @apply bg-black/80 scale-110;
}
</style>