<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImagePicker from '@/Components/ImagePicker.vue';
import { useCategory } from '@/composables/useCategory';

const { translateCategory } = useCategory();

const props = defineProps({
  event: Object,
  categories: Array,
  errors: Object,
  user: Object,
});

const form = ref({
  title: props.event.title || '',
  description: props.event.description || '',
  excerpt: props.event.excerpt || '',
  short_description: props.event.short_description || '',
  category_id: props.event.category_id || '',
  event_date: props.event.event_date || '',
  start_date: props.event.start_date || '',
  end_date: props.event.end_date || '',
  location: props.event.location || '',
  status: props.event.status || 'draft',
  is_featured: props.event.is_featured || false,
  featured_image_id: props.event.featured_image_id || null,
  meta_title: props.event.meta_title || '',
  meta_description: props.event.meta_description || '',
});

const submitting = ref(false);
const showImagePicker = ref(false);
const showDeleteModal = ref(false);
const selectedImage = ref(props.event.featured_image ? {
  id: props.event.featured_image.id,
  url: props.event.featured_image.url,
  filename: props.event.featured_image.filename,
} : null);

const submitForm = () => {
  submitting.value = true;
  
  router.put(`/admin/events/${props.event.id}`, form.value, {
    onFinish: () => {
      submitting.value = false;
    }
  });
};

const openImagePicker = () => {
  showImagePicker.value = true;
};

const handleImageSelect = (image) => {
  form.value.featured_image_id = image.id;
  selectedImage.value = image;
};

const removeImage = () => {
  form.value.featured_image_id = null;
  selectedImage.value = null;
};

const deleteEvent = () => {
  showDeleteModal.value = true;
};

const confirmDelete = () => {
  router.delete(`/admin/events/${props.event.id}`, {
    onSuccess: () => {
      router.visit('/admin/events');
    }
  });
};

const cancel = () => {
  router.visit('/admin/events');
};
</script>

