<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-dark to-primary-green py-20 text-white">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-display font-bold mb-4">Photo Albums</h1>
        <p class="text-xl max-w-2xl mx-auto">
          Browse our collection of memorable moments organized by event
        </p>
      </div>
    </section>

    <!-- Filter Bar -->
    <section class="bg-white border-b sticky top-0 z-10 shadow-sm">
      <div class="container mx-auto px-4 py-4">
        <div class="flex items-center gap-4">
          <Link href="/gallery" class="text-gray-600 hover:text-primary-dark">
            All Photos
          </Link>
          <Link href="/gallery/albums" class="text-primary-dark font-semibold border-b-2 border-primary-orange pb-1">
            Albums
          </Link>
        </div>
      </div>
    </section>

    <!-- Albums Grid -->
    <section class="py-12 bg-gray-50">
      <div class="container mx-auto px-4">
        <div v-if="albums.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <Card
            v-for="album in albums"
            :key="album.id"
            variant="elevated"
            hoverable
            class="group"
          >
            <template #image>
              <div class="relative overflow-hidden">
                <img
                  v-if="album.cover_image_url"
                  :src="album.cover_image_url"
                  :alt="album.title"
                  class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300"
                />
                <div v-else class="w-full h-64 bg-gray-200 flex items-center justify-center">
                  <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                </div>
                
                <!-- Featured Badge -->
                <div v-if="album.is_featured" class="absolute top-4 right-4">
                  <span class="px-3 py-1 bg-primary-orange text-white rounded-full text-xs font-semibold">
                    Featured
                  </span>
                </div>
              </div>
            </template>

            <div class="p-6">
              <h3 class="text-2xl font-bold text-primary-dark mb-2">
                {{ album.title }}
              </h3>
              
              <p v-if="album.description" class="text-gray-600 mb-4 line-clamp-2">
                {{ album.description }}
              </p>
              
              <div class="flex items-center justify-between text-sm text-gray-500">
                <span>{{ album.images_count }} {{ album.images_count === 1 ? 'photo' : 'photos' }}</span>
              </div>
            </div>

            <template #footer>
              <Button
                variant="ghost"
                :href="`/gallery/${album.slug}`"
                fullWidth
              >
                View Album
              </Button>
            </template>
          </Card>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20">
          <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
          </svg>
          <h3 class="mt-4 text-xl font-semibold text-gray-600">No albums yet</h3>
          <p class="mt-2 text-gray-500">Check back soon for new photo albums</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  albums: {
    type: Array,
    default: () => []
  }
});
</script>
