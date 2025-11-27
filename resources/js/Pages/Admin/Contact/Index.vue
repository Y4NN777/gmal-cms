<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Contact Messages</h2>
        <p class="mt-2 text-gray-600">Manage and respond to contact form submissions</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Messages</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total }}</p>
            </div>
            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
              <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-orange-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Unread</p>
              <p class="text-3xl font-bold text-orange-600 mt-2">{{ stats.unread }}</p>
            </div>
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
              <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-green-100">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Read</p>
              <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.read }}</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
              <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input
              v-model="searchQuery"
              @input="debouncedSearch"
              type="text"
              placeholder="Search by name, email, subject, or message..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="statusFilter"
              @change="applyFilters"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            >
              <option value="">All Messages</option>
              <option value="unread">Unread Only</option>
              <option value="read">Read Only</option>
            </select>
          </div>
        </div>

        <!-- Bulk Actions -->
        <div v-if="selectedMessages.length > 0" class="mt-6 flex items-center justify-between p-4 bg-orange-50 border border-orange-200 rounded-xl">
          <span class="text-sm font-medium text-gray-700">
            {{ selectedMessages.length }} message(s) selected
          </span>
          <button
            @click="bulkDelete"
            class="px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 text-sm font-medium transition-colors"
          >
            Delete Selected
          </button>
        </div>
      </div>

      <!-- Messages List -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Empty State -->
        <div v-if="messages.data.length === 0" class="text-center py-16">
          <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          <h3 class="mt-4 text-xl font-semibold text-gray-900">No messages found</h3>
          <p class="mt-2 text-gray-500">There are no contact messages matching your filters.</p>
        </div>

        <!-- Messages -->
        <div v-else>
          <!-- Select All -->
          <div class="flex items-center gap-2 px-6 py-4 bg-gray-50 border-b">
            <input
              type="checkbox"
              :checked="selectedMessages.length === messages.data.length"
              @change="selectAll"
              class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded"
            />
            <span class="text-sm text-gray-600">Select All</span>
          </div>

          <!-- Message Items -->
          <div
            v-for="message in messages.data"
            :key="message.id"
            class="border-b last:border-b-0"
          >
            <!-- Message Header -->
            <div
              class="px-6 py-4 flex items-start gap-4 cursor-pointer hover:bg-gray-50 transition-colors"
              :class="{ 'bg-orange-50': !message.read_at }"
              @click="toggleMessage(message.id)"
            >
              <!-- Checkbox -->
              <input
                type="checkbox"
                :checked="selectedMessages.includes(message.id)"
                @click.stop="toggleSelection(message.id)"
                class="h-5 w-5 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-1"
              />

              <!-- Message Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between mb-2">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                      <h3 class="font-semibold text-gray-900">{{ message.name }}</h3>
                      <span v-if="!message.read_at" class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-full border border-orange-200">
                        New
                      </span>
                      <span v-if="message.priority" class="px-2 py-0.5 text-xs font-medium rounded-full border" :class="getPriorityColor(message.priority)">
                        {{ message.priority }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-600">{{ message.email }}</p>
                    <p v-if="message.phone" class="text-sm text-gray-500">{{ message.phone }}</p>
                  </div>
                  
                  <span class="text-sm text-gray-500 whitespace-nowrap ml-4">
                    {{ formatDate(message.created_at) }}
                  </span>
                </div>

                <div class="mb-2">
                  <p class="font-medium text-gray-900">{{ message.subject || 'No subject' }}</p>
                  <p class="text-sm text-gray-600 line-clamp-2 mt-1">{{ message.message }}</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 mt-3">
                  <button
                    v-if="!message.read_at"
                    @click.stop="markAsRead(message.id)"
                    class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors"
                  >
                    ✓ Mark as Read
                  </button>
                  <button
                    v-else
                    @click.stop="markAsUnread(message.id)"
                    class="px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                  >
                    Mark as Unread
                  </button>
                  <button
                    @click.stop="deleteMessage(message.id)"
                    class="px-3 py-1.5 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg text-sm font-medium hover:from-red-100 hover:to-red-50 transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>

            <!-- Expanded Message Content -->
            <div v-if="expandedMessage === message.id" class="px-6 py-6 bg-gray-50 border-t">
              <div class="max-w-4xl">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Full Message:</h4>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                  <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ message.message }}</p>
                </div>
                
                <div v-if="message.internal_notes" class="mt-4">
                  <h4 class="text-sm font-semibold text-gray-900 mb-2">Internal Notes:</h4>
                  <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <p class="text-gray-700 text-sm">{{ message.internal_notes }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="messages.last_page > 1" class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Showing <span class="font-semibold">{{ messages.from }}</span> to <span class="font-semibold">{{ messages.to }}</span> of <span class="font-semibold">{{ messages.total }}</span> messages
          </div>
          <div class="flex gap-1">
            <button
              v-if="messages.current_page > 1"
              @click="changePage(messages.current_page - 1)"
              class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-white transition-colors"
            >
              Previous
            </button>
            <button
              v-for="page in paginationPages"
              :key="page"
              @click="changePage(page)"
              :class="[
                'px-3 py-1.5 rounded-lg text-sm font-medium transition-colors',
                page === messages.current_page
                  ? 'bg-orange-600 text-white'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
              ]"
            >
              {{ page }}
            </button>
            <button
              v-if="messages.current_page < messages.last_page"
              @click="changePage(messages.current_page + 1)"
              class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-white transition-colors"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900">Delete Message?</h3>
        </div>

        <p class="text-gray-600 mb-6">
          Are you sure you want to permanently delete this message? This action cannot be undone.
        </p>

        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors"
          >
            Cancel
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-gradient-to-r from-red-50 to-white text-red-700 border border-red-200 rounded-lg hover:from-red-100 hover:to-red-50 font-medium transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  messages: Object,
  filters: Object,
  stats: Object,
  user: Object,
});

