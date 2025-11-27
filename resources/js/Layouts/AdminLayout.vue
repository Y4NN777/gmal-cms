<script setup>
import AdminSidebar from '@/Components/AdminSidebar.vue';

defineProps({
  user: Object,
});
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200">
      <div class="px-8 h-16 flex items-center">
        <h1 class="text-xl font-bold text-gray-900">GiveMeALift</h1>
        <span class="ml-2 px-2 py-1 text-xs font-medium bg-orange-100 text-orange-700 rounded">Admin</span>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <AdminSidebar :unreadContactsCount="$page.props.unreadContactsCount" :user="user" />

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <slot />
      </main>
    </div>
  </div>
</template>
