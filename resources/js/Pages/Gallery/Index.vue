<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-dark to-primary-green py-20 text-white">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-display font-bold mb-4">Gallery</h1>
        <p class="text-xl max-w-2xl mx-auto">
          Explore moments of impact, compassion, and community action
        </p>
      </div>
    </section>

    <!-- Filter Bar -->
    <section class="bg-white border-b sticky top-0 z-10 shadow-sm">
      <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <Link href="/gallery" class="text-primary-dark font-semibold border-b-2 border-primary-orange pb-1">
              All Photos
            </Link>
            <Link href="/gallery/albums" class="text-gray-600 hover:text-primary-dark">
              Albums
            </Link>
          </div>

          <div class="flex items-center gap-4">
            <select
              v-model="selectedAlbum"
              @change="filterByAlbum"
              class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-orange"
            >
              <option value="">All Albums</option>
              <option v-for="album in albums" :key="album.id" :value="album.slug">
                {{ album.title }} ({{ album.images_count }})
              </option>
            </select>

            <span class="text-gray-600">{{ images.total }} photos</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Images Grid (Masonry Style) -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <div v-if="images.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div
            v-for="(image, index) in images.data"
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
            
            <!-- Overlay on hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
              <p v-if="image.caption" class="text-white text-sm font-medium mb-1">
                {{ image.caption }}
              </p>
              <p v-if="image.album" class="text-white/80 text-xs">
                {{ image.album.title }}
              </p>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <h3 class="mt-4 text-xl font-semibold text-gray-600">No images found</h3>
          <p class="mt-2 text-gray-500">Try selecting a different album</p>
        </div>

        <!-- Pagination -->
        <div v-if="images.data.length > 0 && (images.prev_page_url || images.next_page_url)" class="flex justify-center gap-2 mt-12">
          <Button
            v-if="images.prev_page_url"
            variant="outline"
            @click="loadPage(images.current_page - 1)"
          >
            Previous
          </Button>
          
          <span class="px-4 py-2 text-gray-600">
            Page {{ images.current_page }} of {{ images.last_page }}
          </span>
          
          <Button
            v-if="images.next_page_url"
            variant="outline"
            @click="loadPage(images.current_page + 1)"
          >
            Next
          </Button>
        </div>
      </div>
    </section>

    <!-- Lightbox Component -->
    <Lightbox
      v-if="showLightbox"
      :images="images.data"
      :initial-index="currentImageIndex"
      @close="closeLightbox"
    />
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Lightbox from '@/Components/Lightbox.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  images: {
    type: Object,
    required: true
  },
  albums: {
    type: Array,
    default: () => []
  },
  currentAlbum: {
    type: String,
    default: ''
  }
});

const selectedAlbum = ref(props.currentAlbum || '');
const showLightbox = ref(false);
const currentImageIndex = ref(0);

const filterByAlbum = () => {
  router.get('/gallery', { album: selectedAlbum.value }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const loadPage = (page) => {
  router.get('/gallery', { 
    album: selectedAlbum.value,
    page: page 
  }, {
    preserveState: true,
    preserveScroll: false,
  });
};

const openLightbox = (index) => {
  currentImageIndex.value = index;
  showLightbox.value = true;
};

const closeLightbox = () => {
  showLightbox.value = false;
};
</script>
