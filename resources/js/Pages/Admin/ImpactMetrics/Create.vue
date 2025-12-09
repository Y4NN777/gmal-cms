<template>
  <AdminLayout>
    <Head title="Create Impact Metric" />

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <Link href="/admin/impact-metrics" class="hover:text-orange-600">Impact Metrics</Link>
            <span>/</span>
            <span class="text-gray-900">Create New</span>
          </div>
          <h2 class="text-2xl font-bold text-gray-900">Create New Impact Metric</h2>
          <p class="text-gray-600 mt-1">Add a new statistic to display on the About page</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
          <!-- Basic Info Card -->
          <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>

            <!-- Key -->
            <div>
              <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                Key (Unique Identifier) <span class="text-red-500">*</span>
              </label>
              <input
                id="key"
                v-model="form.key"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.key }"
                placeholder="e.g., families_helped"
              />
              <p v-if="errors?.key" class="text-red-500 text-sm mt-1">{{ errors.key }}</p>
              <p class="text-xs text-gray-500 mt-1">Use lowercase with underscores (e.g., families_helped, funds_raised)</p>
            </div>

            <!-- Value -->
            <div>
              <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                Value <span class="text-red-500">*</span>
              </label>
              <input
                id="value"
                v-model="form.value"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.value }"
                placeholder="e.g., 200+, $75K+"
              />
              <p v-if="errors?.value" class="text-red-500 text-sm mt-1">{{ errors.value }}</p>
              <p class="text-xs text-gray-500 mt-1">The statistic value displayed (e.g., 500+, $75K+, 1,000+)</p>
            </div>
          </div>

          <!-- Labels Card -->
          <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Display Labels (Bilingual)</h3>

            <!-- Label EN -->
            <div>
              <label for="label_en" class="block text-sm font-medium text-gray-700 mb-2">
                Label (English) <span class="text-red-500">*</span>
              </label>
              <input
                id="label_en"
                v-model="form.label_en"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.label_en }"
                placeholder="e.g., Families Helped"
              />
              <p v-if="errors?.label_en" class="text-red-500 text-sm mt-1">{{ errors.label_en }}</p>
            </div>

            <!-- Label FR -->
            <div>
              <label for="label_fr" class="block text-sm font-medium text-gray-700 mb-2">
                Label (French) <span class="text-red-500">*</span>
              </label>
              <input
                id="label_fr"
                v-model="form.label_fr"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.label_fr }"
                placeholder="e.g., Familles Aidées"
              />
              <p v-if="errors?.label_fr" class="text-red-500 text-sm mt-1">{{ errors.label_fr }}</p>
            </div>
          </div>

          <!-- Display Settings Card -->
          <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <h3 class="text-lg font-semibold text-gray-900">Display Settings</h3>

            <!-- Order -->
            <div>
              <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                Display Order <span class="text-red-500">*</span>
              </label>
              <input
                id="order"
                v-model.number="form.order"
                type="number"
                min="0"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                :class="{ 'border-red-500': errors?.order }"
              />
              <p v-if="errors?.order" class="text-red-500 text-sm mt-1">{{ errors.order }}</p>
              <p class="text-xs text-gray-500 mt-1">Lower numbers appear first (e.g., 1, 2, 3, 4)</p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
              <input
                id="is_active"
                v-model="form.is_active"
                type="checkbox"
                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
              />
              <label for="is_active" class="ml-2 text-sm text-gray-700">
                Active (display this metric on the About page)
              </label>
            </div>
          </div>

          <!-- Preview Card -->
          <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg shadow-xl p-8">
            <h3 class="text-white text-lg font-semibold mb-6 text-center">Preview</h3>
            <div class="text-center">
              <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2">
                {{ form.value || '---' }}
              </div>
              <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">
                {{ form.label_en || 'Label (English)' }}
              </p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3">
            <Link
              href="/admin/impact-metrics"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
            >
              Cancel
            </Link>
            <button
              type="submit"
              :disabled="submitting"
              class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg hover:from-orange-600 hover:to-orange-700 font-medium disabled:opacity-50"
            >
              {{ submitting ? 'Creating...' : 'Create Metric' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
  errors: Object,
});

const form = ref({
  key: '',
  label_en: '',
  label_fr: '',
  value: '',
  order: 0,
  is_active: true,
});

const submitting = ref(false);

const submitForm = () => {
  submitting.value = true;
  
  router.post('/admin/impact-metrics', form.value, {
    onFinish: () => {
      submitting.value = false;
    }
  });
};
</script>
