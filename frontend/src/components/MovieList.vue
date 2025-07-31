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
      // Move os breakpoints para o data ou computed
      breakpoints: {
        320:  { slidesPerView: 2, spaceBetween: 8 },
        640:  { slidesPerView: 3, spaceBetween: 12 },
        1024: { slidesPerView: 5, spaceBetween: 16 }
      }
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
    <SwiperSlide
        v-for="movie in movies"
        :key="movie.id"
        class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition"
    >
      <div class="relative">
        <img
            :src="movie.poster_path"
            :alt="movie.title"
            loading="lazy"
            class="w-full h-auto block"
        />

        <div
            class="absolute bottom-2 right-2 w-10 h-10 rounded-full border-2 flex items-center justify-center text-sm font-bold bg-black bg-opacity-70 text-white"
            :class="ratingClass(movie.vote_average)"
        >
          {{ toPercent(movie.vote_average) }}%
        </div>
      </div>

      <div class="p-3 bg-white space-y-2">
        <h3 class="text-gray-900 font-semibold text-sm truncate">
          {{ movie.title }}
        </h3>
        <p class="text-gray-600 text-xs">{{ formatDate(movie.release_date) }}</p>
        <p class="text-gray-700 text-xs line-clamp-3" v-if="movie.overview">
          {{ movie.overview }}
        </p>

        <!-- Botão de detalhes -->
        <button
            @click="$emit('show-details', movie.id)"
            class="w-full text-center mt-2 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-500"
        >
          Detalhes
        </button>
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
