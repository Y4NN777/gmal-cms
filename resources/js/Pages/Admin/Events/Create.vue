<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImagePicker from '@/Components/ImagePicker.vue';

const props = defineProps({
  categories: Array,
  errors: Object,
  user: Object,
});

const form = ref({
  title: '',
  description: '',
  excerpt: '',
  short_description: '',
  category_id: '',
  event_date: '',
  start_date: '',
  end_date: '',
  location: '',
  status: 'draft',
  is_featured: false,
  featured_image_id: null,
  meta_title: '',
  meta_description: '',
});

const submitting = ref(false);
const showImagePicker = ref(false);
const selectedImage = ref(null);

const submitForm = () => {
  submitting.value = true;
  
  router.post('/admin/events', form.value, {
    onSuccess: () => {
      // Redirect handled by controller
    },
    onError: () => {
      submitting.value = false;
    },
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
              <a href="/admin/events" class="hover:text-orange-600">Events</a>
              <span>/</span>
              <span class="text-gray-900">Create Event</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Create New Event</h2>
            <p class="text-gray-600 mt-1">Fill in the details to create a new event</p>
          </div>

          <!-- Form -->
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>

              <!-- Title -->
              <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                  Event Title <span class="text-red-500">*</span>
                </label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.title }"
                  placeholder="e.g., Summer 2025 Fundraiser"
                />
                <p v-if="errors?.title" class="text-red-500 text-sm mt-1">{{ errors.title }}</p>
              </div>

              <!-- Short Description -->
              <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                  Short Description
                </label>
                <input
                  id="short_description"
                  v-model="form.short_description"
                  type="text"
                  maxlength="500"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.short_description }"
                  placeholder="Brief one-line description (max 500 chars)"
                />
                <p class="text-xs text-gray-500 mt-1">{{ form.short_description?.length || 0 }}/500</p>
                <p v-if="errors?.short_description" class="text-red-500 text-sm mt-1">{{ errors.short_description }}</p>
              </div>

              <!-- Excerpt -->
              <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                  Excerpt
                </label>
                <textarea
                  id="excerpt"
                  v-model="form.excerpt"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.excerpt }"
                  placeholder="Brief summary for cards and previews..."
                ></textarea>
                <p v-if="errors?.excerpt" class="text-red-500 text-sm mt-1">{{ errors.excerpt }}</p>
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Full Description <span class="text-red-500">*</span>
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="8"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': errors?.description }"
                  placeholder="Full event description..."
                ></textarea>
                <p v-if="errors?.description" class="text-red-500 text-sm mt-1">{{ errors.description }}</p>
              </div>

              <!-- Category & Status Row -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="category_id"
                    v-model="form.category_id"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    :class="{ 'border-red-500': errors?.category_id }"
                  >
                    <option value="">Select Category</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                      {{ cat.name }}
                    </option>
                  </select>
                  <p v-if="errors?.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id }}</p>
                </div>

                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="status"
                    v-model="form.status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Dates & Location Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">Dates & Location</h3>

              <div class="grid grid-cols-3 gap-4">
                <div>
                  <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Event Date <span class="text-red-500">*</span>
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
                    Start Date <span class="text-red-500">*</span>
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
                    End Date
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
                  placeholder="e.g., Community Center, Main Street"
                />
              </div>
            </div>

            <!-- Featured Image Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
              <h3 class="text-lg font-semibold text-gray-900">Featured Image</h3>

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
                {{ selectedImage ? 'Change Image' : 'Select Image' }}
              </button>
            </div>

            <!-- SEO Card -->
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
              <h3 class="text-lg font-semibold text-gray-900">SEO Settings</h3>

              <div>
                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                  Meta Title
                </label>
                <input
                  id="meta_title"
                  v-model="form.meta_title"
                  type="text"
                  maxlength="60"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  placeholder="SEO title (max 60 chars)"
                />
                <p class="text-xs text-gray-500 mt-1">{{ form.meta_title?.length || 0 }}/60</p>
              </div>

              <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                  Meta Description
                </label>
                <textarea
                  id="meta_description"
                  v-model="form.meta_description"
                  rows="3"
                  maxlength="160"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  placeholder="SEO description (max 160 chars)"
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
                  Feature this event on the homepage
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
                Cancel
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium disabled:opacity-50"
                :disabled="submitting"
              >
                {{ submitting ? 'Creating...' : 'Create Event' }}
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
  </AdminLayout>
</template>
