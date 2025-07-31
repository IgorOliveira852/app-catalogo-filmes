<template>
  <Teleport to="body">
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.self="close"
    >
      <div
          class="bg-white rounded-lg overflow-auto max-h-[90vh] w-11/12 md:w-2/3 lg:w-1/2"
      >
        <!-- Cabeçalho -->
        <div class="flex justify-between items-center px-4 py-2 border-b">
          <h3 class="text-xl font-bold">{{ details?.title }}</h3>
          <button @click="close" class="text-2xl">&times;</button>
        </div>

        <!-- Conteúdo -->
        <div class="p-4 space-y-4">
          <div class="flex flex-wrap gap-2 mb-4">
            <span
                v-for="(genre, idx) in details.genres"
                :key="genre"
                :class="['text-xs font-medium px-2 py-1 rounded-full text-white', genreColor(idx)]"
            >
              {{ genre }}
            </span>
          </div>

          <img
              v-if="details?.backdrop_path"
              :src="details.backdrop_path"
              class="w-full h-auto rounded"
              loading="lazy"
          />

          <p><strong>Sinopse:</strong> {{ details?.overview }}</p>

          <p>
            <strong>Data de Lançamento:</strong>
            {{ formatDate(details?.release_date) }}
          </p>

          <p>
            <strong>Avaliação:</strong>
            <span
                class="inline-block w-10 h-10 rounded-full border-2 flex items-center justify-center font-bold"
                :class="ratingClass(details?.vote_average)"
            >
              {{ toPercent(details?.vote_average) }}%
            </span>
          </p>

          <!-- adicione o que mais quiser: duração, gêneros, etc. -->
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import axiosClient from '../axios.js'
import { format } from 'date-fns'

const props = defineProps({
  movieId: { type: [String, Number], required: true }
})
const emit = defineEmits(['close'])

const details = ref(null)
const loading = ref(false)
const error = ref('')

const tagColors = [
  'bg-red-500',
  'bg-green-500',
  'bg-blue-500',
  'bg-yellow-500',
  'bg-purple-500',
  'bg-pink-500'
]

const genreColor = computed(() => {
  return (index) => {
    return tagColors[index % tagColors.length]
  }
})

// busca detalhes sempre que movieId mudar
watch(
    () => props.movieId,
    async (id) => {
      loading.value = true
      error.value = ''
      details.value = null
      try {
        const { data } = await axiosClient.get(`/movies/details/${id}`)
        details.value = data
      } catch (e) {
        error.value = e.response?.data?.message || 'Não foi possível carregar detalhes.'
      } finally {
        loading.value = false
      }
    },
    { immediate: true }
)

function close() {
  emit('close')
}

const toPercent = (vote) => (vote != null ? Math.round(vote * 10) : '')
const formatDate = (d) => (d ? format(new Date(d), 'dd/MM/yyyy') : '')
const ratingClass = (vote) => {
  const pct = toPercent(vote)
  if (pct >= 70) return 'border-green-500 text-green-500'
  if (pct >= 40) return 'border-yellow-500 text-yellow-500'
  return 'border-red-500 text-red-500'
}
</script>