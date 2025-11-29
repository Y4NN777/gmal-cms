<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  users: Array,
  currentUserId: Number,
});

const showModal = ref(false);
const editingUser = ref(null);
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const showPassword = ref(false);

const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'viewer',
  is_active: true,
});

const roles = computed(() => [
  { value: 'admin', label: t('admin.userManagement.roles.admin'), description: t('admin.userManagement.roleDescriptions.admin') },
  { value: 'editor', label: t('admin.userManagement.roles.editor'), description: t('admin.userManagement.roleDescriptions.editor') },
  { value: 'viewer', label: t('admin.userManagement.roles.viewer'), description: t('admin.userManagement.roleDescriptions.viewer') },
]);

const getRoleBadgeColor = (role) => {
  const colors = {
    admin: 'bg-red-100 text-red-700',
    editor: 'bg-blue-100 text-blue-700',
    viewer: 'bg-gray-100 text-gray-700',
  };
  return colors[role] || 'bg-gray-100 text-gray-700';
};

const openCreateModal = () => {
  editingUser.value = null;
  form.value = {
    name: '',
    email: '',
    password: '',
    role: 'viewer',
    is_active: true,
  };
  showModal.value = true;
};

const openEditModal = (user) => {
  editingUser.value = user;
  form.value = {
    name: user.name,
    email: user.email,
    password: '',
    role: user.role,
    is_active: user.is_active,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingUser.value = null;
  showPassword.value = false;
};

const saveUser = () => {
  if (editingUser.value) {
    router.put(`/admin/users/${editingUser.value.id}`, form.value, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  } else {
    router.post('/admin/users', form.value, {
      preserveScroll: true,
      onSuccess: () => closeModal(),
    });
  }
};

const confirmDelete = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const deleteUser = () => {
  if (!userToDelete.value) return;
  
  router.delete(`/admin/users/${userToDelete.value.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      userToDelete.value = null;
    },
  });
};

const cancelDelete = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

const toggleUserStatus = (user) => {
  router.put(`/admin/users/${user.id}`, {
    ...user,
    is_active: !user.is_active,
  }, {
    preserveScroll: true,
  });
};

const formatDate = (date) => {
  if (!date) return 'Never';
  return new Date(date).toLocaleString();
};
</script>

<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">{{ $t('admin.userManagement.title') }}</h2>
          <p class="text-gray-600 mt-1">{{ $t('admin.userManagement.description') }}</p>
        </div>
        <button
          @click="openCreateModal"
          class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium flex items-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          {{ $t('admin.userManagement.createUser') }}
        </button>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('admin.userManagement.userName') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('admin.userManagement.userRole') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('admin.userManagement.userStatus') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('admin.userManagement.lastLogin') }}</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $t('admin.actions') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="users.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                  <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                  </svg>
                  <p class="text-lg font-medium">{{ $t('admin.userManagement.noUsers') }}</p>
                </td>
              </tr>
              <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-semibold">
                      {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900 flex items-center gap-2">
                        {{ user.name }}
                        <span v-if="user.id === currentUserId" class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">
                          You
                        </span>
                      </div>
                      <div class="text-sm text-gray-500">{{ user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-3 py-1 text-xs font-semibold rounded-full" :class="getRoleBadgeColor(user.role)">
                    {{ user.role }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button
                    @click="toggleUserStatus(user)"
                    :disabled="user.id === currentUserId"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                    :class="[
                      user.is_active ? 'bg-green-600' : 'bg-gray-200',
                      user.id === currentUserId ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                    ]"
                  >
                    <span
                      class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                      :class="user.is_active ? 'translate-x-6' : 'translate-x-1'"
                    />
                  </button>
                  <span class="ml-2 text-xs text-gray-500">
                    {{ user.is_active ? $t('status.active') : $t('status.inactive') }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(user.last_login_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button
                    @click="openEditModal(user)"
                    class="text-orange-600 hover:text-orange-900 mr-3"
                  >
                    {{ $t('admin.edit') }}
                  </button>
                  <button
                    @click="confirmDelete(user)"
                    :disabled="user.id === currentUserId"
                    class="text-red-600 hover:text-red-900"
                    :class="{ 'opacity-50 cursor-not-allowed': user.id === currentUserId }"
                  >
                    {{ $t('admin.delete') }}
                  </button>
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
            {{ editingUser ? $t('admin.userManagement.editUser') : $t('admin.userManagement.createUser') }}
          </h3>
        </div>

        <form @submit.prevent="saveUser" class="p-6 space-y-4">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ $t('admin.userManagement.userName') }} <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="John Doe"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ $t('admin.userManagement.userEmail') }} <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              placeholder="john@example.com"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ $t('admin.userManagement.userPassword') }} <span v-if="!editingUser" class="text-red-500">*</span>
              <span v-else class="text-xs text-gray-500">({{ $t('admin.userManagement.leaveBlankToKeep') }})</span>
            </label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                :required="!editingUser"
                placeholder="••••••••"
                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ $t('admin.userManagement.minCharacters') }}</p>
          </div>

          <!-- Role -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              {{ $t('admin.userManagement.userRole') }} <span class="text-red-500">*</span>
            </label>
            <div class="space-y-2">
              <label
                v-for="role in roles"
                :key="role.value"
                class="flex items-start p-3 border rounded-lg cursor-pointer transition-colors"
                :class="form.role === role.value ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-gray-300'"
              >
                <input
                  v-model="form.role"
                  type="radio"
                  :value="role.value"
                  class="mt-1 text-orange-600 focus:ring-orange-500"
                />
                <div class="ml-3">
                  <div class="font-medium text-gray-900">{{ role.label }}</div>
                  <div class="text-xs text-gray-500">{{ role.description }}</div>
                </div>
              </label>
            </div>
          </div>

          <!-- Status -->
          <div class="flex items-center">
            <input
              v-model="form.is_active"
              type="checkbox"
              id="is_active"
              class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
            />
            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
              {{ $t('admin.userManagement.activeAccount') }}
            </label>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              {{ $t('admin.cancel') }}
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors"
            >
              {{ editingUser ? $t('admin.update') : $t('admin.create') }}
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
            <h3 class="text-lg font-semibold text-gray-900">{{ $t('admin.userManagement.deleteUser') }}</h3>
            <p class="text-sm text-gray-600 mt-1">
              {{ $t('admin.userManagement.deleteConfirm', { name: userToDelete?.name }) }}
            </p>
          </div>
        </div>

        <div class="flex gap-3 justify-end mt-6">
          <button
            @click="cancelDelete"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
          >
            {{ $t('admin.cancel') }}
          </button>
          <button
            @click="deleteUser"
            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
          >
            {{ $t('admin.delete') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
