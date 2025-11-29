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
  <aside class="w-64 bg-white shadow-sm flex flex-col fixed left-0 top-16 bottom-0">
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
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
        href="/admin/donations"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/donations') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        Donations
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

    <!-- Logout at bottom -->
    <div class="border-t border-gray-200 px-6 pt-10 pb-10 flex-shrink-0">
      <button
        @click="logout"
        class="w-full flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-gray-700 bg-gradient-to-r from-red-50 to-white border-2 border-red-200 rounded-xl hover:from-red-100 hover:to-red-50 hover:border-red-300 hover:shadow-md transition-all duration-200"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </div>
  </aside>
</template>
