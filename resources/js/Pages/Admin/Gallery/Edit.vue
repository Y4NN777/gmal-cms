<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  user: Object,
  album: Object,
  images: Array,
  errors: Object,
});

const form = ref({
  title: props.album.title,
  description: props.album.description || '',
  is_featured: props.album.is_featured || false,
});

const submitting = ref(false);
const uploading = ref(false);
const uploadProgress = ref(0);
const selectedImages = ref([]);
const showDeleteModal = ref(false);
const showDeleteImageModal = ref(false);
const selectedImageId = ref(null);

const submitForm = async () => {
  submitting.value = true;
  
  router.put(`/admin/gallery/${props.album.id}`, form.value, {
    onSuccess: () => {
      // Redirect handled by Inertia
    },
    onError: () => {
      submitting.value = false;
    },
    onFinish: () => {
      submitting.value = false;
    }
  });
};

const handleFileUpload = async (event) => {
  const files = Array.from(event.target.files);
  if (files.length === 0) return;

  uploading.value = true;
  uploadProgress.value = 0;

  const formData = new FormData();
  files.forEach(file => {
    formData.append('images[]', file);
  });
  formData.append('album_id', props.album.id);

  try {
    const token = localStorage.getItem('token');
    const response = await fetch('/admin/gallery/images/upload', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });

    if (response.ok) {
      router.reload({ only: ['images'] });
    }
  } catch (error) {
    console.error('Upload failed:', error);
  } finally {
    uploading.value = false;
    uploadProgress.value = 0;
    event.target.value = '';
  }
};

const deleteImage = (id) => {
  selectedImageId.value = id;
  showDeleteImageModal.value = true;
};

const confirmDeleteImage = () => {
  if (!selectedImageId.value) return;
  
  router.delete(`/admin/gallery/images/${selectedImageId.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteImageModal.value = false;
      selectedImageId.value = null;
    },
    onError: () => {
      showDeleteImageModal.value = false;
      selectedImageId.value = null;
    }
  });
};

const deleteAlbum = () => {
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  router.delete(`/admin/gallery/${props.album.id}`, {
    onSuccess: () => {
      router.visit('/admin/gallery');
    }
  });
};

const cancel = () => {
  router.visit('/admin/gallery');
};

const logout = () => {
  router.post('/logout');
};
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">GiveMeALift Admin</h1>
          </div>
          
          <div class="flex items-center gap-4">
            <span class="text-sm text-gray-700">{{ user?.name }}</span>
            <button @click="logout" class="text-sm text-gray-600 hover:text-gray-900">
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-sm min-h-screen">
        <nav class="px-4 py-6 space-y-1">
          <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Dashboard
          </a>
          <a href="/admin/events" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Events
          </a>
          <a href="/admin/testimonials" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Testimonials
          </a>
          <a href="/admin/gallery" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-orange-50 text-orange-700">
            Gallery
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="max-w-6xl mx-auto">
          <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <a href="/admin/gallery" class="hover:text-orange-600">Gallery</a>
              <span>/</span>
              <span class="text-gray-900">Edit Album</span>
            </div>
            <div class="flex justify-between items-start">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Album</h2>
                <p class="text-gray-600 mt-1">Update album details and manage images</p>
              </div>
              <button
                @click="deleteAlbum"
                class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium"
              >
                Delete Album
              </button>
            </div>
          </div>

          <!-- Album Info Form -->
          <form @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-6 space-y-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Album Information</h3>
            
            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Album Title <span class="text-red-500">*</span>
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.title }"
              />
              <p v-if="errors?.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.description }"
              ></textarea>
              <p v-if="errors?.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
            </div>

            <!-- Featured -->
            <div class="flex items-center">
              <input
                id="is_featured"
                v-model="form.is_featured"
                type="checkbox"
                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
              />
              <label for="is_featured" class="ml-2 text-sm text-gray-700">
                Feature this album on the homepage
              </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">
              <button
                type="button"
                @click="cancel"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
                :disabled="submitting"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium disabled:opacity-50"
                :disabled="submitting"
              >
                {{ submitting ? 'Saving...' : 'Save Changes' }}
              </button>
            </div>
          </form>

          <!-- Images Section -->
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h3 class="text-lg font-semibold text-gray-900">Album Images</h3>
                <p class="text-sm text-gray-600 mt-1">{{ images?.length || 0 }} images in this album</p>
              </div>
              <label class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium cursor-pointer">
                <input
                  type="file"
                  multiple
                  accept="image/*"
                  class="hidden"
                  @change="handleFileUpload"
                  :disabled="uploading"
                />
                {{ uploading ? 'Uploading...' : '+ Upload Images' }}
              </label>
            </div>

            <!-- Upload Progress -->
            <div v-if="uploading" class="mb-6">
              <div class="bg-gray-200 rounded-full h-2">
                <div class="bg-orange-600 h-2 rounded-full transition-all" :style="{ width: uploadProgress + '%' }"></div>
              </div>
              <p class="text-sm text-gray-600 mt-2">Uploading... {{ uploadProgress }}%</p>
            </div>

            <!-- Images Grid -->
            <div v-if="images && images.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div
                v-for="image in images"
                :key="image.id"
                class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden"
              >
                <img
                  :src="image.image_url"
                  :alt="image.caption || 'Album image'"
                  class="w-full h-full object-cover"
                />
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex items-center justify-center">
                  <button
                    @click="deleteImage(image.id)"
                    class="opacity-0 group-hover:opacity-100 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
              <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-600 mb-4">No images in this album yet</p>
              <label class="inline-block px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium cursor-pointer">
                <input
                  type="file"
                  multiple
                  accept="image/*"
                  class="hidden"
                  @change="handleFileUpload"
                />
                Upload First Images
              </label>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Delete Album Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Album?</h3>
        <p class="text-gray-600 text-center mb-6">
          Are you sure you want to delete this album? All {{ images?.length || 0 }} images will also be deleted. This action cannot be undone.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"
          >
            Delete Album
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Image Modal -->
    <div v-if="showDeleteImageModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteImageModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Image?</h3>
        <p class="text-gray-600 text-center mb-6">
          Are you sure you want to delete this image? This action cannot be undone.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteImageModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDeleteImage"
            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"
          >
            Delete Image
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
