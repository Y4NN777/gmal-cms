<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
      <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Events</h1>
        <p class="text-xl text-orange-100">Join us in making a difference in our community</p>
      </div>
    </section>

    <!-- Events Grid -->
    <section class="py-16">
      <div class="container mx-auto px-4">
        <!-- Filters -->
        <div class="mb-8 flex flex-wrap gap-4">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="selectedCategory = category.id === selectedCategory ? null : category.id"
            class="px-4 py-2 rounded-lg font-medium transition-colors"
            :class="selectedCategory === category.id 
              ? 'bg-orange-600 text-white' 
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
          >
            {{ category.name }}
          </button>
          <button
            v-if="selectedCategory"
            @click="selectedCategory = null"
            class="px-4 py-2 rounded-lg font-medium bg-gray-100 text-gray-700 hover:bg-gray-200"
          >
            Clear Filter
          </button>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
          <p class="mt-4 text-gray-600">Loading events...</p>
        </div>

        <!-- Events Grid -->
        <div v-else-if="filteredEvents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="event in filteredEvents"
            :key="event.id"
            class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow cursor-pointer"
            @click="goToEvent(event.slug)"
          >
            <div class="h-48 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white">
              <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            
            <div class="p-6">
              <div class="flex items-center gap-2 mb-3">
                <span
                  class="px-2 py-1 text-xs font-medium rounded"
                  :style="{ backgroundColor: event.category?.color + '20', color: event.category?.color }"
                >
                  {{ event.category?.name }}
                </span>
                <span v-if="event.is_featured" class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">
                  Featured
                </span>
              </div>
              
              <h3 class="text-xl font-bold text-gray-900 mb-2">{{ event.title }}</h3>
              <p class="text-gray-600 text-sm mb-4">{{ event.excerpt }}</p>
              
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(event.event_date) }}
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <p class="text-gray-600">No events found</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const events = ref([]);
const categories = ref([]);
const selectedCategory = ref(null);
const loading = ref(true);

const filteredEvents = computed(() => {
  if (!selectedCategory.value) return events.value;
  return events.value.filter(event => event.category_id === selectedCategory.value);
});

onMounted(async () => {
  await Promise.all([loadEvents(), loadCategories()]);
  loading.value = false;
});

const loadEvents = async () => {
  try {
    const response = await fetch('/api/v1/events?per_page=50');
    const data = await response.json();
    if (data.success) {
      events.value = data.data;
    }
  } catch (error) {
    console.error('Failed to load events:', error);
  }
};

const loadCategories = async () => {
  try {
    const response = await fetch('/api/v1/events/categories');
    const data = await response.json();
    if (data.success) {
      categories.value = data.data;
    }
  } catch (error) {
    console.error('Failed to load categories:', error);
  }
};

const formatDate = (date) => {
  if (!date) return 'Date TBD';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const goToEvent = (slug) => {
  router.visit(`/events/${slug}`);
};
</script>
