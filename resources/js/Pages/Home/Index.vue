<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="hero-section relative h-screen flex items-center justify-center text-white">
      <div class="absolute inset-0 bg-primary-dark opacity-60"></div>
      <div class="container mx-auto px-4 z-10 text-center">
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-display font-bold mb-6 animate-fade-in">
          Supporting Communities Through Compassion
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
          Join us in making a difference. Every contribution helps build a better tomorrow.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Button variant="primary" size="lg" href="/donate">
            Donate Now
          </Button>
          <Button variant="outline" size="lg" href="/events">
            View Events
          </Button>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
          <div class="stat-card">
            <div class="text-4xl font-bold text-primary-orange mb-2">{{ stats.totalDonations }}+</div>
            <div class="text-gray-600">Donations Received</div>
          </div>
          <div class="stat-card">
            <div class="text-4xl font-bold text-primary-orange mb-2">{{ stats.eventsHosted }}+</div>
            <div class="text-gray-600">Events Hosted</div>
          </div>
          <div class="stat-card">
            <div class="text-4xl font-bold text-primary-orange mb-2">{{ stats.volunteersEngaged }}+</div>
            <div class="text-gray-600">Volunteers Engaged</div>
          </div>
          <div class="stat-card">
            <div class="text-4xl font-bold text-primary-orange mb-2">{{ stats.communitiesReached }}+</div>
            <div class="text-gray-600">Communities Reached</div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Preview Section -->
    <section class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-4xl font-display font-bold text-primary-dark mb-6">
              Who We Are
            </h2>
            <p class="text-lg text-gray-600 mb-6 leading-relaxed">
              GiveMeALift is a nonprofit organization dedicated to supporting communities through 
              compassionate action and sustainable change. We believe in the power of collective 
              effort to transform lives and create lasting impact.
            </p>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
              Our mission is to empower individuals and communities by providing resources, 
              organizing impactful events, and fostering a spirit of giving that transcends boundaries.
            </p>
            <Button variant="secondary" href="/about">
              Learn More About Us
            </Button>
          </div>
          <div class="relative h-96 lg:h-full bg-gray-200 rounded-lg shadow-xl flex items-center justify-center">
            <div class="text-gray-400 text-center">
              <p class="text-sm">About Image Placeholder</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Events Section -->
    <section class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-display font-bold text-primary-dark mb-4">
            Upcoming Events
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Join us in our upcoming events and be part of the change you want to see in the world.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
          <Card 
            v-for="event in featuredEvents" 
            :key="event.id" 
            variant="elevated" 
            hoverable
          >
            <template #image>
              <div 
                v-if="event.featured_image?.url"
                class="w-full h-48 bg-cover bg-center"
                :style="{ backgroundImage: `url(${event.featured_image.url})` }"
              ></div>
              <div v-else class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400 text-sm">No Image</span>
              </div>
            </template>
            
            <template #header>
              <div class="flex items-center justify-between text-sm text-gray-500">
                <span>{{ formatDate(event.event_date) }}</span>
                <span class="px-3 py-1 bg-primary-green text-white rounded-full text-xs">
                  {{ event.category?.name }}
                </span>
              </div>
            </template>
            
            <h3 class="text-xl font-bold text-primary-dark mb-2">{{ event.title }}</h3>
            <p class="text-gray-600 mb-4">{{ event.excerpt }}</p>
            
            <template #footer>
              <Button variant="ghost" :href="`/events/${event.slug}`" fullWidth>
                Learn More
              </Button>
            </template>
          </Card>
        </div>
        
        <div class="text-center">
          <Button variant="outline" href="/events">
            View All Events
          </Button>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-display font-bold text-primary-dark mb-4">
            What People Say
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Hear from those who have experienced the impact of our work firsthand.
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <Card v-for="testimonial in testimonials" :key="testimonial.id" variant="bordered">
            <div class="flex items-center mb-4">
              <div 
                v-if="testimonial.avatar?.url"
                class="w-12 h-12 rounded-full mr-4 bg-cover bg-center"
                :style="{ backgroundImage: `url(${testimonial.avatar.url})` }"
              ></div>
              <div v-else class="w-12 h-12 rounded-full mr-4 bg-gray-300 flex items-center justify-center">
                <span class="text-gray-500 text-xs font-bold">{{ testimonial.name.charAt(0) }}</span>
              </div>
              <div>
                <h4 class="font-bold text-primary-dark">{{ testimonial.name }}</h4>
                <p class="text-sm text-gray-500">{{ testimonial.position }}</p>
              </div>
            </div>
            
            <div class="flex mb-3">
              <svg 
                v-for="i in 5" 
                :key="i"
                class="w-5 h-5"
                :class="i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            </div>
            
            <p class="text-gray-600 italic">"{{ testimonial.content }}"</p>
          </Card>
        </div>
      </div>
    </section>

    <!-- Gallery Preview Section -->
    <section v-if="recentGalleryImages.length > 0" class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-display font-bold text-primary-dark mb-4">
            Recent Moments
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            A glimpse into our community's journey through powerful visuals.
          </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div
            v-for="image in recentGalleryImages"
            :key="image.id"
            class="relative group overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer"
          >
            <img
              :src="image.image_url"
              :alt="image.alt_text || image.caption"
              class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-500"
            />
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
              <p v-if="image.album" class="text-white text-sm font-medium">
                {{ image.album.title }}
              </p>
            </div>
          </div>
        </div>
        
        <div class="text-center mt-10">
          <Button variant="outline" href="/gallery">
            View Full Gallery
          </Button>
        </div>
      </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-20 bg-primary-orange text-white">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-display font-bold mb-6">
          Ready to Make a Difference?
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">
          Your support can change lives. Join us today and be part of something meaningful.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Button variant="secondary" size="lg" href="/donate">
            Donate Now
          </Button>
          <Button variant="outline" size="lg" href="/volunteer" class="border-white text-white hover:bg-white hover:text-primary-orange">
            Become a Volunteer
          </Button>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps({
  featuredEvents: {
    type: Array,
    default: () => []
  },
  testimonials: {
    type: Array,
    default: () => []
  },
  recentGalleryImages: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({
      totalDonations: 0,
      eventsHosted: 0,
      volunteersEngaged: 0,
      communitiesReached: 0
    })
  }
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
  });
};
</script>

<style scoped>
.hero-section {
  background: linear-gradient(135deg, #252A34 0%, #219D80 100%);
  background-size: cover;
  background-position: center;
}

.stat-card {
  @apply transform transition-transform duration-200 hover:scale-105;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}
</style>