// State
const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const selectedMessages = ref([]);
const expandedMessage = ref(null);
const showDeleteModal = ref(false);
const messageToDelete = ref(null);

// Computed
const paginationPages = computed(() => {
  const pages = [];
  const current = props.messages.current_page;
  const last = props.messages.last_page;

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
  router.get('/admin/contact-messages', {
    search: searchQuery.value,
    status: statusFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const changePage = (page) => {
  router.get('/admin/contact-messages', {
    page,
    search: searchQuery.value,
    status: statusFilter.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

const toggleMessage = (id) => {
  expandedMessage.value = expandedMessage.value === id ? null : id;
};

const markAsRead = (id) => {
  router.post(`/admin/contact-messages/${id}/mark-read`, {}, {
    preserveScroll: true,
  });
};

const markAsUnread = (id) => {
  router.post(`/admin/contact-messages/${id}/mark-unread`, {}, {
    preserveScroll: true,
  });
};

const deleteMessage = (id) => {
  messageToDelete.value = id;
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  if (!messageToDelete.value) return;

  router.delete(`/admin/contact-messages/${messageToDelete.value}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      messageToDelete.value = null;
      selectedMessages.value = selectedMessages.value.filter(id => id !== messageToDelete.value);
    },
  });
};

const toggleSelection = (id) => {
  const index = selectedMessages.value.indexOf(id);
  if (index > -1) {
    selectedMessages.value.splice(index, 1);
  } else {
    selectedMessages.value.push(id);
  }
};

const selectAll = () => {
  if (selectedMessages.value.length === props.messages.data.length) {
    selectedMessages.value = [];
  } else {
    selectedMessages.value = props.messages.data.map(m => m.id);
  }
};

const bulkDelete = () => {
  if (selectedMessages.value.length === 0) return;

  if (confirm(`Delete ${selectedMessages.value.length} message(s)? This action cannot be undone.`)) {
    router.post('/admin/contact-messages/bulk-destroy', {
      ids: selectedMessages.value,
    }, {
      preserveScroll: true,
      onSuccess: () => {
        selectedMessages.value = [];
      },
    });
  }
};

const getPriorityColor = (priority) => {
  const colors = {
    low: 'bg-gray-50 text-gray-600 border-gray-200',
    normal: 'bg-blue-50 text-blue-700 border-blue-200',
    high: 'bg-orange-50 text-orange-700 border-orange-200',
    urgent: 'bg-gradient-to-r from-red-50 to-white text-red-700 border-red-200',
  };
  return colors[priority] || colors.normal;
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
};
</script>
