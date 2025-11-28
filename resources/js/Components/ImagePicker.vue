<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  modelValue: [String, Number], // media_id or URL
  show: Boolean,
});

const emit = defineEmits(['update:modelValue', 'update:show', 'select']);

const searchQuery = ref('');
const mediaType = ref('images'); // 'all', 'images', 'documents'
const media = ref([]);
const loading = ref(false);
const selectedMedia = ref(null);

const filteredMedia = computed(() => {
  let filtered = media.value;

  // Filter by type
  if (mediaType.value === 'images') {
    filtered = filtered.filter(m => m.is_image);
  } else if (mediaType.value === 'documents') {
    filtered = filtered.filter(m => !m.is_image);
  }

  // Filter by search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(m => 
      m.filename.toLowerCase().includes(query) ||
      (m.alt_text && m.alt_text.toLowerCase().includes(query))
    );
  }

  return filtered;
});

const loadMedia = async () => {
  loading.value = true;
  try {
    const response = await fetch('/admin/media/list?per_page=100', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin', // Include session cookies
    });
    const data = await response.json();
    media.value = data.data || [];
  } catch (error) {
    console.error('Failed to load media:', error);
  } finally {
    loading.value = false;
  }
};

const selectMedia = (item) => {
  selectedMedia.value = item;
  emit('update:modelValue', item.id);
  emit('select', item);
  closeModal();
};

const closeModal = () => {
  emit('update:show', false);
};

onMounted(() => {
  if (props.show) {
    loadMedia();
  }
});

// Watch for show prop changes
const showModal = computed(() => props.show);
if (showModal.value) {
  loadMedia();
}
</script>

<template>
  <div v-if="show" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="closeModal">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col" @click.stop>
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <div>
          <h3 class="text-xl font-bold text-gray-900">Select Image</h3>
          <p class="text-sm text-gray-600 mt-1">Choose an image from your media library</p>
        </div>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Filters -->
      <div class="p-4 border-b bg-gray-50">
        <div class="flex gap-4">
          <div class="flex-1">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search images..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
            />
          </div>
          <select
            v-model="mediaType"
            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm"
          >
            <option value="all">All Files</option>
            <option value="images">Images Only</option>
            <option value="documents">Documents Only</option>
          </select>
        </div>
      </div>

      <!-- Media Grid -->
      <div class="flex-1 overflow-y-auto p-6">
        <div v-if="loading" class="flex items-center justify-center py-12">
          <div class="text-gray-400">
            <svg class="animate-spin h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm">Loading media...</p>
          </div>
        </div>

        <div v-else-if="filteredMedia.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-gray-600">No images found</p>
          <p class="text-sm text-gray-500 mt-1">Upload images to your media library first</p>
        </div>

        <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
          <button
            v-for="item in filteredMedia"
            :key="item.id"
            @click="selectMedia(item)"
            class="group relative aspect-square bg-gray-100 rounded-lg overflow-hidden hover:ring-2 hover:ring-orange-500 transition-all"
            :class="{ 'ring-2 ring-orange-500': item.id === modelValue }"
          >
            <!-- Image Preview -->
            <img
              v-if="item.is_image"
              :src="item.thumbnail_url || item.url"
              :alt="item.alt_text || item.filename"
              class="w-full h-full object-cover"
            />
            <!-- Document Icon -->
            <div v-else class="flex items-center justify-center h-full">
              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>

            <!-- Overlay on Hover -->
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all flex items-center justify-center">
              <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>

            <!-- Selected Indicator -->
            <div v-if="item.id === modelValue" class="absolute top-2 right-2 bg-orange-600 text-white rounded-full p-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>

            <!-- Filename -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
              <p class="text-white text-xs truncate">{{ item.filename }}</p>
            </div>
          </button>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-4 border-t bg-gray-50 flex justify-between items-center">
        <p class="text-sm text-gray-600">
          {{ filteredMedia.length }} image{{ filteredMedia.length !== 1 ? 's' : '' }} available
        </p>
        <div class="flex gap-2">
          <button
            @click="closeModal"
            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium"
          >
            Cancel
          </button>
          <a
            href="/admin/media"
            target="_blank"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium"
          >
            Manage Media
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
