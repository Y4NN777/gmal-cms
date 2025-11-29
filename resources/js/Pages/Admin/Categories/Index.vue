<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  categories: Array,
});

const showModal = ref(false);
const editingCategory = ref(null);
const showDeleteModal = ref(false);
const categoryToDelete = ref(null);

const form = ref({
  name: '',
  description: '',
  color: '#3B82F6',
  icon: '',
});

const openCreateModal = () => {
  editingCategory.value = null;
  form.value = {
    name: '',
    description: '',
    color: '#3B82F6',
    icon: '',
  };
  showModal.value = true;
};

const openEditModal = (category) => {
  editingCategory.value = category;
  form.value = {
    name: category.name,
    description: category.description || '',
    color: category.color || '#3B82F6',
    icon: category.icon || '',
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingCategory.value = null;
};

const saveCategory = () => {
  if (editingCategory.value) {
    router.put(`/admin/categories/${editingCategory.value.id}`, form.value, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  } else {
    router.post('/admin/categories', form.value, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  }
};

const confirmDelete = (category) => {
  categoryToDelete.value = category;
  showDeleteModal.value = true;
};

const deleteCategory = () => {
  if (!categoryToDelete.value) return;
  
  router.delete(`/admin/categories/${categoryToDelete.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      categoryToDelete.value = null;
    },
  });
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  categoryToDelete.value = null;
};
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Categories Management</h2>
          <p class="text-gray-600 mt-1">Manage event categories</p>
        </div>
        <button
          @click="openCreateModal"
          class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          New Category
        </button>
      </div>

      <!-- Categories Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Events</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="categories.length === 0">
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                  <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                  <p class="text-lg font-medium">No categories yet</p>
                  <p class="text-sm mt-1">Create your first category to organize events</p>
                </td>
              </tr>
              <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                  <div class="text-xs text-gray-500">{{ category.slug }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <div 
                      class="w-8 h-8 rounded-full border-2 border-gray-200"
                      :style="{ backgroundColor: category.color || '#3B82F6' }"
                    ></div>
                    <span class="text-xs text-gray-500">{{ category.color || '#3B82F6' }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm text-gray-900">{{ category.icon || '-' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded">
                    {{ category.events_count }} events
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-600 max-w-xs truncate">
                    {{ category.description || '-' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button
                    @click="openEditModal(category)"
                    class="text-orange-600 hover:text-orange-900 mr-3"
                  >
                    Edit
                  </button>
                  <div class="inline-block relative group">
                    <button
                      @click="confirmDelete(category)"
                      class="text-red-600 hover:text-red-900"
                      :disabled="category.events_count > 0"
                      :class="{ 'opacity-50 cursor-not-allowed': category.events_count > 0 }"
                    >
                      Delete
                    </button>
                    <!-- Tooltip for disabled delete button -->
                    <div 
                      v-if="category.events_count > 0"
                      class="absolute bottom-full right-0 mb-2 hidden group-hover:block w-64 bg-gray-900 text-white text-xs rounded py-2 px-3 z-10"
                    >
                      Cannot delete: This category has {{ category.events_count }} associated event{{ category.events_count > 1 ? 's' : '' }}. 
                      Please reassign or delete the events first.
                      <div class="absolute top-full right-4 -mt-1 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click="closeModal"
    >
      <div
        class="bg-white rounded-lg shadow-xl max-w-md w-full"
        @click.stop
      >
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ editingCategory ? 'Edit Category' : 'Create New Category' }}
          </h3>
        </div>

        <form @submit.prevent="saveCategory" class="p-6 space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="e.g., Education, Health, Community"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Description
            </label>
            <textarea
              v-model="form.description"
              rows="3"
              placeholder="Brief description of this category"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            ></textarea>
          </div>

          <!-- Color -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Color
            </label>
            <div class="flex items-center gap-2">
              <input
                v-model="form.color"
                type="color"
                class="h-10 w-20 border border-gray-300 rounded cursor-pointer"
              />
              <input
                v-model="form.color"
                type="text"
                placeholder="#3B82F6"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Icon (optional) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Icon Name (optional)
            </label>
            <input
              v-model="form.icon"
              type="text"
              placeholder="e.g., 🎓 or icon-name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
            <p class="text-xs text-gray-500 mt-1">Emoji or icon class name</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors"
            >
              {{ editingCategory ? 'Update' : 'Create' }}
            </button>
          </div>
        </form>
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
            <h3 class="text-lg font-semibold text-gray-900">Delete Category</h3>
            <p class="text-sm text-gray-600 mt-1">
              Are you sure you want to delete "{{ categoryToDelete?.name }}"? This action cannot be undone.
            </p>
          </div>
        </div>

        <div class="flex gap-3 justify-end mt-6">
          <button
            @click="cancelDelete"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button
            @click="deleteCategory"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
