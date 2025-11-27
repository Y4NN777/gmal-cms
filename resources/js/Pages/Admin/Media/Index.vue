<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Media Library</h2>
        <p class="mt-2 text-gray-600">Manage your uploaded images and files</p>
      </div>

        <!-- Upload Section -->
        <div class="mb-6 bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Upload Files</h3>
            <button 
              @click="openUploadModal" 
              class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
            >
              <span class="inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Files
              </span>
            </button>
          </div>

          <!-- Drag & Drop Zone -->
          <div 
            v-if="showUploadZone"
            @drop.prevent="handleDrop"
            @dragover.prevent="dragOver = true"
            @dragleave="dragOver = false"
            :class="[
              'border-2 border-dashed rounded-lg p-12 text-center transition-colors',
              dragOver ? 'border-orange-500 bg-orange-50' : 'border-gray-300 bg-gray-50'
            ]"
          >
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4">
              <label class="cursor-pointer">
                <span class="mt-2 block text-sm font-medium text-orange-600 hover:text-orange-500">
                  Click to upload
                </span>
                <input 
                  type="file" 
                  @change="handleFileSelect" 
                  multiple 
                  accept="image/*,.pdf,.doc,.docx"
                  class="hidden"
                  ref="fileInput"
                >
              </label>
              <p class="mt-1 text-xs text-gray-500">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, WebP, SVG, PDF up to 10MB</p>
          </div>

          <!-- Upload Progress -->
          <div v-if="uploading" class="mt-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Uploading {{ uploadQueue.length }} file(s)...</span>
              <span class="text-sm text-gray-500">{{ uploadProgress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div 
                class="bg-orange-600 h-2 rounded-full transition-all duration-300"
                :style="{ width: uploadProgress + '%' }"
              ></div>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-white rounded-lg shadow p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
              <input 
                v-model="filters.search"
                @input="debouncedSearch"
                type="text" 
                placeholder="Search files..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              >
            </div>

            <!-- Type Filter -->
            <select 
              v-model="filters.type"
              @change="applyFilters"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="">All Types</option>
              <option value="images">Images Only</option>
              <option value="documents">Documents Only</option>
            </select>

            <!-- Sort -->
            <select 
              v-model="filters.sort_by"
              @change="applyFilters"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="created_at">Date Uploaded</option>
              <option value="filename">Filename</option>
              <option value="size">File Size</option>
            </select>
          </div>

          <!-- Bulk Actions -->
          <div v-if="selectedMedia.length > 0" class="mt-4 flex items-center justify-between p-3 bg-orange-50 rounded-lg">
            <span class="text-sm font-medium text-gray-700">
              {{ selectedMedia.length }} file(s) selected
            </span>
            <button 
              @click="bulkDelete"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium"
            >
              Delete Selected
            </button>
          </div>
        </div>

        <!-- Media Grid -->
        <div class="bg-white rounded-lg shadow p-6">
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-300 border-t-orange-600"></div>
            <p class="mt-4 text-gray-600">Loading media...</p>
          </div>

          <div v-else-if="media.data.length === 0" class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No media files</h3>
            <p class="mt-2 text-gray-500">Upload your first file to get started.</p>
          </div>

          <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div 
              v-for="item in media.data" 
              :key="item.id"
              class="group relative bg-gray-50 rounded-lg overflow-hidden border-2 border-transparent hover:border-orange-500 transition-all cursor-pointer"
              @click="selectMedia(item)"
            >
              <!-- Selection Checkbox -->
              <div class="absolute top-2 left-2 z-10">
                <input 
                  type="checkbox"
                  :checked="selectedMedia.includes(item.id)"
                  @click.stop="toggleSelection(item.id)"
                  class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                >
              </div>

              <!-- Media Preview -->
              <div class="aspect-square">
                <img 
                  v-if="item.is_image"
                  :src="item.thumbnail_url" 
                  :alt="item.alt_text || item.original_name"
                  class="w-full h-full object-cover"
                >
                <div v-else class="flex items-center justify-center h-full bg-gray-200">
                  <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>

              <!-- Media Info -->
              <div class="p-2 bg-white">
                <p class="text-xs font-medium text-gray-900 truncate" :title="item.original_name">
                  {{ item.original_name }}
                </p>
                <p class="text-xs text-gray-500">{{ item.size_human }}</p>
              </div>

              <!-- Action Overlay -->
              <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100">
                <button 
                  @click.stop="viewMedia(item)"
                  class="px-3 py-1 bg-white text-gray-900 rounded-lg text-sm font-medium mr-2"
                >
                  View
                </button>
                <button 
                  @click.stop="deleteMedia(item.id)"
                  class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm font-medium"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="media.last_page > 1" class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
            <div class="text-sm text-gray-700">
              Showing <span class="font-medium">{{ media.from }}</span> to <span class="font-medium">{{ media.to }}</span> of <span class="font-medium">{{ media.total }}</span> files
            </div>
            <div class="flex gap-2">
              <button 
                v-for="page in paginationPages" 
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-4 py-2 rounded-lg text-sm font-medium',
                  page === media.current_page 
                    ? 'bg-orange-600 text-white' 
                    : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                ]"
              >
                {{ page }}
              </button>
            </div>
          </div>
        </div>

        <!-- View Modal -->
        <div v-if="viewingMedia" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="viewingMedia = null">
          <div class="bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-auto" @click.stop>
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">{{ viewingMedia.original_name }}</h3>
                <button @click="viewingMedia = null" class="text-gray-400 hover:text-gray-600">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <div class="mb-4">
                <img 
                  v-if="viewingMedia.is_image"
                  :src="viewingMedia.url" 
                  :alt="viewingMedia.alt_text"
                  class="w-full rounded-lg"
                >
              </div>

              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="font-medium text-gray-700">File Size:</span>
                  <span class="text-gray-600 ml-2">{{ viewingMedia.size_human }}</span>
                </div>
                <div>
                  <span class="font-medium text-gray-700">Type:</span>
                  <span class="text-gray-600 ml-2">{{ viewingMedia.mime_type }}</span>
                </div>
                <div v-if="viewingMedia.dimensions" class="col-span-2">
                  <span class="font-medium text-gray-700">Dimensions:</span>
                  <span class="text-gray-600 ml-2">{{ viewingMedia.dimensions.width }} x {{ viewingMedia.dimensions.height }}px</span>
                </div>
                <div class="col-span-2">
                  <span class="font-medium text-gray-700">URL:</span>
                  <input 
                    type="text" 
                    :value="viewingMedia.url" 
                    readonly
                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm"
                    @click="$event.target.select()"
                  >
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showDeleteModal = false">
          <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex items-center gap-3 mb-4">
              <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900">Delete Media File</h3>
            </div>
            
            <p class="text-gray-600 mb-6">
              Are you sure you want to permanently delete this file? This action cannot be undone and may affect events or galleries using this image.
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
                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
              >
                Delete File
              </button>
            </div>
          </div>
        </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  media: Object,
  filters: Object,
  user: Object,
});

