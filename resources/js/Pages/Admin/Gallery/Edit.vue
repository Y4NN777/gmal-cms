<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
          <a href="/admin/gallery" class="hover:text-orange-600 transition-colors">{{ t('galleryManagement.title') }}</a>
          <span>/</span>
          <span class="text-gray-900 font-medium">{{ t('galleryManagement.edit') }}</span>
        </div>
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-3xl font-bold text-gray-900">{{ album.title }}</h2>
            <p class="text-gray-600 mt-2">{{ t('galleryManagement.description') }}</p>
          </div>
          <button
            @click="showDeleteAlbumModal = true"
            class="px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
          >
            {{ t('galleryManagement.delete') }} {{ t('galleryManagement.albumTitle') }}
          </button>
        </div>
      </div>

      <!-- Album Info Form -->
      <form @submit.prevent="submitForm" class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ t('galleryManagement.albumTitle') }}</h3>
        
        <div class="space-y-6">
          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
              {{ t('galleryManagement.albumTitle') }} <span class="text-red-500">*</span>
            </label>
            <input
              id="title"
              v-model="form.title"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              :class="{ 'border-red-500': errors?.title }"
            />
            <p v-if="errors?.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
          </div>

          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
              {{ t('galleryManagement.albumDescription') }}
            </label>
            <textarea
              id="description"
              v-model="form.description"
              rows="4"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              :class="{ 'border-red-500': errors?.description }"
              :placeholder="t('common.description')"
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
            <label for="is_featured" class="ml-3 text-sm text-gray-700">
              {{ t('common.featured') }}
            </label>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 border-t">
            <button
              type="button"
              @click="router.visit('/admin/gallery')"
              class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
              :disabled="submitting"
            >
              {{ t('galleryManagement.cancel') }}
            </button>
            <button
              type="submit"
              class="px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-medium shadow-sm transition-all disabled:opacity-50"
              :disabled="submitting"
            >
              {{ submitting ? t('common.saving') : t('galleryManagement.save') }}
            </button>
          </div>
        </div>
      </form>

      <!-- Images Section -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ t('galleryManagement.images') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ images?.length || 0 }} {{ t('galleryManagement.images') }}</p>
          </div>
          <label class="px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-medium shadow-sm cursor-pointer transition-all">
            <span class="inline-flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ uploading ? 'Uploading...' : 'Upload Images' }}
            </span>
            <input
              type="file"
              multiple
              accept="image/*"
              class="hidden"
              @change="handleFileUpload"
              :disabled="uploading"
              ref="fileInput"
            />
          </label>
        </div>

        <!-- Drag & Drop Zone (when empty) -->
        <div
          v-if="(!images || images.length === 0) && !uploading"
          @drop.prevent="handleDrop"
          @dragover.prevent="dragOver = true"
          @dragleave="dragOver = false"
          :class="[
            'border-2 border-dashed rounded-xl p-12 text-center transition-all cursor-pointer',
            dragOver ? 'border-orange-500 bg-orange-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'
          ]"
          @click="$refs.fileInput.click()"
        >
          <svg class="mx-auto h-16 w-16 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <p class="mt-4 text-lg font-medium text-orange-600">Click to upload or drag and drop</p>
          <p class="mt-1 text-sm text-gray-500">Upload multiple images at once</p>
          <p class="text-xs text-gray-400 mt-2">PNG, JPG, GIF, WebP up to 10MB each</p>
        </div>

        <!-- Upload Progress -->
        <div v-if="uploading" class="mb-6">
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Uploading {{ uploadQueue }} image(s)...</span>
            <span class="text-sm font-semibold text-orange-600">{{ uploadProgress }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div 
              class="bg-gradient-to-r from-orange-500 to-orange-600 h-2.5 rounded-full transition-all duration-300"
              :style="{ width: uploadProgress + '%' }"
            ></div>
          </div>
        </div>

        <!-- Images Grid -->
        <div v-if="images && images.length > 0">
          <!-- Bulk Actions -->
          <div v-if="selectedImages.length > 0" class="mb-6 flex items-center justify-between p-4 bg-orange-50 border border-orange-200 rounded-xl">
            <span class="text-sm font-medium text-gray-700">
              {{ selectedImages.length }} image(s) selected
            </span>
            <button
              @click="bulkDelete"
              class="px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 text-sm font-medium transition-colors"
            >
              Delete Selected
            </button>
          </div>

          <!-- Select All -->
          <div class="flex items-center gap-2 mb-6 px-2">
            <input
              type="checkbox"
              :checked="selectedImages.length === images.length"
              @change="selectAll"
              class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
            />
            <span class="text-sm text-gray-600">Select All</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div
              v-for="image in images"
              :key="image.id"
              class="group relative aspect-square bg-gray-100 rounded-xl overflow-hidden border-2 transition-all"
              :class="selectedImages.includes(image.id) ? 'border-orange-500 ring-2 ring-orange-200' : 'border-transparent'"
            >
              <!-- Selection Checkbox -->
              <div class="absolute top-2 left-2 z-10">
                <input
                  type="checkbox"
                  :checked="selectedImages.includes(image.id)"
                  @click.stop="toggleSelection(image.id)"
                  class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 shadow-sm"
                />
              </div>

              <!-- Image -->
              <img
                :src="image.image_url"
                :alt="image.caption || 'Album image'"
                class="w-full h-full object-cover"
                loading="lazy"
              />

              <!-- Hover Overlay -->
              <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                <button
                  @click="viewImage(image)"
                  class="px-3 py-1.5 bg-white text-gray-900 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                >
                  View
                </button>
                <button
                  @click="deleteImage(image.id)"
                  class="px-3 py-1.5 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg text-sm font-medium hover:from-red-100 hover:to-red-50 transition-colors"
                >
                  Delete
                </button>
              </div>

              <!-- Caption (if exists) -->
              <div v-if="image.caption" class="absolute bottom-0 left-0 right-0 bg-black/70 text-white p-2 text-xs">
                {{ image.caption }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- View Image Modal -->
    <div v-if="viewingImage" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="viewingImage = null">
      <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-auto" @click.stop>
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Image Details</h3>
            <button @click="viewingImage = null" class="text-gray-400 hover:text-gray-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="mb-4 bg-gray-50 rounded-lg overflow-hidden">
            <img
              :src="viewingImage.image_url"
              :alt="viewingImage.caption"
              class="w-full max-h-[60vh] object-contain"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Caption (Optional)</label>
            <input
              v-model="editingCaption"
              type="text"
              placeholder="Add a caption for this image..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
            <div class="flex justify-end gap-3 mt-4">
              <button
                @click="viewingImage = null"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
              >
                Cancel
              </button>
              <button
                @click="updateCaption"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors"
              >
                Save Caption
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Album Modal -->
    <div v-if="showDeleteAlbumModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteAlbumModal = false">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Delete Album?</h3>
        </div>
        <p class="text-gray-600 mb-6">
          Are you sure you want to delete this album? All {{ images?.length || 0 }} image(s) will be removed from the album. This action cannot be undone.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteAlbumModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
          >
            Cancel
          </button>
          <button
            @click="confirmDeleteAlbum"
            class="flex-1 px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
          >
            Delete Album
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Image Modal -->
    <div v-if="showDeleteImageModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteImageModal = false">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Delete Image?</h3>
        </div>
        <p class="text-gray-600 mb-6">
          Are you sure you want to remove this image from the album? This action cannot be undone.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteImageModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
          >
            Cancel
          </button>
          <button
            @click="confirmDeleteImage"
            class="flex-1 px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
          >
            Delete Image
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  user: Object,
  album: Object,
  images: Array,
  errors: Object,
});

