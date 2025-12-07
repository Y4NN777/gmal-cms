<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden bg-[#252A34]">
      <div class="absolute inset-0">
        <img 
          :src="`${$page.props.assetUrl || ''}/images/home/hero-background.png`" 
          alt="Gallery Hero" 
          class="w-full h-full object-cover opacity-40"
          @error="$event.target.style.display='none'"
        />
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-[#252A34]/90 to-[#252A34]/40"></div>
      
      <div class="relative z-10 container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
        <div class="flex items-center gap-3 mb-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
          <div class="h-px w-12 bg-[#EE9446]"></div>
          <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">
            {{ $t('gallery.hero.label') }}
          </span>
          <div class="h-px w-12 bg-[#EE9446]"></div>
        </div>
        <h1 
          class="text-white font-display font-bold text-5xl md:text-7xl leading-tight mb-6 opacity-0 animate-fade-in-up"
          style="animation-delay: 0.4s; animation-fill-mode: forwards;"
          v-html="$t('gallery.hero.title')"
        >
        </h1>
        <p 
          class="text-gray-300 font-sans text-lg md:text-xl leading-relaxed max-w-2xl opacity-0 animate-fade-in-up"
          style="animation-delay: 0.6s; animation-fill-mode: forwards;"
        >
          {{ $t('gallery.hero.description') }}
        </p>
      </div>
    </section>

    <!-- Filter Bar -->
    <section class="bg-white border-b border-gray-100 sticky top-0 z-30 shadow-sm backdrop-blur-md bg-white/90">
      <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <div class="flex items-center gap-8">
            <Link 
              href="/gallery" 
              class="font-display font-bold text-lg transition-colors relative"
              :class="!selectedAlbum ? 'text-[#EE9446]' : 'text-gray-500 hover:text-[#252A34]'"
            >
              {{ $t('gallery.allPhotos') }}
              <span v-if="!selectedAlbum" class="absolute -bottom-6 left-0 w-full h-1 bg-[#EE9446] rounded-t-full"></span>
            </Link>
            <Link 
              href="/gallery/albums" 
              class="font-display font-bold text-lg text-gray-500 hover:text-[#252A34] transition-colors"
            >
              {{ $t('gallery.albums') }}
            </Link>
          </div>

          <div class="flex items-center gap-4">
            <div class="relative">
              <select
                v-model="selectedAlbum"
                @change="filterByAlbum"
                class="appearance-none pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-[#252A34] font-sans font-medium focus:outline-none focus:ring-2 focus:ring-[#EE9446] focus:border-transparent cursor-pointer"
              >
                <option value="">{{ $t('gallery.filterByAlbum') }}</option>
                <option v-for="album in albums" :key="album.id" :value="album.slug">
                  {{ album.title }} ({{ album.images_count }})
                </option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </div>
            </div>

            <span class="text-gray-400 font-sans text-sm">{{ $t('gallery.photos', { count: images.total }) }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Images Grid (Masonry Style) -->
    <section class="py-16 bg-[#F8F9FA]">
      <div class="container mx-auto px-4">
        <div v-if="images.data.length > 0" class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
          <div
            v-for="(image, index) in images.data"
            :key="image.id"
            @click="openLightbox(index)"
            class="break-inside-avoid relative group cursor-pointer rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1"
          >
            <img
              :src="image.image_url"
              :alt="image.alt_text || image.caption"
              class="w-full h-auto object-cover transform group-hover:scale-110 transition-transform duration-700"
              loading="lazy"
            />
            
            <!-- Overlay on hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
              <p v-if="image.caption" class="text-white font-display font-bold text-lg mb-1 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                {{ image.caption }}
              </p>
              <p v-if="image.album" class="text-[#EE9446] font-sans text-xs font-bold uppercase tracking-wider transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                {{ image.album.title }}
              </p>
            </div>
            
            <!-- Zoom Icon -->
            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform scale-50 group-hover:scale-100">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-24">
          <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-[#252A34] mb-2">{{ $t('gallery.noImagesFound') }}</h3>
          <p class="text-gray-500">{{ $t('gallery.noImagesDescription') }}</p>
        </div>

        <!-- Pagination -->
        <div v-if="images.data.length > 0 && (images.prev_page_url || images.next_page_url)" class="flex justify-center gap-4 mt-16">
          <button
            v-if="images.prev_page_url"
            @click="loadPage(images.current_page - 1)"
            class="px-6 py-3 bg-white border border-gray-200 rounded-full text-[#252A34] font-medium hover:bg-gray-50 hover:border-[#EE9446] transition-all duration-300"
          >
            {{ $t('gallery.previous') }}
          </button>
          
          <span class="px-6 py-3 text-gray-500 font-sans">
            {{ $t('gallery.pageOf', { current: images.current_page, total: images.last_page }) }}
          </span>
          
          <button
            v-if="images.next_page_url"
            @click="loadPage(images.current_page + 1)"
            class="px-6 py-3 bg-white border border-gray-200 rounded-full text-[#252A34] font-medium hover:bg-gray-50 hover:border-[#EE9446] transition-all duration-300"
          >
            {{ $t('gallery.next') }}
          </button>
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
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Lightbox from '@/Components/Lightbox.vue';

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
