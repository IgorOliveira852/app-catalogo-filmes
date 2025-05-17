<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

const movies = [
  { id: 1, title: 'Inception', poster: 'https://image.tmdb.org/t/p/w500/8tNX8s3j1O0eqilOQkuroRLyOZA.jpg' },
  { id: 2, title: 'Interstellar', poster: 'https://image.tmdb.org/t/p/w500/rAiYTfKGqDCRIIqo664sY9XZIvQ.jpg' },
  { id: 3, title: 'The Batman', poster: 'https://image.tmdb.org/t/p/w500/74xTEgt7R36Fpooo50r9T25onhq.jpg' },
  { id: 4, title: 'Dune', poster: 'https://image.tmdb.org/t/p/w500/d5NXSklXo0qyIYkgV94XAgMIckC.jpg' },
  { id: 5, title: 'Avatar 2', poster: 'https://image.tmdb.org/t/p/w500/6oH378KUfCEitzJkm07r97L0RsZ.jpg' },
];
</script>

<template>
  <section class="px-6 py-8 bg-gray-100 min-h-screen relative">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Meus filmes</h2>

    <Swiper
        :slides-per-view="3"
        :space-between="20"
        :navigation="true"
        :modules="[Navigation]"
        class="relative w-full h-80 rounded-lg overflow-hidden"
    >
      <SwiperSlide v-for="movie in movies" :key="movie.id">
        <div class="rounded-lg overflow-hidden shadow-xl bg-white">
          <img :src="movie.poster" class="w-full h-60 object-cover" />
          <div class="px-4 py-2">
            <h3 class="text-center text-lg font-semibold text-gray-700">{{ movie.title }}</h3>
          </div>
        </div>
      </SwiperSlide>

    </Swiper>
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