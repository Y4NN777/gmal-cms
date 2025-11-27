<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const currentRoute = computed(() => page.url);

const isActive = (path) => {
  return currentRoute.value.startsWith(path);
};

defineProps({
  unreadContactsCount: {
    type: Number,
    default: 0,
  },
  user: Object,
});

const logout = () => {
  router.post('/logout');
};
</script>

<template>
  <aside class="w-64 bg-white shadow-sm min-h-screen flex flex-col relative">
    <nav class="flex-1 px-4 py-6 space-y-1">
      <a
        href="/admin/dashboard"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/dashboard') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Dashboard
      </a>
      <a
        href="/admin/events"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/events') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Events
      </a>
      <a
        href="/admin/testimonials"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/testimonials') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Testimonials
      </a>
      <a
        href="/admin/gallery"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/gallery') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Gallery
      </a>
      <a
        href="/admin/media"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/media') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Media Library
      </a>
      <a
        href="/admin/contact-messages"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/contact-messages') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Contact Messages
        <span
          v-if="unreadContactsCount > 0"
          class="ml-auto bg-orange-600 text-white text-xs rounded-full px-2 py-1"
        >
          {{ unreadContactsCount }}
        </span>
      </a>

      <div class="pt-4 mt-4 border-t border-gray-200">
        <a
          href="/"
          target="_blank"
          class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
          </svg>
          View Website
        </a>
      </div>
    </nav>

    <!-- User & Logout - Fixed at bottom -->
    <div class="border-t border-gray-200 bg-white p-4">
      <div class="mb-3">
        <p class="text-xs text-gray-500 mb-1">Logged in as</p>
        <p class="text-sm font-medium text-gray-900">{{ user?.name }}</p>
      </div>
      <button
        @click="logout"
        class="w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
      >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </div>
  </aside>
</template>
