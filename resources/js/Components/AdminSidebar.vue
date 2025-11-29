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
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
        </svg>
        {{ $t('admin.dashboard') }}
      </a>
      <a
        href="/admin/events"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/events') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
        </svg>
        {{ $t('admin.events') }}
      </a>
      <a
        href="/admin/testimonials"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/testimonials') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h7m-7 4h4M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l1.2-4.2A9.863 9.863 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        {{ $t('admin.testimonials') }}
      </a>
      <a
        href="/admin/donations"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/donations') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z" />
        </svg>
        {{ $t('admin.donations') }}
      </a>
      <a
        href="/admin/gallery"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/gallery') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zM8 13l2.5-3 2 2.5L15 9l4 5H5l3-1z" />
        </svg>
        {{ $t('admin.gallery') }}
      </a>
      <a
        href="/admin/categories"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/categories') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7l10 10M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ $t('admin.categories') }}
      </a>
      <a
        href="/admin/media"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/media') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3v4M8 3v4M3 11h18M7 16l3-3 2 2 3-4 4 5" />
        </svg>
        {{ $t('admin.media') }}
      </a>

      <div class="pt-4 mt-4 border-t border-gray-200">
        <a
          href="/admin/users"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
          :class="isActive('/admin/users') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          {{ $t('admin.users') }}
        </a>
      </div>
      <a
        href="/admin/contact-messages"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/contact-messages') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m0 0v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8m18 0L12 13 3 8" />
        </svg>
        {{ $t('admin.contacts') }}
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
          {{ $t('admin.viewWebsite') }}
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
        {{ $t('admin.logout') }}
      </button>
    </div>
  </aside>
</template>
