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
                  v-model="filters.search"
                  type="text"
                  placeholder="Search events..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  @input="applyFilters"
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                  v-model="filters.status"
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
                  v-model="filters.category"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  @change="applyFilters"
                >
                  <option value="">All Categories</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select
                  v-model="filters.sort"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  @change="applyFilters"
                >
                  <option value="created_at">Newest First</option>
                  <option value="event_date">Event Date</option>
                  <option value="title">Title A-Z</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Events Table -->
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Event
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="loading">
                  <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <div class="flex justify-center items-center">
                      <svg class="animate-spin h-8 w-8 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                    </div>
                  </td>
                </tr>
                <tr v-else-if="events.length === 0">
                  <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    No events found. Create your first event!
                  </td>
                </tr>
                <tr v-else v-for="event in events" :key="event.id" class="hover:bg-gray-50">
                  <td class="px-6 py-4">
                    <div class="flex items-center">
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ event.title }}</div>
                        <div class="text-sm text-gray-500">{{ event.excerpt?.substring(0, 60) }}...</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded" :style="{ backgroundColor: event.category?.color + '20', color: event.category?.color }">
                      {{ event.category?.name }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ formatDate(event.event_date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="px-2 py-1 text-xs font-medium rounded"
                      :class="{
                        'bg-green-100 text-green-800': event.status === 'published',
                        'bg-yellow-100 text-yellow-800': event.status === 'draft',
                        'bg-gray-100 text-gray-800': event.status === 'archived',
                      }"
                    >
                      {{ event.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button @click="editEvent(event.id)" class="text-orange-600 hover:text-orange-900 mr-3">
                      Edit
                    </button>
                    <button @click="deleteEvent(event.id)" class="text-red-600 hover:text-red-900">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pagination.total > pagination.per_page" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                  Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} events
                </div>
                <div class="flex gap-2">
                  <button
                    v-for="page in paginationPages"
                    :key="page"
                    @click="goToPage(page)"
                    class="px-3 py-1 rounded"
                    :class="page === pagination.current_page ? 'bg-orange-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100'"
                  >
                    {{ page }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

    <!-- Delete Event Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm flex items-center justify-center z-50" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Delete Event</h3>
        </div>
        
        <p class="text-gray-600 mb-6">
          Are you sure you want to permanently delete this event? This action cannot be undone and will also delete all associated data.
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
            Delete Event
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const user = ref(null);
const events = ref([]);
const categories = ref([]);
const loading = ref(true);
const showDeleteModal = ref(false);
const selectedEventId = ref(null);

const pagination = ref({
  current_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0,
});

const filters = reactive({
  search: '',
  status: '',
  category: '',
  sort: 'created_at',
});

const paginationPages = computed(() => {
  const pages = [];
  const totalPages = Math.ceil(pagination.value.total / pagination.value.per_page);
  for (let i = 1; i <= totalPages; i++) {
    pages.push(i);
  }
  return pages;
});

onMounted(async () => {
  const token = localStorage.getItem('token');
  const userData = localStorage.getItem('user');
  
  if (!token || !userData) {
    router.visit('/admin/login');
    return;
  }
  
  user.value = JSON.parse(userData);
  await loadEvents();
  await loadCategories();
});

const loadEvents = async () => {
  loading.value = true;
  const token = localStorage.getItem('token');
  
  try {
    // Build query params
    const params = new URLSearchParams({
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      ...(filters.search && { search: filters.search }),
      ...(filters.status && { status: filters.status }),
      ...(filters.category && { category: filters.category }),
      ...(filters.sort && { sort: filters.sort }),
    });
    
    const response = await fetch(`/api/v1/events?${params}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    if (!response.ok) throw new Error('Failed to load events');
    
    const data = await response.json();
    events.value = data.data;
    pagination.value = data.meta.pagination;
  } catch (error) {
    console.error('Failed to load events:', error);
  } finally {
    loading.value = false;
  }
};

const loadCategories = async () => {
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch('/api/v1/events/categories', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    if (response.ok) {
      const data = await response.json();
      categories.value = data.data || [];
    }
  } catch (error) {
    console.error('Failed to load categories:', error);
  }
};

const applyFilters = () => {
  pagination.value.current_page = 1;
  loadEvents();
};

const goToPage = (page) => {
  pagination.value.current_page = page;
  loadEvents();
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const createEvent = () => {
  router.visit('/admin/events/create');
};

const editEvent = (id) => {
  router.visit(`/admin/events/${id}/edit`);
};

const deleteEvent = (id) => {
  selectedEventId.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = async () => {
  const id = selectedEventId.value;
  showDeleteModal.value = false;
  const token = localStorage.getItem('token');
  
  try {
    const response = await fetch(`/admin/events/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
    });
    
    if (response.ok) {
      await loadEvents();
    }
  } catch (error) {
    console.error('Failed to delete event:', error);
    alert('Failed to delete event');
  } finally {
    selectedEventId.value = null;
  }
};

const logout = async () => {
  const token = localStorage.getItem('token');
  
  try {
    await fetch('/api/v1/auth/logout', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
  } catch (error) {
    console.error('Logout error:', error);
  }
  
  localStorage.removeItem('token');
  localStorage.removeItem('user');
  router.visit('/admin/login');
};
</script>
