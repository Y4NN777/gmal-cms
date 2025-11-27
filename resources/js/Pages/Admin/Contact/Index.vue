<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  messages: Object,
  filters: Object,
  stats: Object,
  user: Object,
});

const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || 'all');
const selectedMessages = ref([]);
const expandedMessage = ref(null);
const showDeleteModal = ref(false);
const messageToDelete = ref(null);

const applyFilters = () => {
  router.get('/admin/contact-messages', {
    search: searchQuery.value,
    status: statusFilter.value === 'all' ? null : statusFilter.value,
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
  
  if (confirm(`Delete ${selectedMessages.value.length} message(s)?`)) {
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

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
          <!-- Header -->
          <div class="mb-8 flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-bold text-gray-900">Contact Messages</h2>
              <p class="text-gray-600 mt-1">Manage contact form submissions</p>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
              <div class="text-sm text-gray-600">Total Messages</div>
              <div class="text-2xl font-bold text-gray-900">{{ stats.total }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
              <div class="text-sm text-gray-600">Unread</div>
              <div class="text-2xl font-bold text-orange-600">{{ stats.unread }}</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
              <div class="text-sm text-gray-600">Read</div>
              <div class="text-2xl font-bold text-green-600">{{ stats.read }}</div>
            </div>
          </div>

          <!-- Filters -->
          <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex gap-4">
              <div class="flex-1">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Search messages..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  @input="applyFilters"
                />
              </div>
              <select
                v-model="statusFilter"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                @change="applyFilters"
              >
                <option value="all">All Messages</option>
                <option value="unread">Unread</option>
                <option value="read">Read</option>
              </select>
            </div>
          </div>

          <!-- Bulk Actions -->
          <div v-if="selectedMessages.length > 0" class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4 flex items-center justify-between">
            <span class="text-sm text-gray-700">{{ selectedMessages.length }} message(s) selected</span>
            <button
              @click="bulkDelete"
              class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium"
            >
              Delete Selected
            </button>
          </div>

          <!-- Messages List -->
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <div v-if="messages.data.length === 0" class="text-center py-12">
              <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <p class="text-gray-600">No messages found</p>
            </div>

            <div v-else>
              <!-- Table Header -->
              <div class="bg-gray-50 border-b px-6 py-3 flex items-center gap-4 text-sm font-medium text-gray-700">
                <input
                  type="checkbox"
                  :checked="selectedMessages.length === messages.data.length && messages.data.length > 0"
                  @change="selectAll"
                  class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                />
                <div class="flex-1 grid grid-cols-12 gap-4">
                  <div class="col-span-3">From</div>
                  <div class="col-span-4">Subject</div>
                  <div class="col-span-3">Date</div>
                  <div class="col-span-2 text-right">Actions</div>
                </div>
              </div>

              <!-- Messages -->
              <div v-for="message in messages.data" :key="message.id" class="border-b last:border-b-0">
                <div
                  class="px-6 py-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
                  :class="{ 'bg-orange-50': !message.read_at }"
                  @click="toggleMessage(message.id)"
                >
                  <input
                    type="checkbox"
                    :checked="selectedMessages.includes(message.id)"
                    @click.stop="toggleSelection(message.id)"
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                  />
                  <div class="flex-1 grid grid-cols-12 gap-4">
                    <div class="col-span-3">
                      <div class="font-medium text-gray-900">{{ message.name }}</div>
                      <div class="text-sm text-gray-600">{{ message.email }}</div>
                    </div>
                    <div class="col-span-4">
                      <div class="font-medium text-gray-900">{{ message.subject || 'No subject' }}</div>
                      <div class="text-sm text-gray-600 truncate">{{ message.message }}</div>
                    </div>
                    <div class="col-span-3 text-sm text-gray-600">
                      {{ formatDate(message.created_at) }}
                    </div>
                    <div class="col-span-2 flex justify-end gap-2">
                      <button
                        v-if="!message.read_at"
                        @click.stop="markAsRead(message.id)"
                        class="text-sm text-green-600 hover:text-green-700 font-medium"
                      >
                        Mark Read
                      </button>
                      <button
                        v-else
                        @click.stop="markAsUnread(message.id)"
                        class="text-sm text-gray-600 hover:text-gray-700 font-medium"
                      >
                        Mark Unread
                      </button>
                      <button
                        @click.stop="deleteMessage(message.id)"
                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Expanded Message -->
                <div v-if="expandedMessage === message.id" class="px-6 py-4 bg-gray-50 border-t">
                  <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ message.message }}</p>
                  </div>
                  <div v-if="message.phone" class="mt-4 text-sm text-gray-600">
                    <strong>Phone:</strong> {{ message.phone }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="messages.links && messages.links.length > 3" class="mt-6 flex justify-center">
            <nav class="flex gap-2">
              <template v-for="(link, index) in messages.links" :key="index">
                <a
                  v-if="link.url"
                  :href="link.url"
                  class="px-3 py-2 border rounded-lg text-sm font-medium"
                  :class="link.active ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                  v-html="link.label"
                ></a>
                <span
                  v-else
                  class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400"
                  v-html="link.label"
                ></span>
              </template>
            </nav>
          </div>
        </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Delete Message?</h3>
        <p class="text-gray-600 text-center mb-6">
          Are you sure you want to delete this message? This action cannot be undone.
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
            Delete Message
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
