<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useDate } from '@/composables/useDate';
import { Eye, Check, X, Trash2 } from 'lucide-vue-next';

const { formatShortDate } = useDate();

const props = defineProps({
  user: Object,
  testimonials: Object,
  stats: Object,
  filters: Object,
});

const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const featuredFilter = ref(props.filters?.featured || '');
const selectedTestimonials = ref([]);

const applyFilters = () => {
  router.get('/admin/testimonials', {
    search: searchQuery.value,
    status: statusFilter.value,
    featured: featuredFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const approve = (id) => {
  router.post(`/admin/testimonials/${id}/approve`, {}, {
    preserveScroll: true,
  });
};

const reject = (id) => {
  router.post(`/admin/testimonials/${id}/reject`, {}, {
    preserveScroll: true,
  });
};

const bulkApprove = () => {
  if (selectedTestimonials.value.length === 0) return;
  
  router.post('/admin/testimonials/bulk-approve', {
    testimonial_ids: selectedTestimonials.value,
  }, {
    preserveScroll: true,
    onSuccess: () => {
      selectedTestimonials.value = [];
    },
  });
};

const testimonialToDelete = ref(null);
const showDeleteModal = ref(false);

const confirmDelete = (id) => {
  testimonialToDelete.value = id;
  showDeleteModal.value = true;
};

const deleteTestimonial = () => {
  if (!testimonialToDelete.value) return;
  
  router.delete(`/admin/testimonials/${testimonialToDelete.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      testimonialToDelete.value = null;
    },
  });
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  testimonialToDelete.value = null;
};

const toggleSelection = (id) => {
  const index = selectedTestimonials.value.indexOf(id);
  if (index > -1) {
    selectedTestimonials.value.splice(index, 1);
  } else {
    selectedTestimonials.value.push(id);
  }
};

const selectAll = () => {
  if (selectedTestimonials.value.length === props.testimonials.data.length) {
    selectedTestimonials.value = [];
  } else {
    selectedTestimonials.value = props.testimonials.data.map(t => t.id);
  }
};

const getStatusColor = (status) => {
  const colors = {
    pending: 'bg-yellow-50 text-yellow-700 border border-yellow-200',
    approved: 'bg-green-50 text-green-700 border border-green-200',
    rejected: 'bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200',
  };
  return colors[status] || 'bg-gray-50 text-gray-600 border border-gray-200';
};

const formatDate = (date) => {
  if (!date) return '';
  return formatShortDate(date);
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="space-y-6">
      <!-- Header -->
      <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-center">
        <div>
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $t('testimonials.title') }}</h2>
          <p class="text-sm sm:text-base text-gray-600 mt-1">{{ $t('testimonials.description') }}</p>
        </div>
        
        <!-- Stats -->
        <div class="flex gap-3 sm:gap-4 flex-wrap">
          <div class="bg-yellow-50 px-3 sm:px-4 py-2 rounded-lg">
            <div class="text-xl sm:text-2xl font-bold text-yellow-800">{{ stats.pending }}</div>
            <div class="text-xs text-yellow-600">{{ $t('testimonials.pending') }}</div>
          </div>
          <div class="bg-green-50 px-3 sm:px-4 py-2 rounded-lg">
            <div class="text-xl sm:text-2xl font-bold text-green-800">{{ stats.approved }}</div>
            <div class="text-xs text-green-600">{{ $t('testimonials.approved') }}</div>
          </div>
          <div class="bg-red-50 px-3 sm:px-4 py-2 rounded-lg">
            <div class="text-xl sm:text-2xl font-bold text-red-800">{{ stats.rejected }}</div>
            <div class="text-xs text-red-600">{{ $t('testimonials.rejected') }}</div>
          </div>
        </div>
      </div>

      <!-- Filters & Actions -->
      <div class="bg-white rounded-lg shadow p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
          <!-- Search -->
          <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">{{ $t('testimonials.search') }}</label>
            <input
              v-model="searchQuery"
              type="text"
              :placeholder="$t('testimonials.searchPlaceholder')"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @input="applyFilters"
            />
          </div>
          
          <!-- Status Filter -->
          <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">{{ $t('testimonials.status') }}</label>
            <select
              v-model="statusFilter"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">{{ $t('testimonials.allStatus') }}</option>
              <option value="pending">{{ $t('status.pending') }}</option>
              <option value="approved">{{ $t('status.approved') }}</option>
              <option value="rejected">{{ $t('status.rejected') }}</option>
            </select>
          </div>

          <!-- Featured Filter -->
          <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5 sm:mb-2">{{ $t('testimonials.featured') }}</label>
            <select
              v-model="featuredFilter"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">{{ $t('testimonials.all') }}</option>
              <option value="true">{{ $t('testimonials.featuredOnly') }}</option>
            </select>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 mt-4">
          <button
            @click="searchQuery = ''; statusFilter = ''; featuredFilter = ''; applyFilters()"
            class="px-3 sm:px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
          >
            {{ $t('testimonials.clear') }}
          </button>

          <button
            v-if="selectedTestimonials.length > 0"
            @click="bulkApprove"
            class="px-3 sm:px-4 py-2 text-sm bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 font-medium transition-colors"
          >
            ✓ <span class="hidden sm:inline">{{ $t('testimonials.approveSelected') }}</span> ({{ selectedTestimonials.length }})
          </button>
        </div>
      </div>

      <!-- Testimonials Grid -->
      <div v-if="testimonials.data && testimonials.data.length > 0" class="space-y-3 sm:space-y-4">
        <!-- Select All -->
        <div class="flex items-center gap-2 px-4">
          <input
            type="checkbox"
            :checked="selectedTestimonials.length === testimonials.data.length"
            @change="selectAll"
            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
          />
          <span class="text-sm text-gray-600">{{ $t('testimonials.selectAll') }}</span>
        </div>

        <div
          v-for="testimonial in testimonials.data"
          :key="testimonial.id"
          class="bg-white rounded-lg shadow p-4 sm:p-6 hover:shadow-md transition-shadow"
        >
          <div class="flex gap-3 sm:gap-4">
            <!-- Checkbox -->
            <input
              type="checkbox"
              :checked="selectedTestimonials.includes(testimonial.id)"
              @change="toggleSelection(testimonial.id)"
              class="h-5 w-5 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-1"
            />

            <!-- Avatar -->
            <div class="flex-shrink-0">
              <img 
                v-if="testimonial.avatar"
                :src="testimonial.avatar" 
                :alt="testimonial.name"
                class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-gray-200"
              />
              <div 
                v-else
                class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-lg sm:text-xl border-2 border-orange-300"
              >
                {{ testimonial.name?.charAt(0).toUpperCase() || '?' }}
              </div>
            </div>

            <!-- Content -->
            <div class="flex-1">
              <div class="flex items-start justify-between mb-3 flex-wrap gap-2">
                <div>
                  <h3 class="text-sm sm:text-base font-bold text-gray-900">{{ testimonial.name }}</h3>
                  <p v-if="testimonial.position" class="text-xs sm:text-sm text-gray-600">{{ testimonial.position }}</p>
                  <p v-if="testimonial.organization" class="text-xs text-gray-500">{{ testimonial.organization }}</p>
                </div>
                
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                  <!-- Rating -->
                  <div class="flex items-center gap-1">
                    <svg v-for="i in testimonial.rating" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </div>
                  
                  <!-- Status -->
                  <span class="px-2 py-1 text-xs font-medium rounded whitespace-nowrap" :class="getStatusColor(testimonial.status)">
                    {{ $t('status.' + testimonial.status) }}
                  </span>

                  <!-- Featured Badge -->
                  <span v-if="testimonial.is_featured" class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded whitespace-nowrap">
                    ⭐ {{ $t('testimonials.featured') }}
                  </span>
                </div>
              </div>

              <!-- Testimonial Content -->
              <p class="text-sm sm:text-base text-gray-700 mb-4 leading-relaxed">{{ testimonial.content }}</p>

              <!-- Meta Info -->
              <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                <span>{{ $t('testimonials.submitted') }}: {{ formatDate(testimonial.created_at) }}</span>
                <span v-if="testimonial.approved_at">{{ $t('testimonials.approved') }}: {{ formatDate(testimonial.approved_at) }}</span>
              </div>

              <!-- Actions -->
              <div class="flex flex-wrap gap-1.5 sm:gap-2">
                <Link
                  :href="`/admin/testimonials/${testimonial.id}`"
                  class="px-2 sm:px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors inline-flex items-center"
                >
                  <Eye :size="14" class="sm:mr-1.5" />
                  <span class="hidden sm:inline ml-1.5">{{ $t('testimonials.viewDetails') }}</span>
                </Link>
                <button
                  v-if="testimonial.status !== 'approved'"
                  @click="approve(testimonial.id)"
                  class="px-2 sm:px-4 py-2 text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors inline-flex items-center"
                >
                  <Check :size="14" class="sm:mr-1.5" />
                  <span class="hidden sm:inline ml-1.5">{{ $t('testimonials.approve') }}</span>
                </button>
                <button
                  v-if="testimonial.status !== 'rejected'"
                  @click="reject(testimonial.id)"
                  class="px-2 sm:px-4 py-2 text-sm font-medium text-red-700 bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border border-red-200 rounded-lg transition-colors inline-flex items-center"
                >
                  <X :size="14" class="sm:mr-1.5" />
                  <span class="hidden sm:inline ml-1.5">{{ $t('testimonials.reject') }}</span>
                </button>
                <button
                  @click="confirmDelete(testimonial.id)"
                  class="px-2 sm:px-4 py-2 text-sm font-medium text-red-700 bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border border-red-200 rounded-lg transition-colors inline-flex items-center"
                >
                  <Trash2 :size="14" class="sm:mr-1.5" />
                  <span class="hidden sm:inline ml-1.5">{{ $t('testimonials.delete') }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
        </svg>
        <p class="text-gray-500">{{ $t('testimonials.noTestimonialsFound') }}</p>
      </div>

      <!-- Pagination -->
      <div v-if="testimonials.data && testimonials.data.length > 0" class="flex justify-between items-center bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">
          Showing {{ testimonials.from }} to {{ testimonials.to }} of {{ testimonials.total }} testimonials
        </div>
        
        <div class="flex gap-2">
          <button
            v-for="link in testimonials.links"
            :key="link.label"
            @click="link.url ? router.get(link.url, {}, { preserveState: true, preserveScroll: true }) : null"
            :disabled="!link.url || link.active"
            class="px-3 py-1 text-sm rounded"
            :class="[
              link.active 
                ? 'bg-orange-600 text-white' 
                : link.url 
                  ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' 
                  : 'bg-gray-50 text-gray-400 cursor-not-allowed'
            ]"
            v-html="link.label"
          />
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click="cancelDelete"
    >
      <div
        class="bg-white rounded-lg shadow-xl max-w-md w-full p-6"
        @click.stop
      >
        <div class="flex items-center gap-4 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $t('testimonials.deleteTitle') }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $t('testimonials.deleteConfirm') }}</p>
          </div>
        </div>

        <div class="flex gap-3 justify-end mt-6">
          <button
            @click="cancelDelete"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            {{ $t('testimonials.cancel') }}
          </button>
          <button
            @click="deleteTestimonial"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
          >
            {{ $t('testimonials.delete') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
