<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold text-gray-900">{{ $t('mediaLibrary.title') }}</h2>
            <p class="mt-2 text-gray-600">{{ $t('mediaLibrary.description') }}</p>
          </div>
          <button 
            @click="showUploadZone = !showUploadZone" 
            class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl hover:from-orange-600 hover:to-orange-700 font-medium shadow-sm transition-all"
          >
            <span class="inline-flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ $t('mediaLibrary.uploadFiles') }}
            </span>
          </button>
        </div>
      </div>

      <!-- Upload Zone -->
      <div v-if="showUploadZone" class="mb-6 bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $t('mediaLibrary.uploadHere') }}</h3>
        
        <!-- Drag & Drop Zone -->
        <div 
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
          <div class="mt-4">
            <span class="text-lg font-medium text-orange-600">{{ $t('mediaLibrary.dragAndDrop') }}</span>
            <input 
              type="file" 
              @change="handleFileSelect" 
              multiple 
              accept="image/*,video/*"
              class="hidden"
              ref="fileInput"
            >
          </div>
          <p class="text-sm text-gray-500 mt-2">Images: PNG, JPG, GIF, WebP, SVG</p>
          <p class="text-sm text-gray-500">Videos: MP4, WebM, MOV</p>
          <p class="text-xs text-gray-400 mt-1">Maximum file size: 10MB</p>
        </div>

        <!-- Upload Progress -->
        <div v-if="uploading" class="mt-6">
          <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">{{ $t('mediaLibrary.uploading') }} {{ uploadQueue.length }} {{ $t('mediaLibrary.files') }}...</span>
            <span class="text-sm font-semibold text-orange-600">{{ uploadProgress }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div 
              class="bg-gradient-to-r from-orange-500 to-orange-600 h-2.5 rounded-full transition-all duration-300"
              :style="{ width: uploadProgress + '%' }"
            ></div>
          </div>
        </div>
      </div>

      <!-- Filters & Actions -->
      <div class="mb-6 bg-white rounded-xl shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('mediaLibrary.search') }}</label>
            <input 
              v-model="searchQuery"
              @input="debouncedSearch"
              type="text" 
              :placeholder="$t('mediaLibrary.searchPlaceholder')" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
          </div>

          <!-- Type Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('mediaLibrary.type') }}</label>
            <select 
              v-model="typeFilter"
              @change="applyFilters"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="">{{ $t('mediaLibrary.allMedia') }}</option>
              <option value="images">{{ $t('mediaLibrary.imagesOnly') }}</option>
              <option value="videos">{{ $t('mediaLibrary.videosOnly') }}</option>
            </select>
          </div>

          <!-- Sort -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('mediaLibrary.sort') }}</label>
            <select 
              v-model="sortBy"
              @change="applyFilters"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="created_at">{{ $t('mediaLibrary.newest') }}</option>
              <option value="filename">{{ $t('mediaLibrary.fileName') }}</option>
              <option value="size">{{ $t('mediaLibrary.size') }}</option>
            </select>
          </div>
        </div>

        <!-- Bulk Actions -->
        <div v-if="selectedMedia.length > 0" class="mt-6 flex items-center justify-between p-4 bg-orange-50 border border-orange-200 rounded-xl">
          <span class="text-sm font-medium text-gray-700">
            {{ selectedMedia.length }} {{ $t('mediaLibrary.selected') }}
          </span>
          <button 
            @click="bulkDelete"
            class="px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 text-sm font-medium transition-colors"
          >
            {{ $t('mediaLibrary.deleteSelected') }}
          </button>
        </div>
      </div>

      <!-- Media Grid -->
      <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Empty State -->
        <div v-if="media.data.length === 0" class="text-center py-16">
          <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $t('mediaLibrary.noFilesFound') }}</h3>
          <p class="mt-2 text-gray-500">{{ $t('mediaLibrary.noMatchingFiles') }}</p>
          <button 
            @click="showUploadZone = true"
            class="mt-6 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
          >
            {{ $t('mediaLibrary.uploadFiles') }}
          </button>
        </div>

        <!-- Media Grid -->
        <div v-else>
          <!-- Select All -->
          <div class="flex items-center gap-2 mb-6 px-2">
            <input
              type="checkbox"
              :checked="selectedMedia.length === media.data.length"
              @change="selectAll"
              class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
            />
            <span class="text-sm text-gray-600">{{ $t('mediaLibrary.selectAll') }}</span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <div 
              v-for="item in media.data" 
              :key="item.id"
              class="group relative bg-gray-50 rounded-xl overflow-hidden border-2 transition-all"
              :class="selectedMedia.includes(item.id) ? 'border-orange-500 ring-2 ring-orange-200' : 'border-transparent hover:border-orange-300'"
            >
              <!-- Selection Checkbox -->
              <div class="absolute top-2 left-2 z-10">
                <input 
                  type="checkbox"
                  :checked="selectedMedia.includes(item.id)"
                  @click.stop="toggleSelection(item.id)"
                  class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 shadow-sm"
                >
              </div>

              <!-- Media Preview -->
              <div class="aspect-square cursor-pointer" @click="viewMedia(item)">
                <img 
                  v-if="item.is_image"
                  :src="item.thumbnail_url || item.url" 
                  :alt="item.alt_text || item.original_name"
                  class="w-full h-full object-cover"
                  loading="lazy"
                >
                <div v-else class="flex flex-col items-center justify-center h-full bg-gradient-to-br from-gray-100 to-gray-200">
                  <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                  </svg>
                  <span class="text-xs text-gray-500 font-medium">Video</span>
                </div>
              </div>

              <!-- Media Info -->
              <div class="p-2 bg-white">
                <p class="text-xs font-medium text-gray-900 truncate" :title="item.original_name">
                  {{ item.original_name }}
                </p>
                <p class="text-xs text-gray-500">{{ item.size_human }}</p>
                <p v-if="item.dimensions" class="text-xs text-gray-400">
                  {{ item.dimensions.width }}×{{ item.dimensions.height }}
                </p>
              </div>

              <!-- Action Overlay -->
              <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                <button 
                  @click.stop="viewMedia(item)"
                  class="px-3 py-1.5 bg-white text-gray-900 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                >
                  View
                </button>
                <button 
                  @click.stop="deleteMedia(item.id)"
                  class="px-3 py-1.5 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg text-sm font-medium hover:from-red-100 hover:to-red-50 transition-colors"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="media.last_page > 1" class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
          <div class="text-sm text-gray-700">
            {{ $t('mediaLibrary.showing') }} <span class="font-semibold">{{ media.from }}</span> {{ $t('mediaLibrary.to') }} <span class="font-semibold">{{ media.to }}</span> {{ $t('mediaLibrary.of') }} <span class="font-semibold">{{ media.total }}</span> {{ $t('mediaLibrary.mediaFiles') }}
          </div>
          <div class="flex gap-1">
            <button 
              v-if="media.current_page > 1"
              @click="changePage(media.current_page - 1)"
              class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
            >
              {{ $t('mediaLibrary.previous') }}
            </button>
            <button 
              v-for="page in paginationPages" 
              :key="page"
              @click="changePage(page)"
              :class="[
                'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
                page === media.current_page 
                  ? 'bg-orange-600 text-white' 
                  : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
              ]"
            >
              {{ page }}
            </button>
            <button 
              v-if="media.current_page < media.last_page"
              @click="changePage(media.current_page + 1)"
              class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              {{ $t('mediaLibrary.next') }}
            </button>
          </div>
        </div>
      </div>

      <!-- View Modal -->
      <div v-if="viewingMedia" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="viewingMedia = null">
        <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-auto" @click.stop>
          <div class="p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-2xl font-bold text-gray-900">{{ viewingMedia.original_name }}</h3>
              <button @click="viewingMedia = null" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Media Preview -->
            <div class="mb-6 bg-gray-50 rounded-lg overflow-hidden">
              <img 
                v-if="viewingMedia.is_image"
                :src="viewingMedia.url" 
                :alt="viewingMedia.alt_text"
                class="w-full max-h-[60vh] object-contain"
              >
              <video 
                v-else
                :src="viewingMedia.url"
                controls
                class="w-full max-h-[60vh]"
              ></video>
            </div>

            <!-- Details -->
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Size</label>
                <p class="text-gray-900">{{ viewingMedia.size_human }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <p class="text-gray-900">{{ viewingMedia.mime_type }}</p>
              </div>
              <div v-if="viewingMedia.dimensions" class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dimensions</label>
                <p class="text-gray-900">{{ viewingMedia.dimensions.width }} × {{ viewingMedia.dimensions.height }} pixels</p>
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                <div class="flex gap-2">
                  <input 
                    type="text" 
                    :value="viewingMedia.url" 
                    readonly
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono"
                    @click="$event.target.select()"
                  >
                  <button
                    @click="copyUrl(viewingMedia.url)"
                    class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 text-sm font-medium"
                  >
                    Copy
                  </button>
                </div>
              </div>
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Uploaded</label>
                <p class="text-gray-900">{{ formatDate(viewingMedia.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showDeleteModal = false">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ $t('mediaLibrary.deleteTitle') }}</h3>
          </div>
          
          <p class="text-gray-600 mb-6">
            {{ $t('mediaLibrary.deleteConfirm') }}
          </p>
          
          <div class="flex gap-3">
            <button
              @click="showDeleteModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
            >
              {{ $t('mediaLibrary.cancel') }}
            </button>
            <button
              @click="confirmDelete"
              class="flex-1 px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
            >
              {{ $t('mediaLibrary.delete') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const { t } = useI18n();

const props = defineProps({
  media: Object,
  filters: Object,
  user: Object,
});

// State
const showUploadZone = ref(false);
const uploading = ref(false);
const uploadProgress = ref(0);
const uploadQueue = ref([]);
const dragOver = ref(false);
const selectedMedia = ref([]);
const viewingMedia = ref(null);
const showDeleteModal = ref(false);
const selectedMediaId = ref(null);

// Filters
const searchQuery = ref(props.filters?.search || '');
const typeFilter = ref(props.filters?.type || '');
const sortBy = ref(props.filters?.sort_by || 'created_at');

// Computed
const paginationPages = computed(() => {
  const pages = [];
  const current = props.media.current_page;
  const last = props.media.last_page;
  
  // Show max 7 pages
  let start = Math.max(1, current - 3);
  let end = Math.min(last, current + 3);
  
  if (end - start < 6) {
    if (start === 1) {
      end = Math.min(last, start + 6);
    } else if (end === last) {
      start = Math.max(1, end - 6);
    }
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  
  return pages;
});

// Methods
let searchTimeout;
const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
};

const applyFilters = () => {
  router.get('/admin/media', {
    search: searchQuery.value,
    type: typeFilter.value,
    sort_by: sortBy.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const changePage = (page) => {
  router.get('/admin/media', {
    page,
    search: searchQuery.value,
    type: typeFilter.value,
    sort_by: sortBy.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
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

  let completed = 0;

  for (const file of files) {
    const formData = new FormData();
    formData.append('file', file);

    try {
      await router.post('/admin/media', formData, {
        preserveScroll: true,
        onSuccess: () => {
          completed++;
          uploadProgress.value = Math.round((completed / files.length) * 100);
        },
      });
    } catch (error) {
      console.error('Upload failed:', error);
    }
  }

  uploading.value = false;
  uploadQueue.value = [];
  uploadProgress.value = 0;
  router.reload({ preserveScroll: true });
};

const toggleSelection = (id) => {
  const index = selectedMedia.value.indexOf(id);
  if (index > -1) {
    selectedMedia.value.splice(index, 1);
  } else {
    selectedMedia.value.push(id);
  }
};

const selectAll = () => {
  if (selectedMedia.value.length === props.media.data.length) {
    selectedMedia.value = [];
  } else {
    selectedMedia.value = props.media.data.map(m => m.id);
  }
};

const viewMedia = (item) => {
  viewingMedia.value = item;
};

const deleteMedia = (id) => {
  selectedMediaId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (!selectedMediaId.value) return;
  
  router.delete(`/admin/media/${selectedMediaId.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      selectedMedia.value = selectedMedia.value.filter(id => id !== selectedMediaId.value);
      showDeleteModal.value = false;
      selectedMediaId.value = null;
    },
  });
};

const bulkDelete = () => {
  if (!confirm(`Delete ${selectedMedia.value.length} file(s)? This action cannot be undone.`)) return;

  router.post('/admin/media/bulk-destroy', {
    media_ids: selectedMedia.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      selectedMedia.value = [];
    },
  });
};

const copyUrl = (url) => {
  navigator.clipboard.writeText(url);
  alert('URL copied to clipboard!');
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>
