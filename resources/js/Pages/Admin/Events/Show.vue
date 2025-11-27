<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  event: Object,
  user: Object,
});

const editEvent = () => {
  router.visit(`/admin/events/${props.event.id}/edit`);
};

const backToList = () => {
  router.visit('/admin/events');
};

const logout = () => {
  router.post('/logout');
};

const getStatusColor = (status) => {
  const colors = {
    draft: 'bg-gray-100 text-gray-700',
    published: 'bg-green-100 text-green-700',
    archived: 'bg-yellow-100 text-yellow-700',
    cancelled: 'bg-red-100 text-red-700',
  };
  return colors[status] || 'bg-gray-100 text-gray-700';
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">GiveMeALift Admin</h1>
          </div>
          
          <div class="flex items-center gap-4">
            <span class="text-sm text-gray-700">{{ user?.name }}</span>
            <button @click="logout" class="text-sm text-gray-600 hover:text-gray-900">
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-sm min-h-screen">
        <nav class="px-4 py-6 space-y-1">
          <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Dashboard
          </a>
          <a href="/admin/events" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-orange-50 text-orange-700">
            Events
          </a>
          <a href="/admin/testimonials" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Testimonials
          </a>
          <a href="/admin/gallery" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
            Gallery
          </a>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="max-w-4xl mx-auto">
          <!-- Header -->
          <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <a href="/admin/events" class="hover:text-orange-600">Events</a>
              <span>/</span>
              <span class="text-gray-900">{{ event.title }}</span>
            </div>
            <div class="flex justify-between items-start">
              <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ event.title }}</h2>
                <div class="flex items-center gap-3 mt-2">
                  <span :class="getStatusColor(event.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                    {{ event.status.toUpperCase() }}
                  </span>
                  <span v-if="event.is_featured" class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-medium">
                    FEATURED
                  </span>
                  <span v-if="event.category" class="text-sm text-gray-600">
                    {{ event.category.name }}
                  </span>
                </div>
              </div>
              <div class="flex gap-2">
                <button
                  @click="backToList"
                  class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium"
                >
                  Back to List
                </button>
                <button
                  @click="editEvent"
                  class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium"
                >
                  Edit Event
                </button>
              </div>
            </div>
          </div>

          <!-- Featured Image -->
          <div v-if="event.featured_image_url" class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <img :src="event.featured_image_url" :alt="event.title" class="w-full h-96 object-cover" />
          </div>

          <!-- Event Details -->
          <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
              <dl class="grid grid-cols-2 gap-4">
                <div>
                  <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(event.event_date) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(event.start_date) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">End Date</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(event.end_date) }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Location</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ event.location || 'N/A' }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Created By</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ event.creator?.name || 'Unknown' }}</dd>
                </div>
                <div>
                  <dt class="text-sm font-medium text-gray-500">Last Updated By</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ event.updater?.name || 'Unknown' }}</dd>
                </div>
              </dl>
            </div>

            <!-- Short Description -->
            <div v-if="event.short_description" class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Short Description</h3>
              <p class="text-gray-700">{{ event.short_description }}</p>
            </div>

            <!-- Excerpt -->
            <div v-if="event.excerpt" class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Excerpt</h3>
              <p class="text-gray-700">{{ event.excerpt }}</p>
            </div>

            <!-- Full Description -->
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Full Description</h3>
              <div class="prose max-w-none text-gray-700 whitespace-pre-wrap">{{ event.description }}</div>
            </div>

            <!-- SEO Info -->
            <div v-if="event.meta_title || event.meta_description" class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Information</h3>
              <dl class="space-y-4">
                <div v-if="event.meta_title">
                  <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ event.meta_title }}</dd>
                </div>
                <div v-if="event.meta_description">
                  <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ event.meta_description }}</dd>
                </div>
              </dl>
            </div>

            <!-- Public URL -->
            <div class="bg-white rounded-lg shadow p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Public URL</h3>
              <a :href="`/events/${event.slug}`" target="_blank" class="text-orange-600 hover:text-orange-700 text-sm">
                {{ window.location.origin }}/events/{{ event.slug }}
                <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