<template>
  <AdminLayout>
        <div class="max-w-4xl mx-auto">
          <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <a href="/admin/events" class="hover:text-orange-600">{{ $t('admin.events') }}</a>
              <span>/</span>
              <span class="text-gray-900">{{ $t('admin.eventForm.editTitle') }}</span>
            </div>
            <div class="flex justify-between items-start">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $t('admin.eventForm.editTitle') }}</h2>
                <p class="text-gray-600 mt-1">{{ $t('admin.eventForm.editDescription') }}</p>
              </div>
              <button
                @click="deleteEvent"
                class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium"
              >
                {{ $t('admin.delete') }}
              </button>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">{{ $t('admin.eventForm.basicInfo') }}</h3>

              <!-- Title -->
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.titleLabel') }} <span class="text-red-500">*</span>
                </label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.title }"
                  :placeholder="$t('admin.eventForm.titlePlaceholder')"
                />
                <p v-if="errors?.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
              </div>

              <!-- {{ $t('admin.eventForm.shortDescriptionLabel') }} -->
              <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.shortDescriptionLabel') }}
                </label>
                <input
                  id="short_description"
                  v-model="form.short_description"
                  type="text"
                  maxlength="500"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.short_description }"
                  :placeholder="$t('admin.eventForm.shortDescriptionPlaceholder')"
                />
                <p class="text-xs text-gray-500 mt-1">{{ form.short_description?.length || 0 }}/500</p>
                <p v-if="errors?.short_description" class="text-red-500 text-sm mt-1">{{ errors.short_description }}</p>
              </div>

              <!-- {{ $t('admin.eventForm.excerptLabel') }} -->
              <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.excerptLabel') }}
                </label>
                <textarea
                  id="excerpt"
                  v-model="form.excerpt"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.excerpt }"
                  :placeholder="$t('admin.eventForm.excerptPlaceholder')"
                ></textarea>
                <p v-if="errors?.excerpt" class="text-red-500 text-sm mt-1">{{ errors.excerpt }}</p>
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.descriptionLabel') }} <span class="text-red-500">*</span>
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="8"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.description }"
                  :placeholder="$t('admin.eventForm.descriptionPlaceholder')"
                ></textarea>
                <p v-if="errors?.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
              </div>

              <!-- {{ $t('admin.eventForm.categoryLabel') }} & Status Row -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $t('admin.eventForm.categoryLabel') }} <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="category_id"
                    v-model="form.category_id"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors?.category_id }"
                  >
                    <option value="">{{ $t('admin.eventForm.categoryPlaceholder') }}</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                      {{ translateCategory(cat) }}
                    </option>
                  </select>
                  <p v-if="errors?.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id }}</p>
                </div>

                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $t('admin.eventForm.statusLabel') }} <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  >
                    <option value="draft">{{ $t('status.draft') }}</option>
                    <option value="published">{{ $t('status.published') }}</option>
                    <option value="archived">{{ $t('status.archived') }}</option>
                    <option value="cancelled">{{ $t('status.cancelled') }}</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- {{ $t('admin.eventForm.dateTimeLocation') }} Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">{{ $t('admin.eventForm.dateTimeLocation') }}</h3>

              <div class="grid grid-cols-3 gap-4">
                <div>
                  <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $t('admin.eventForm.eventDateLabel') }} <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="event_date"
                    v-model="form.event_date"
                    type="date"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors?.event_date }"
                  />
                  <p v-if="errors?.event_date" class="text-red-500 text-sm mt-1">{{ errors.event_date }}</p>
                </div>

                <div>
                  <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $t('admin.eventForm.startDateLabel') }} <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="start_date"
                    v-model="form.start_date"
                    type="date"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors?.start_date }"
                  />
                  <p v-if="errors?.start_date" class="text-red-500 text-sm mt-1">{{ errors.start_date }}</p>
                </div>

                <div>
                  <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $t('admin.eventForm.endDateLabel') }}
                  </label>
                  <input
                    id="end_date"
                    v-model="form.end_date"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors?.end_date }"
                  />
                  <p v-if="errors?.end_date" class="text-red-500 text-sm mt-1">{{ errors.end_date }}</p>
                </div>
              </div>

              <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                  Location
                </label>
                <input
                  id="location"
                  v-model="form.location"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :placeholder="$t('admin.eventForm.locationPlaceholder')"
                />
              </div>
            </div>

            <!-- {{ $t('admin.eventForm.featuredImage') }} Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
              <h3 class="text-lg font-semibold text-gray-900">{{ $t('admin.eventForm.featuredImage') }}</h3>

              <div v-if="selectedImage" class="relative inline-block">
                <img :src="selectedImage.url" :alt="selectedImage.filename" class="w-48 h-48 object-cover rounded-lg" />
                <button
                  type="button"
                  @click="removeImage"
                  class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <button
                type="button"
                @click="openImagePicker"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium"
              >
                {{ selectedImage ? $t('admin.eventForm.changeImage') : $t('admin.eventForm.selectImage') }}
              </button>
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">{{ $t('admin.eventForm.seoMeta') }}</h3>

              <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.metaTitleLabel') }}
                </label>
                <input
                  id="meta_title"
                  v-model="form.meta_title"
                  type="text"
                  maxlength="60"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :placeholder="$t('admin.eventForm.metaTitlePlaceholder')"
                />
                <p class="text-xs text-gray-500 mt-1">{{ form.meta_title?.length || 0 }}/60</p>
              </div>

              <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('admin.eventForm.metaDescriptionLabel') }}
                </label>
                <textarea
                  id="meta_description"
                  v-model="form.meta_description"
                  rows="3"
                  maxlength="160"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :placeholder="$t('admin.eventForm.metaDescriptionPlaceholder')"
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">{{ form.meta_description?.length || 0 }}/160</p>
              </div>
            </div>

            <!-- Options Card -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center">
                <input
                  id="is_featured"
                  v-model="form.is_featured"
                  type="checkbox"
                  class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                />
                <label for="is_featured" class="ml-2 text-sm text-gray-700">
                  {{ $t('admin.eventForm.featuredDescription') }}
                </label>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
              <button
                type="button"
                @click="cancel"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
                :disabled="submitting"
              >
                {{ $t('admin.cancel') }}
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium disabled:opacity-50"
                :disabled="submitting"
              >
                {{ submitting ? $t('admin.eventForm.updating') : $t('admin.eventForm.update') }}
              </button>
            </div>
          </form>
        </div>

    <!-- Image Picker Modal -->
    <ImagePicker
      v-model="form.featured_image_id"
      :show="showImagePicker"
      @update:show="showImagePicker = $event"
      @select="handleImageSelect"
    />

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-white/30 backdrop-blur-sm z-50 flex items-center justify-center p-4" @click="showDeleteModal = false">
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6" @click.stop>
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">{{ $t('admin.eventManagement.deleteEventTitle') }}</h3>
        <p class="text-gray-600 text-center mb-6">
          {{ $t('admin.eventManagement.deleteEventConfirm') }}
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
          >
            {{ $t('admin.cancel') }}
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium"
          >
            {{ $t('admin.delete') }}
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
