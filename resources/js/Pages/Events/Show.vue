<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  event: {
    type: Object,
    required: true,
  },
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatTime = (time) => {
  if (!time) return null
  return new Date(`2000-01-01T${time}`).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}
</script>

<template>
  <AppLayout>
    <Head :title="event.title" />

    <!-- Hero Section with Featured Image -->
    <section class="relative h-96 bg-gradient-to-r from-red-600 to-red-700">
      <div
        v-if="event.featured_image"
        class="absolute inset-0"
      >
        <img
          :src="event.featured_image"
          :alt="event.title"
          class="w-full h-full object-cover opacity-40"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-red-900/60 to-red-800/60" />
      </div>
      
      <div class="container mx-auto px-4 h-full flex items-center relative z-10">
        <div class="max-w-4xl">
          <div v-if="event.category" class="mb-4">
            <span class="inline-block bg-orange-500 text-white text-sm font-semibold px-4 py-2 rounded-full">
              {{ event.category.name }}
            </span>
            <span v-if="event.is_featured" class="ml-2 inline-block bg-yellow-500 text-gray-900 text-sm font-semibold px-4 py-2 rounded-full">
              Featured Event
            </span>
          </div>
          <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            {{ event.title }}
          </h1>
          <div class="flex flex-wrap gap-6 text-white text-lg">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span>{{ formatDate(event.event_date) }}</span>
            </div>
            <div v-if="event.event_time" class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ formatTime(event.event_time) }}</span>
            </div>
            <div v-if="event.location" class="flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ event.location }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Back Button -->
    <section class="bg-gray-50 py-4 border-b border-gray-200">
      <div class="container mx-auto px-4">
        <Link
          href="/events"
          class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 font-semibold transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Events
        </Link>
      </div>
    </section>

    <!-- Event Content -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <!-- Event Description -->
          <div class="prose prose-lg max-w-none mb-12">
            <div class="bg-gray-50 rounded-lg p-8">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
              <div class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                {{ event.description }}
              </div>
            </div>
          </div>

          <!-- Donation Goal (if applicable) -->
          <div v-if="event.donation_goal" class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg p-8 mb-12">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Support This Event</h3>
                <p class="text-gray-700 mb-4">
                  Help us reach our donation goal of <span class="font-bold text-red-600">${{ event.donation_goal.toLocaleString() }}</span>
                </p>
                <a
                  href="/contact"
                  class="inline-block bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold px-8 py-3 rounded-lg hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl"
                >
                  Donate Now
                </a>
              </div>
            </div>
          </div>

          <!-- Event Details Card -->
          <div class="bg-gray-50 rounded-lg p-8 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Event Details</h3>
            <div class="space-y-4">
              <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900">Date</div>
                  <div class="text-gray-700">{{ formatDate(event.event_date) }}</div>
                </div>
              </div>

              <div v-if="event.event_time" class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900">Time</div>
                  <div class="text-gray-700">{{ formatTime(event.event_time) }}</div>
                </div>
              </div>

              <div v-if="event.location" class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900">Location</div>
                  <div class="text-gray-700">{{ event.location }}</div>
                </div>
              </div>

              <div v-if="event.category" class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900">Category</div>
                  <div class="text-gray-700">{{ event.category.name }}</div>
                </div>
              </div>

              <div v-if="event.creator" class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900">Organized By</div>
                  <div class="text-gray-700">{{ event.creator.name }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Call to Action -->
          <div class="mt-12 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Get Involved</h3>
            <p class="text-gray-700 mb-6 max-w-2xl mx-auto">
              Join us in making a difference. Whether through donations, volunteering, or spreading the word, 
              your support helps us create lasting change in our community.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
              <a
                href="/contact"
                class="inline-block bg-gradient-to-r from-red-600 to-orange-600 text-white font-semibold px-8 py-3 rounded-lg hover:from-red-700 hover:to-orange-700 transition-all shadow-lg hover:shadow-xl"
              >
                Contact Us
              </a>
              <Link
                href="/events"
                class="inline-block bg-white text-red-600 font-semibold px-8 py-3 rounded-lg border-2 border-red-600 hover:bg-red-50 transition-all"
              >
                View More Events
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
