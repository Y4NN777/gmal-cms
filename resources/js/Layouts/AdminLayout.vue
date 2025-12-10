<script setup>
import { ref } from 'vue';
import AdminSidebar from '@/Components/AdminSidebar.vue';
import Toast from '@/Components/Toast.vue';
import HelpCenter from '@/Components/HelpCenter.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { Menu, X } from 'lucide-vue-next';
import logoImg from '@/assets/favicon.png';

defineProps({
  user: Object,
});

const mobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

const closeMobileMenu = () => {
  mobileMenuOpen.value = false;
};
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 flex-shrink-0 fixed top-0 left-0 right-0 z-20">
      <div class="px-4 sm:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
            aria-label="Toggle menu"
          >
            <Menu v-if="!mobileMenuOpen" :size="24" />
            <X v-else :size="24" />
          </button>
          
          <div class="h-8 w-8 rounded-lg overflow-hidden flex-shrink-0">
            <img :src="logoImg" alt="GiveMeALift" class="h-full w-full object-cover">
          </div>
          <h1 class="text-lg sm:text-xl font-bold text-gray-900">GiveMeALift</h1>
          <span class="hidden sm:inline px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded">Admin</span>
        </div>
        <LanguageSwitcher />
      </div>
    </nav>

    <div class="flex pt-16">
      <!-- Sidebar -->
      <AdminSidebar 
        :unreadContactsCount="$page.props.unreadContactsCount" 
        :user="user" 
        :mobileMenuOpen="mobileMenuOpen"
        @close="closeMobileMenu"
      />

      <!-- Main Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8 lg:ml-64 w-full">
        <slot />
      </main>
    </div>

    <!-- Toast Notifications -->
    <Toast />

    <!-- Help Center -->
    <HelpCenter />
  </div>
</template>
