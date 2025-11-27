<template>
  <AdminLayout :user="user">
    <div class="max-w-7xl mx-auto">
          <!-- Stats Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Events</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_events }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Pending Testimonials -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Pending Testimonials</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.pending_testimonials }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Total Donations -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Total Donations</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">${{ stats.total_donations?.toLocaleString() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
            </div>

            <!-- Unread Messages -->
            <div class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-gray-600">Unread Messages</p>
                  <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.unread_messages }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Events -->
            <div class="bg-white rounded-lg shadow">
              <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Events</h2>
              </div>
              <div class="p-6">
                <div v-if="recentEvents.length === 0" class="text-center text-gray-500 py-8">
                  No events yet
                </div>
                <div v-else class="space-y-4">
                  <div
                    v-for="event in recentEvents"
                    :key="event.id"
                    class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0"
                  >
                    <div class="flex-1">
                      <h3 class="font-medium text-gray-900">{{ event.title }}</h3>
                      <p class="text-sm text-gray-500 mt-1">{{ event.event_date }}</p>
                    </div>
                    <span
                      class="px-2 py-1 text-xs font-medium rounded"
                      :class="{
                        'bg-green-100 text-green-800': event.status === 'published',
                        'bg-yellow-100 text-yellow-800': event.status === 'draft',
                      }"
                    >
                      {{ event.status }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Testimonials -->
            <div class="bg-white rounded-lg shadow">
              <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Pending Testimonials</h2>
              </div>
              <div class="p-6">
                <div v-if="pendingTestimonials.length === 0" class="text-center text-gray-500 py-8">
                  No pending testimonials
                </div>
                <div v-else class="space-y-4">
                  <div
                    v-for="testimonial in pendingTestimonials"
                    :key="testimonial.id"
                    class="pb-4 border-b border-gray-100 last:border-0"
                  >
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h3 class="font-medium text-gray-900">{{ testimonial.author_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ testimonial.content?.substring(0, 100) }}...</p>
                      </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                      <button class="px-3 py-1 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded">
                        Approve
                      </button>
                      <button class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded">
                        Reject
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  stats: Object,
  recentEvents: Array,
  pendingTestimonials: Array,
  user: Object,
});

onMounted(async () => {
  // Check if user is authenticated
  const token = localStorage.getItem('token');
  const userData = localStorage.getItem('user');
  
  if (!token || !userData) {
    // Not authenticated, redirect to login
    router.visit('/admin/login');
    return;
  }
  
  userRef.value = JSON.parse(userData);
  
  // Verify token is still valid
  try {
    const response = await fetch('/api/v1/auth/me', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
      },
    });
    
    if (!response.ok) {
      // Token invalid, redirect to login
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      router.visit('/admin/login');
    }
  } catch (error) {
    console.error('Auth check failed:', error);
    router.visit('/admin/login');
  }
});
</script>
