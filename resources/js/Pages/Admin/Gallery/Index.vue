<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  albums: Array,
  user: Object,
});

const searchQuery = ref('');
const showDeleteModal = ref(false);
const selectedAlbumId = ref(null);

const filteredAlbums = computed(() => {
  if (!searchQuery.value) return props.albums;
  
  return props.albums.filter(album => 
    album.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (album.description && album.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
  );
});

const deleteAlbum = (id) => {
  selectedAlbumId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (!selectedAlbumId.value) return;
  
  router.delete(`/admin/gallery/${selectedAlbumId.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedAlbumId.value = null;
    },
  });
};

const createAlbum = () => {
  router.visit('/admin/gallery/create');
};

const editAlbum = (id) => {
  router.visit(`/admin/gallery/${id}/edit`);
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
          <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-center">
            <div>
              <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ t('galleryManagement.title') }}</h2>
              <p class="text-sm sm:text-base text-gray-600 mt-1">{{ t('galleryManagement.description') }}</p>
            </div>
            <button
              @click="createAlbum"
              class="px-4 py-2 text-sm sm:text-base bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium whitespace-nowrap"
            >
              + {{ t('galleryManagement.createAlbum') }}
            </button>
          </div>

          <!-- Search Bar -->
          <div class="bg-white rounded-lg shadow p-4 mb-4 sm:mb-6">
            <div class="flex gap-4">
              <div class="flex-1">
                <input
                  v-model="searchQuery"
                  type="text"
                  :placeholder="t('common.search')"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                />
              </div>
            </div>
          </div>

          <!-- Albums Grid -->
          <div v-if="filteredAlbums.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div
              v-for="album in filteredAlbums"
              :key="album.id"
              class="bg-white rounded-lg shadow hover:shadow-md transition-shadow"
            >
              <!-- Album Cover -->
              <div class="relative h-48 bg-gray-100 rounded-t-lg overflow-hidden">
                <div class="absolute inset-0 flex items-center justify-center">
                  <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="absolute top-3 right-3 bg-orange-600 text-white px-2 py-1 rounded text-xs font-medium whitespace-nowrap">
                  {{ album.images_count || 0 }} {{ t('galleryManagement.images') }}
                </div>
              </div>

              <!-- Album Info -->
              <div class="p-4">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1">{{ album.title }}</h3>
                <p v-if="album.description" class="text-gray-600 text-xs sm:text-sm mb-3 line-clamp-2">
                  {{ album.description }}
                </p>
                <div class="flex items-center justify-between flex-wrap gap-2">
                  <span class="text-xs text-gray-500">
                    {{ new Date(album.created_at).toLocaleDateString() }}
                  </span>
                  <div class="flex gap-1.5 sm:gap-2">
                    <button
                      @click="editAlbum(album.id)"
                      class="px-2 sm:px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 font-medium whitespace-nowrap"
                    >
                      {{ t('galleryManagement.edit') }}
                    </button>
                    <button
                      @click="deleteAlbum(album.id)"
                      class="px-2 sm:px-3 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 font-medium whitespace-nowrap"
                    >
                      {{ t('galleryManagement.delete') }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white rounded-lg shadow p-8 sm:p-12 text-center">
            <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">{{ t('galleryManagement.noAlbumsYet') }}</h3>
            <p class="text-sm sm:text-base text-gray-600 mb-4">{{ t('galleryManagement.createFirstAlbum') }}</p>
            <button 
              @click="createAlbum"
              class="px-4 py-2 text-sm sm:text-base bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
            >
              + {{ t('galleryManagement.createAlbum') }}
            </button>
          </div>
        </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-4 sm:p-6" @click.stop>
        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-lg sm:text-xl font-bold text-gray-900 text-center mb-2">{{ t('galleryManagement.deleteAlbumTitle') }}</h3>
        <p class="text-sm sm:text-base text-gray-600 text-center mb-6">
          {{ t('galleryManagement.deleteAlbumConfirm') }}
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
          >
            {{ t('galleryManagement.cancel') }}
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium"
          >
            {{ t('galleryManagement.delete') }} {{ t('galleryManagement.albumTitle') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