const loading = ref(false);
const showUploadZone = ref(false);
const uploading = ref(false);
const uploadProgress = ref(0);
const uploadQueue = ref([]);
const dragOver = ref(false);
const selectedMedia = ref([]);
const viewingMedia = ref(null);
const showDeleteModal = ref(false);
const selectedMediaId = ref(null);
const fileInput = ref(null);
const filters = reactive({
  search: props.filters?.search || '',
  type: props.filters?.type || '',
  sort_by: props.filters?.sort_by || 'created_at',
});

const paginationPages = computed(() => {
  const pages = [];
  for (let i = 1; i <= props.media.last_page; i++) {
    pages.push(i);
  }
  return pages;
});

let searchTimeout;
const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
};

const applyFilters = () => {
  router.get('/admin/media', filters, { preserveState: true });
};

const goToPage = (page) => {
  router.get('/admin/media', { ...filters, page }, { preserveState: true });
};

const openUploadModal = () => {
  showUploadZone.value = !showUploadZone.value;
};

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files);
  uploadFiles(files);
};

const handleDrop = (event) => {
  dragOver.value = false;
  const files = Array.from(event.dataTransfer.files);
  uploadFiles(files);
};

const uploadFiles = async (files) => {
  if (files.length === 0) return;

  uploading.value = true;
  uploadQueue.value = files;
  uploadProgress.value = 0;

  const token = localStorage.getItem('token');
  let completed = 0;

  for (const file of files) {
    const formData = new FormData();
    formData.append('file', file);

    try {
      const response = await fetch('/admin/media', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: formData,
      });

      if (response.ok) {
        completed++;
        uploadProgress.value = Math.round((completed / files.length) * 100);
      }
    } catch (error) {
      console.error('Upload failed:', error);
    }
  }

  uploading.value = false;
  uploadQueue.value = [];
  showUploadZone.value = false;
  
  // Reload page
  router.reload();
};

const toggleSelection = (id) => {
  const index = selectedMedia.value.indexOf(id);
  if (index > -1) {
    selectedMedia.value.splice(index, 1);
  } else {
    selectedMedia.value.push(id);
  }
};

const selectMedia = (item) => {
  viewingMedia.value = item;
};

const viewMedia = (item) => {
  viewingMedia.value = item;
};

const deleteMedia = (id) => {
  selectedMediaId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  if (!selectedMediaId.value) return;
  
  router.delete(`/admin/media/${selectedMediaId.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      selectedMedia.value = selectedMedia.value.filter(mediaId => mediaId !== selectedMediaId.value);
      showDeleteModal.value = false;
      selectedMediaId.value = null;
    },
    onError: () => {
      showDeleteModal.value = false;
      selectedMediaId.value = null;
    }
  });
};

const bulkDelete = async () => {
  if (!confirm(`Delete ${selectedMedia.value.length} file(s)?`)) return;

  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch('/admin/media/bulk-destroy', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({
        media_ids: selectedMedia.value,
      }),
    });

    if (response.ok) {
      selectedMedia.value = [];
      router.reload();
    }
  } catch (error) {
    console.error('Bulk delete failed:', error);
  }
};

const logout = async () => {
  const token = localStorage.getItem('token');
  
  await fetch('/api/v1/auth/logout', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  });

  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.visit('/admin/login');
};

onMounted(() => {
  // Check auth
  const token = localStorage.getItem('token');
  if (!token) {
    router.visit('/admin/login');
  }
});
</script>
