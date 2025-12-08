<template>
  <AdminLayout>
    <Head :title="metric ? 'Edit Impact Metric' : 'Create Impact Metric'" />

    <div class="py-12">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="mb-6">
              <h2 class="text-2xl font-bold text-gray-900">
                {{ metric ? 'Edit Impact Metric' : 'Create New Impact Metric' }}
              </h2>
            </div>

            <form @submit.prevent="submit">
              <!-- Key -->
              <div class="mb-6">
                <label for="key" class="block text-sm font-medium text-gray-700 mb-2">
                  Key (Unique Identifier)
                </label>
                <input
                  id="key"
                  v-model="form.key"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  :class="{ 'border-red-500': form.errors.key }"
                  required
                  placeholder="e.g., families_helped"
                />
                <p v-if="form.errors.key" class="mt-1 text-sm text-red-600">{{ form.errors.key }}</p>
                <p class="mt-1 text-sm text-gray-500">Use lowercase with underscores (e.g., families_helped)</p>
              </div>

              <!-- Value -->
              <div class="mb-6">
                <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                  Value
                </label>
                <input
                  id="value"
                  v-model="form.value"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  :class="{ 'border-red-500': form.errors.value }"
                  required
                  placeholder="e.g., 200+, $75K+"
                />
                <p v-if="form.errors.value" class="mt-1 text-sm text-red-600">{{ form.errors.value }}</p>
                <p class="mt-1 text-sm text-gray-500">The statistic value (e.g., 500+, $75K+)</p>
              </div>

              <!-- Label EN -->
              <div class="mb-6">
                <label for="label_en" class="block text-sm font-medium text-gray-700 mb-2">
                  Label (English)
                </label>
                <input
                  id="label_en"
                  v-model="form.label_en"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  :class="{ 'border-red-500': form.errors.label_en }"
                  required
                  placeholder="e.g., Families Helped"
                />
                <p v-if="form.errors.label_en" class="mt-1 text-sm text-red-600">{{ form.errors.label_en }}</p>
              </div>

              <!-- Label FR -->
              <div class="mb-6">
                <label for="label_fr" class="block text-sm font-medium text-gray-700 mb-2">
                  Label (French)
                </label>
                <input
                  id="label_fr"
                  v-model="form.label_fr"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  :class="{ 'border-red-500': form.errors.label_fr }"
                  required
                  placeholder="e.g., Familles Aidées"
                />
                <p v-if="form.errors.label_fr" class="mt-1 text-sm text-red-600">{{ form.errors.label_fr }}</p>
              </div>

              <!-- Order -->
              <div class="mb-6">
                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                  Display Order
                </label>
                <input
                  id="order"
                  v-model.number="form.order"
                  type="number"
                  min="0"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  :class="{ 'border-red-500': form.errors.order }"
                  required
                />
                <p v-if="form.errors.order" class="mt-1 text-sm text-red-600">{{ form.errors.order }}</p>
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
              </div>

              <!-- Active Status -->
              <div class="mb-6">
                <label class="flex items-center">
                  <input
                    v-model="form.is_active"
                    type="checkbox"
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                  />
                  <span class="ml-2 text-sm text-gray-700">Active (display on About page)</span>
                </label>
              </div>

              <!-- Actions -->
              <div class="flex items-center justify-end gap-4">
                <Link
                  :href="route('admin.impact-metrics.index')"
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                >
                  Cancel
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-md hover:from-orange-600 hover:to-orange-700 disabled:opacity-50 transition"
                >
                  {{ form.processing ? 'Saving...' : (metric ? 'Update Metric' : 'Create Metric') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  metric: {
    type: Object,
    default: null
  }
});

const form = useForm({
  key: props.metric?.key || '',
  label_en: props.metric?.label_en || '',
  label_fr: props.metric?.label_fr || '',
  value: props.metric?.value || '',
  order: props.metric?.order || 0,
  is_active: props.metric?.is_active ?? true,
});

const submit = () => {
  if (props.metric) {
    form.put(route('admin.impact-metrics.update', props.metric.id));
  } else {
    form.post(route('admin.impact-metrics.store'));
  }
};
</script>
