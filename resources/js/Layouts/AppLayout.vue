<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <Header />

    <!-- Main Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <Footer />

    <!-- Toast Notifications -->
    <Toast />
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Layout/Header.vue';
import Footer from '@/Components/Layout/Footer.vue';
import Toast from '@/Components/Toast.vue';

defineProps({
  title: {
    type: String,
    default: 'GiveMeALift - Supporting Communities'
  }
});
</script>
