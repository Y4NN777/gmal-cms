<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  user: Object,
  stats: Object,
  recentEvents: Array,
  pendingTestimonials: Array,
  recentDonations: Array,
  visitorStats: Object,
  donationStats: Object,
  unreadMessages: Array,
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount || 0);
};

const formatDate = (date) => {
  if (!date) return 'N/A';
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

const getStatusColor = (status) => {
  const colors = {
    published: 'bg-green-100 text-green-800',
    draft: 'bg-gray-100 text-gray-800',
    archived: 'bg-red-100 text-red-800',
    completed: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    failed: 'bg-red-100 text-red-800',
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const getPriorityColor = (priority) => {
  const colors = {
    urgent: 'bg-red-100 text-red-800',
    high: 'bg-orange-100 text-orange-800',
    normal: 'bg-blue-100 text-blue-800',
    low: 'bg-gray-100 text-gray-800',
  };
  return colors[priority] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
  <AdminLayout :user="user">
    <div class="space-y-6">
      <!-- Welcome Header -->
      <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ user?.name }}! 👋</h1>
        <p class="text-orange-100">Here's what's happening with your platform today.</p>
      </div>

      <!-- Main Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Events -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Events</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_events }}</p>
              <p class="text-xs text-gray-500 mt-1">
                {{ stats.published_events }} published • {{ stats.upcoming_events }} upcoming
              </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Testimonials -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Testimonials</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.approved_testimonials }}</p>
              <p class="text-xs text-gray-500 mt-1">
                <span class="text-yellow-600 font-semibold">{{ stats.pending_testimonials }} pending review</span>
              </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Donations -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Donations</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ formatCurrency(stats.total_donation_amount) }}</p>
              <p class="text-xs text-gray-500 mt-1">{{ stats.total_donations }} donations</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Contact Messages</p>
              <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.unread_messages }}</p>
              <p class="text-xs text-gray-500 mt-1">
                <span class="text-orange-600 font-semibold">Unread messages</span>
              </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Visitor & Donation Stats -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Stats -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Visitor Analytics</h2>
          <div class="grid grid-cols-3 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
              <p class="text-2xl font-bold text-gray-900">{{ visitorStats.today }}</p>
              <p class="text-xs text-gray-600 mt-1">Today</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
              <p class="text-2xl font-bold text-blue-900">{{ visitorStats.this_week }}</p>
              <p class="text-xs text-blue-600 mt-1">This Week</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
              <p class="text-2xl font-bold text-green-900">{{ visitorStats.this_month }}</p>
              <p class="text-xs text-green-600 mt-1">This Month</p>
            </div>
          </div>
        </div>

        <!-- Donation Stats -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Donations This Month</h2>
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
              <p class="text-2xl font-bold text-green-900">{{ donationStats.this_month_count }}</p>
              <p class="text-xs text-green-600 mt-1">Total Donations</p>
            </div>
            <div class="text-center p-4 bg-emerald-50 rounded-lg">
              <p class="text-2xl font-bold text-emerald-900">{{ formatCurrency(donationStats.this_month_amount) }}</p>
              <p class="text-xs text-emerald-600 mt-1">Total Amount</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Events -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Recent Events</h2>
            <a href="/admin/events" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View all →</a>
          </div>
          <div class="p-6">
            <div v-if="recentEvents.length === 0" class="text-center text-gray-500 py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <p>No events yet</p>
            </div>
            <div v-else class="space-y-3">
              <a
                v-for="event in recentEvents"
                :key="event.id"
                :href="`/admin/events/${event.id}`"
                class="block p-3 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="font-medium text-gray-900">{{ event.title }}</h3>
                    <div class="flex items-center gap-2 mt-1">
                      <span class="text-xs text-gray-500">{{ formatDate(event.event_date) }}</span>
                      <span v-if="event.category" class="text-xs text-gray-400">•</span>
                      <span v-if="event.category" class="text-xs text-gray-500">{{ event.category }}</span>
                    </div>
                  </div>
                  <span class="px-2 py-1 text-xs font-medium rounded ml-2" :class="getStatusColor(event.status)">
                    {{ event.status }}
                  </span>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- Pending Testimonials -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Pending Testimonials</h2>
            <a href="/admin/testimonials" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View all →</a>
          </div>
          <div class="p-6">
            <div v-if="pendingTestimonials.length === 0" class="text-center text-gray-500 py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
              <p>No pending testimonials</p>
            </div>
            <div v-else class="space-y-3">
              <div
                v-for="testimonial in pendingTestimonials"
                :key="testimonial.id"
                class="p-3 rounded-lg border border-gray-200"
              >
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h3 class="font-medium text-gray-900">{{ testimonial.name }}</h3>
                    <div class="flex items-center gap-1 mt-1">
                      <svg v-for="i in testimonial.rating" :key="i" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </div>
                  </div>
                  <span class="text-xs text-gray-500">{{ formatDate(testimonial.created_at) }}</span>
                </div>
                <p class="text-sm text-gray-600 line-clamp-2">{{ testimonial.content }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Donations & Unread Messages -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Donations -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Donations</h2>
          </div>
          <div class="p-6">
            <div v-if="recentDonations.length === 0" class="text-center text-gray-500 py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p>No donations yet</p>
            </div>
            <div v-else class="space-y-2">
              <div
                v-for="donation in recentDonations"
                :key="donation.id"
                class="flex items-center justify-between p-2 rounded hover:bg-gray-50"
              >
                <div class="flex-1">
                  <p class="font-medium text-gray-900 text-sm">{{ donation.donor_name }}</p>
                  <p class="text-xs text-gray-500">{{ donation.campaign || 'General' }}</p>
                </div>
                <div class="text-right">
                  <p class="font-semibold text-green-600">{{ formatCurrency(donation.amount) }}</p>
                  <span class="text-xs px-2 py-0.5 rounded" :class="getStatusColor(donation.status)">
                    {{ donation.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Unread Messages -->
        <div class="bg-white rounded-lg shadow">
          <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900">Unread Messages</h2>
            <a href="/admin/contact-messages" class="text-sm text-orange-600 hover:text-orange-700 font-medium">View all →</a>
          </div>
          <div class="p-6">
            <div v-if="unreadMessages.length === 0" class="text-center text-gray-500 py-8">
              <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <p>No unread messages</p>
            </div>
            <div v-else class="space-y-2">
              <a
                v-for="message in unreadMessages"
                :key="message.id"
                href="/admin/contact-messages"
                class="block p-3 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="font-medium text-gray-900 text-sm">{{ message.name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ message.subject }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ formatDate(message.created_at) }}</p>
                  </div>
                  <span class="text-xs px-2 py-1 rounded ml-2" :class="getPriorityColor(message.priority)">
                    {{ message.priority }}
                  </span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
