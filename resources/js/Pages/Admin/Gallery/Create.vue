<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  user: Object,
  errors: Object,
});

const form = ref({
  title: '',
  description: '',
  is_featured: false,
});

const submitting = ref(false);

const submitForm = async () => {
  submitting.value = true;
  
  router.post('/admin/gallery', form.value, {
    onFinish: () => {
      submitting.value = false;
    }
  });
};

const cancel = () => {
  router.visit('/admin/gallery');
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <a href="/admin/gallery" class="hover:text-orange-600">{{ t('galleryManagement.title') }}</a>
              <span>/</span>
              <span class="text-gray-900">{{ t('galleryManagement.createAlbum') }}</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">{{ t('galleryManagement.createAlbum') }}</h2>
            <p class="text-gray-600 mt-1">{{ t('galleryManagement.createFirstAlbum') }}</p>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-6 space-y-6">
            <!-- Title -->
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('galleryManagement.albumTitle') }} <span class="text-red-500">*</span>
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.title }"
                :placeholder="t('common.title')"
              />
              <p v-if="errors?.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
            </div>

            <!-- Description -->
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('galleryManagement.albumDescription') }}
              </label>
              <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.description }"
                :placeholder="t('common.description')"
              ></textarea>
              <p v-if="errors?.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
            </div>

            <!-- Featured -->
            <div class="flex items-center">
              <input
                id="is_featured"
                v-model="form.is_featured"
                type="checkbox"
                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
              />
              <label for="is_featured" class="ml-2 text-sm text-gray-700">
                {{ t('common.featured') }}
              </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t">
              <button
                type="button"
                @click="cancel"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
                :disabled="submitting"
              >
                {{ t('galleryManagement.cancel') }}
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="submitting"
              >
                {{ submitting ? t('common.creating') : t('galleryManagement.createAlbum') }}
              </button>
            </div>
          </form>
        </div>
  </AdminLayout>
</template>
