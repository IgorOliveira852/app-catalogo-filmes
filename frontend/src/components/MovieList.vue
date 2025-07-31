<script>
import { format } from 'date-fns'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Navigation, Pagination } from 'swiper/modules'

import 'swiper/css'
import 'swiper/css/navigation'
import 'swiper/css/pagination'

export default {
  name: 'MovieList',
  components: {
    Swiper,
    SwiperSlide
  },
  props: {
    movies: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      breakpoints: {
        320:  { slidesPerView: 2, spaceBetween: 8 },
        640:  { slidesPerView: 3, spaceBetween: 12 },
        1024: { slidesPerView: 5, spaceBetween: 16 }
      },
      openMenuFor: null
    }
  },
  methods: {
    Pagination,
    Navigation,
    toPercent(vote) {
      return Math.round(vote * 10)
    },
    formatDate(dateStr) {
      return format(new Date(dateStr), 'dd/MM/yyyy')
    },
    ratingClass(vote) {
      const pct = this.toPercent(vote)
      if (pct >= 70) return 'border-green-500 text-green-500'
      if (pct >= 40) return 'border-yellow-500 text-yellow-500'
      return 'border-red-500 text-red-500'
    }
  }
}
</script>

<template>
  <Swiper
      :modules="[Navigation]"
      navigation
      :breakpoints="breakpoints"
      class="my-6"
  >
    <SwiperSlide v-for="movie in movies" :key="movie.id">
      <div class="relative group rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
        <!-- Pôster + Badge -->
        <img
            :src="movie.poster_path"
            :alt="movie.title"
            loading="lazy"
            class="w-full h-auto"
        />
        <div
            class="absolute bottom-40 right-42 w-10 h-10 rounded-full border-2 flex items-center  text-sm font-bold bg-black bg-opacity-70 text-white"
            :class="ratingClass(movie.vote_average)"
        >
          {{ toPercent(movie.vote_average) }}%
        </div>

        <!-- Conteúdo -->
        <div class="p-3 bg-white">
          <div class="flex items-start justify-between mb-1">
            <h3 class="text-gray-900 font-semibold text-sm truncate max-w-[75%]">
              {{ movie.title }}
            </h3>
            <button
                @click="() => $emit('save-favorite', movie)"
                class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition"
                title="Salvar nos favoritos"
            >
              ★
            </button>
          </div>

          <p class="text-gray-600 text-xs mb-2">
            {{ formatDate(movie.release_date) }}
          </p>

          <p
              v-if="movie.overview"
              class="text-gray-700 text-xs line-clamp-3"
              :title="movie.overview"
          >
            {{ movie.overview }}
          </p>

          <button
              @click="$emit('show-details', movie.id)"
              class="w-full text-center mt-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-500"
          >
            Detalhes
          </button>
        </div>
      </div>
    </SwiperSlide>
  </Swiper>
</template>

<style scoped>
/* Se quiser, adicione altura fixa ao container para equalizar */
.swiper {
  /* ex: min-height: 300px; */
}
</style>
