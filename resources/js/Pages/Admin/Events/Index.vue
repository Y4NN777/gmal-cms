<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
      alert('Failed to delete event');
      showDeleteModal.value = false;
      selectedEventId.value = null;
    }
  });
};

const changePage = (page) => {
  router.get('/admin/events', {
    page,
    search: searchQuery.value,
    status: statusFilter.value,
    category: categoryFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
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
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Events Management</h2>
          <p class="text-gray-600 mt-1">Manage all events and campaigns</p>
        </div>
        <button
          @click="createEvent"
          class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
        >
          + Create Event
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search events..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @input="applyFilters"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="statusFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">All Status</option>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select
              v-model="categoryFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              @change="applyFilters"
            >
              <option value="">All Categories</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <div class="flex items-end">
            <button
              @click="searchQuery = ''; statusFilter = ''; categoryFilter = ''; applyFilters()"
              class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium"
            >
              Clear Filters
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
          <div class="flex justify-between items-start">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <h3 class="text-xl font-bold text-gray-900">{{ event.title }}</h3>
                <span
                  class="px-2 py-1 text-xs font-medium rounded"
                  :class="getStatusClass(event.status)"
                >
                  {{ event.status }}
                </span>
                <span
                  v-if="event.is_featured"
                  class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded"
                >
                  ⭐ Featured
                </span>
              </div>

              <p class="text-gray-600 text-sm mb-3">{{ event.excerpt || 'No description' }}</p>

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
                  <span>{{ event.category.name }}</span>
                </div>
              </div>
            </div>

            <div class="flex gap-2">
              <button
                @click="viewEvent(event.id)"
                class="px-3 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg"
                title="View"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              <button
                @click="editEvent(event.id)"
                class="px-3 py-2 text-sm text-orange-600 hover:bg-orange-50 rounded-lg"
                title="Edit"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click="deleteEvent(event.id)"
                class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg"
                title="Delete"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white rounded-lg shadow p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="text-gray-500">No events found</p>
        <button
          @click="createEvent"
          class="mt-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
        >
          Create First Event
        </button>
      </div>

      <!-- Pagination -->
      <div v-if="events.data && events.data.length > 0" class="mt-6 flex justify-between items-center bg-white rounded-lg shadow p-4">
        <div class="text-sm text-gray-600">
          Showing {{ events.from }} to {{ events.to }} of {{ events.total }} events
        </div>
        
        <div class="flex gap-2">
          <button
            v-for="link in events.links"
            :key="link.label"
            @click="link.url ? changePage(new URL(link.url).searchParams.get('page')) : null"
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
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Delete Event</h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this event? This action cannot be undone.</p>
        
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium"
          >
            Delete Event
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
