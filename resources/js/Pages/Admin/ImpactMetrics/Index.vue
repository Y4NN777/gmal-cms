<template>
  <AdminLayout>
    <Head title="Impact Metrics" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold text-gray-900">Impact Metrics</h2>
            <p class="mt-2 text-sm text-gray-600">Manage the impact statistics displayed on the About page</p>
          </div>
          <Link
            :href="route('admin.impact-metrics.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New Metric
          </Link>
        </div>

        <!-- Metrics Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div v-if="metrics.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">No metrics</h3>
              <p class="mt-1 text-sm text-gray-500">Get started by creating a new impact metric.</p>
            </div>

            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label (EN)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label (FR)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="metric in metrics" :key="metric.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ metric.order }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-2xl font-bold text-blue-600">{{ metric.value }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ metric.label_en }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ metric.label_fr }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="metric.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      >
                        {{ metric.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link
                        :href="route('admin.impact-metrics.edit', metric.id)"
                        class="text-blue-600 hover:text-blue-900 mr-4"
                      >
                        Edit
                      </Link>
                      <button
                        @click="deleteMetric(metric.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div v-if="metrics.length > 0" class="mt-8 bg-gray-900 rounded-lg p-8">
          <h3 class="text-white text-xl font-bold mb-6 text-center">Preview (as shown on About page)</h3>
          <div class="grid md:grid-cols-4 gap-12 text-center">
            <div v-for="metric in activeMetrics" :key="metric.id" class="group">
              <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2 group-hover:text-orange-500 transition-colors duration-300">
                {{ metric.value }}
              </div>
              <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">
                {{ metric.label_en }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  metrics: {
    type: Array,
    required: true
  }
});

const activeMetrics = computed(() => {
  return props.metrics.filter(m => m.is_active);
});

const deleteMetric = (id) => {
  if (confirm('Are you sure you want to delete this metric?')) {
    router.delete(route('admin.impact-metrics.destroy', id));
  }
};
</script>
