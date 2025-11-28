<template>
  <AppLayout>
    <!-- Album Header -->
    <section class="bg-gradient-to-r from-primary-dark to-primary-green py-16 text-white">
      <div class="container mx-auto px-4">
        <nav class="text-sm mb-6">
          <Link href="/gallery" class="hover:underline">Gallery</Link>
          <span class="mx-2">/</span>
          <Link href="/gallery/albums" class="hover:underline">Albums</Link>
          <span class="mx-2">/</span>
          <span>{{ album.title }}</span>
        </nav>
        
        <h1 class="text-5xl font-display font-bold mb-4">{{ album.title }}</h1>
        
        <p v-if="album.description" class="text-xl max-w-3xl">
          {{ album.description }}
        </p>
        
        <p class="mt-4 text-white/80">
          {{ images.length }} {{ images.length === 1 ? 'photo' : 'photos' }}
        </p>
      </div>
    </section>

    <!-- Images Grid -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <div v-if="images.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div
            v-for="(image, index) in images"
            :key="image.id"
            @click="openLightbox(index)"
            class="relative group cursor-pointer overflow-hidden rounded-lg bg-white shadow-sm hover:shadow-xl transition-all duration-300 transform hover:scale-105"
          >
            <img
              :src="image.image_url"
              :alt="image.alt_text || image.caption"
              class="w-full h-64 object-cover"
              loading="lazy"
            />
            
            <!-- Caption Overlay -->
            <div
              v-if="image.caption"
              class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4"
            >
              <p class="text-white text-sm font-medium">
                {{ image.caption }}
              </p>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <h3 class="mt-4 text-xl font-semibold text-gray-600">No images in this album</h3>
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center">
          <Button variant="outline" href="/gallery/albums">
            ← Back to Albums
          </Button>
        </div>
      </div>
    </section>

    <!-- Lightbox -->
    <Lightbox
      v-if="showLightbox"
      :images="images"
      :initial-index="currentImageIndex"
      @close="closeLightbox"
    />
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Lightbox from '@/Components/Lightbox.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  album: {
    type: Object,
    required: true
  },
  images: {
    type: Array,
    default: () => []
  }
});

const showLightbox = ref(false);
const currentImageIndex = ref(0);

const openLightbox = (index) => {
  currentImageIndex.value = index;
  showLightbox.value = true;
};

const closeLightbox = () => {
  showLightbox.value = false;
};
</script>
