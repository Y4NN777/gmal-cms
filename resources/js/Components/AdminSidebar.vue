<script setup>
import { computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { 
  LayoutDashboard, 
  Calendar, 
  MessageSquareQuote, 
  Heart, 
  Images, 
  Tags, 
  FolderOpen, 
  Users, 
  Mail, 
  ExternalLink, 
  LogOut 
} from 'lucide-vue-next';

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
        <LayoutDashboard :size="20" class="mr-2" />
        {{ $t('admin.dashboard') }}
      </a>
      <a
        href="/admin/events"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/events') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <Calendar :size="20" class="mr-2" />
        {{ $t('admin.events') }}
      </a>
      <a
        href="/admin/testimonials"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/testimonials') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <MessageSquareQuote :size="20" class="mr-2" />
        {{ $t('admin.testimonials') }}
      </a>
      <a
        href="/admin/donations"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/donations') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <Heart :size="20" class="mr-2" />
        {{ $t('admin.donations') }}
      </a>
      <a
        href="/admin/gallery"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/gallery') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <Images :size="20" class="mr-2" />
        {{ $t('admin.gallery') }}
      </a>
      <a
        href="/admin/categories"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/categories') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <Tags :size="20" class="mr-2" />
        {{ $t('admin.categories') }}
      </a>
      <a
        href="/admin/media"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/media') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <FolderOpen :size="20" class="mr-2" />
        {{ $t('admin.media') }}
      </a>

      <div class="pt-4 mt-4 border-t border-gray-200">
        <a
          href="/admin/users"
          class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
          :class="isActive('/admin/users') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
        >
          <Users :size="20" class="mr-2" />
          {{ $t('admin.users') }}
        </a>
      </div>
      <a
        href="/admin/contact-messages"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg"
        :class="isActive('/admin/contact-messages') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50'"
      >
        <Mail :size="20" class="mr-2" />
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
          <ExternalLink :size="20" class="mr-2" />
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
        <LogOut :size="16" />
        {{ $t('admin.logout') }}
      </button>
    </div>
  </aside>
</template>
