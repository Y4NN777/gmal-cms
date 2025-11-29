<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useCategory } from '@/composables/useCategory';
import { useDate } from '@/composables/useDate';
import { useStatus } from '@/composables/useStatus';

const { translateCategory } = useCategory();
const { formatShortDate } = useDate();
const { translateStatus } = useStatus();

const props = defineProps({
  user: Object,
  events: Object,
  categories: Array,
  filters: Object,
});

const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const categoryFilter = ref(props.filters?.category || '');
const showDeleteModal = ref(false);
const selectedEventId = ref(null);

const applyFilters = () => {
  router.get('/admin/events', {
    search: searchQuery.value,
    status: statusFilter.value,
    category: categoryFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const createEvent = () => {
  router.visit('/admin/events/create');
};

const editEvent = (id) => {
  router.visit(`/admin/events/${id}/edit`);
};

const viewEvent = (id) => {
  router.visit(`/admin/events/${id}`);
};

const deleteEvent = (id) => {
  selectedEventId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (!selectedEventId.value) return;
  
  router.delete(`/admin/events/${selectedEventId.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      selectedEventId.value = null;
    },
    onError: () => {
      showDeleteModal.value = false;
      selectedEventId.value = null;
    }
  });
};

const getStatusClass = (status) => {
  switch (status) {
    case 'published': return 'bg-green-100 text-green-800';
    case 'draft': return 'bg-gray-100 text-gray-800';
    case 'archived': return 'bg-red-100 text-red-800';
    default: return 'bg-gray-100 text-gray-800';
  }
};

const formatDate = (date) => {
  if (!date) return '';
  return formatShortDate(date);
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">{{ $t('admin.eventManagement.title') }}</h2>
          <p class="text-gray-600 mt-1">{{ $t('admin.eventManagement.description') }}</p>
        </div>
        <button
          @click="createEvent"
          class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
        >
          + {{ $t('admin.eventManagement.createEvent') }}
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('admin.eventManagement.search') }}</label>
            <input
              v-model="searchQuery"
              type="text"
              :placeholder="$t('admin.eventManagement.searchEvents')"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @input="applyFilters"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('admin.eventManagement.status') }}</label>
            <select
              v-model="statusFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">{{ $t('admin.eventManagement.allStatus') }}</option>
              <option value="draft">{{ $t('status.draft') }}</option>
              <option value="published">{{ $t('status.published') }}</option>
              <option value="archived">{{ $t('status.archived') }}</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ $t('admin.eventManagement.category') }}</label>
            <select
              v-model="categoryFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">{{ $t('admin.eventManagement.allCategories') }}</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ translateCategory(category) }}
              </option>
            </select>
          </div>

          <div class="flex items-end">
            <button
              @click="searchQuery = ''; statusFilter = ''; categoryFilter = ''; applyFilters()"
              class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
            >
              {{ $t('admin.eventManagement.clearFilters') }}
            </button>
          </div>
        </div>
      </div>

      <!-- Events List -->
      <div v-if="events.data && events.data.length > 0" class="space-y-4">
        <div
          v-for="event in events.data"
          :key="event.id"
          class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-6"
        >
          <div class="flex gap-4 items-start">
            <!-- Event Thumbnail -->
            <div class="flex-shrink-0">
              <img 
                v-if="event.featured_image"
                :src="event.featured_image" 
                :alt="event.title"
                class="w-24 h-24 rounded-lg object-cover border-2 border-gray-200"
              />
              <div 
                v-else
                class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center border-2 border-gray-300"
              >
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-3 mb-2">
                <h3 class="text-xl font-bold text-gray-900 truncate">{{ event.title }}</h3>
                <span
                  class="px-2 py-1 text-xs font-medium rounded"
                  :class="getStatusClass(event.status)"
                >
                  {{ translateStatus(event.status) }}
                </span>
                <span
                  v-if="event.is_featured"
                  class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded"
                >
                  ⭐ {{ $t('admin.eventManagement.featured') }}
                </span>
              </div>

              <p class="text-gray-600 text-sm mb-3">{{ event.excerpt || $t('admin.eventManagement.noDescription') }}</p>

              <div class="flex items-center gap-6 text-sm text-gray-500">
                <div class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>{{ formatDate(event.event_date) }}</span>
                </div>

                <div v-if="event.location" class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span>{{ event.location }}</span>
                </div>

                <div v-if="event.category" class="flex items-center gap-1">
                  <span
                    class="w-3 h-3 rounded-full"
                    :style="{ backgroundColor: event.category.color }"
                  ></span>
                  <span>{{ translateCategory(event.category) }}</span>
                </div>
              </div>

              <div class="flex gap-2 mt-3">
                <button
                  @click="viewEvent(event.id)"
                  class="px-3 py-1.5 text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors"
                >
                  {{ $t('admin.eventManagement.view') }}
                </button>
                <button
                  @click="editEvent(event.id)"
                  class="px-3 py-1.5 text-sm text-orange-700 bg-orange-50 hover:bg-orange-100 border border-orange-200 rounded-lg transition-colors"
                >
                  {{ $t('admin.eventManagement.edit') }}
                </button>
                <button
                  @click="deleteEvent(event.id)"
                  class="px-3 py-1.5 text-sm text-red-700 bg-gradient-to-r from-red-50 to-white hover:from-red-100 hover:to-red-50 border border-red-200 rounded-lg transition-colors"
                >
                  {{ $t('admin.eventManagement.delete') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="events.data && events.data.length > 0" class="flex justify-between items-center bg-white rounded-lg shadow p-4 mt-6">
        <div class="text-sm text-gray-600">
          Showing {{ events.from }} to {{ events.to }} of {{ events.total }} events
        </div>
        
        <div class="flex gap-2">
          <button
            v-for="link in events.links"
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

      <!-- Empty State -->
      <div v-else class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="text-gray-500">{{ $t('admin.eventManagement.noEventsFound') }}</p>
        <button
          @click="createEvent"
          class="mt-4 px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
        >
          {{ $t('admin.eventManagement.createFirstEvent') }}
        </button>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showDeleteModal = false">
        <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">{{ $t('admin.eventManagement.deleteEventTitle') }}</h3>
          </div>

          <p class="text-gray-600 mb-6">
            {{ $t('admin.eventManagement.deleteEventConfirm') }}
          </p>

          <div class="flex gap-3">
            <button
              @click="showDeleteModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
            >
              {{ $t('admin.cancel') }}
            </button>
            <button
              @click="confirmDelete"
              class="flex-1 px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
            >
              {{ $t('admin.delete') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
