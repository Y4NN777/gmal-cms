<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
    onError: () => {
      showDeleteModal.value = false;
      selectedAlbumId.value = null;
    }
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
          <div class="mb-8 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Gallery Management</h2>
              <p class="text-gray-600 mt-1">Manage photo albums and images</p>
            </div>
            <button
              @click="createAlbum"
              class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
            >
              + Create Album
            </button>
          </div>

          <!-- Search Bar -->
          <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
              <div class="flex-1">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search albums..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                />
              </div>
            </div>
          </div>

          <!-- Albums Grid -->
          <div v-if="filteredAlbums.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                <div class="absolute top-3 right-3 bg-orange-600 text-white px-2 py-1 rounded text-xs font-medium">
                  {{ album.images_count || 0 }} photos
                </div>
              </div>

              <!-- Album Info -->
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ album.title }}</h3>
                <p v-if="album.description" class="text-gray-600 text-sm mb-3 line-clamp-2">
                  {{ album.description }}
                </p>
                <div class="flex items-center justify-between">
                  <span class="text-xs text-gray-500">
                    {{ new Date(album.created_at).toLocaleDateString() }}
                  </span>
                  <div class="flex gap-2">
                    <button
                      @click="editAlbum(album.id)"
                      class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 font-medium"
                    >
                      Edit
                    </button>
                    <button
                      @click="deleteAlbum(album.id)"
                      class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 font-medium"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No albums yet</h3>
            <p class="text-gray-600 mb-4">Create your first photo album to get started</p>
            <button 
              @click="createAlbum"
              class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
            >
              + Create Album
            </button>
          </div>
        </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Album?</h3>
        <p class="text-gray-600 text-center mb-6">
          Are you sure you want to delete this album? All images in this album will also be deleted. This action cannot be undone.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium"
          >
            Delete Album
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