// Form
const form = ref({
  title: props.album.title,
  description: props.album.description || '',
  is_featured: props.album.is_featured || false,
});

const submitting = ref(false);

// Upload
const uploading = ref(false);
const uploadProgress = ref(0);
const uploadQueue = ref(0);
const dragOver = ref(false);

// Selection
const selectedImages = ref([]);
const viewingImage = ref(null);
const editingCaption = ref('');

// Modals
const showDeleteAlbumModal = ref(false);
const showDeleteImageModal = ref(false);
const selectedImageId = ref(null);

// Methods
const submitForm = () => {
  submitting.value = true;
  
  router.put(`/admin/gallery/${props.album.id}`, form.value, {
    preserveScroll: true,
    onFinish: () => {
      submitting.value = false;
    }
  });
};

const handleFileUpload = async (event) => {
  const files = Array.from(event.target.files);
  if (files.length === 0) return;
  
  await uploadFiles(files);
  event.target.value = '';
};

const handleDrop = (event) => {
  dragOver.value = false;
  const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
  if (files.length > 0) {
    uploadFiles(files);
  }
};

const uploadFiles = async (files) => {
  uploading.value = true;
  uploadProgress.value = 0;
  uploadQueue.value = files.length;

  const formData = new FormData();
  files.forEach(file => {
    formData.append('images[]', file);
  });
  formData.append('album_id', props.album.id);

  try {
    await router.post('/admin/gallery/images/upload', formData, {
      preserveScroll: true,
      onProgress: (progress) => {
        uploadProgress.value = Math.round(progress.percentage);
      },
      onSuccess: () => {
        uploading.value = false;
        uploadProgress.value = 0;
        uploadQueue.value = 0;
      },
      onError: () => {
        uploading.value = false;
        uploadProgress.value = 0;
        uploadQueue.value = 0;
      }
    });
  } catch (error) {
    console.error('Upload failed:', error);
    uploading.value = false;
  }
};

const toggleSelection = (id) => {
  const index = selectedImages.value.indexOf(id);
  if (index > -1) {
    selectedImages.value.splice(index, 1);
  } else {
    selectedImages.value.push(id);
  }
};

const selectAll = () => {
  if (selectedImages.value.length === props.images.length) {
    selectedImages.value = [];
  } else {
    selectedImages.value = props.images.map(img => img.id);
  }
};

const viewImage = (image) => {
  viewingImage.value = image;
  editingCaption.value = image.caption || '';
};

const updateCaption = () => {
  if (!viewingImage.value) return;
  
  router.put(`/admin/gallery/images/${viewingImage.value.id}`, {
    caption: editingCaption.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      viewingImage.value = null;
      editingCaption.value = '';
    }
  });
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
      selectedImages.value = selectedImages.value.filter(id => id !== selectedImageId.value);
    }
  });
};

const bulkDelete = () => {
  if (selectedImages.value.length === 0) return;
  
  if (confirm(`Delete ${selectedImages.value.length} image(s) from this album? This action cannot be undone.`)) {
    // Delete images one by one
    Promise.all(
      selectedImages.value.map(id => 
        router.delete(`/admin/gallery/images/${id}`, {
          preserveScroll: true,
        })
      )
    ).then(() => {
      selectedImages.value = [];
      router.reload({ only: ['images'] });
    });
  }
};

const confirmDeleteAlbum = () => {
  router.delete(`/admin/gallery/${props.album.id}`, {
    onSuccess: () => {
      router.visit('/admin/gallery');
    }
  });
};
</script>
