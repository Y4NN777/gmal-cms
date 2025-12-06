<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useCategory } from '@/composables/useCategory';
import { useDate } from '@/composables/useDate';

const { translateCategory } = useCategory();
const { formatDate } = useDate();

const props = defineProps({
  events: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
});

const selectedCategory = ref(null);

const filteredEvents = computed(() => {
  if (!selectedCategory.value) return props.events;
  return props.events.filter(event => event.category?.id === selectedCategory.value);
});

const goToEvent = (slug) => {
  router.visit(`/events/${slug}`);
};
</script>

<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden bg-[#252A34]">
      <div class="absolute inset-0">
        <img 
          :src="`${$page.props.assetUrl || ''}/images/home/hero-background.png`" 
          alt="Events Hero" 
          class="w-full h-full object-cover opacity-40"
          @error="$event.target.style.display='none'"
        />
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-[#252A34]/90 to-[#252A34]/40"></div>
      
      <div class="relative z-10 container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
        <div class="flex items-center gap-3 mb-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
          <div class="h-px w-12 bg-[#EE9446]"></div>
          <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Upcoming Events
          </span>
          <div class="h-px w-12 bg-[#EE9446]"></div>
        </div>
        <h1 
          class="text-white font-display font-bold text-5xl md:text-7xl leading-tight mb-6 opacity-0 animate-fade-in-up"
          style="animation-delay: 0.4s; animation-fill-mode: forwards;"
        >
          Join Us in <span class="text-[#EE9446]">Action</span>
        </h1>
        <p 
          class="text-gray-300 font-sans text-lg md:text-xl leading-relaxed max-w-2xl opacity-0 animate-fade-in-up"
          style="animation-delay: 0.6s; animation-fill-mode: forwards;"
        >
          Participate in our upcoming events and help us make a difference in the community.
        </p>
      </div>
    </section>

    <!-- Events Grid -->
    <section class="py-24 bg-[#F8F9FA]">
      <div class="container mx-auto px-4">
        <!-- Filters -->
        <div class="mb-12 flex flex-wrap justify-center gap-4">
          <button
            v-for="category in categories"
            :key="category.id"
            @click="selectedCategory = category.id === selectedCategory ? null : category.id"
            class="px-6 py-3 rounded-full font-sans font-medium transition-all duration-300 transform hover:-translate-y-1"
            :class="selectedCategory === category.id 
              ? 'bg-[#EE9446] text-white shadow-lg shadow-[#EE9446]/30' 
              : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
          >
            {{ translateCategory(category) }}
          </button>
          <button
            v-if="selectedCategory"
            @click="selectedCategory = null"
            class="px-6 py-3 rounded-full font-sans font-medium bg-gray-200 text-gray-600 hover:bg-gray-300 transition-all duration-300"
          >
            Clear Filters
          </button>
        </div>

        <!-- Events Grid -->
        <div v-if="filteredEvents.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div
            v-for="event in filteredEvents"
            :key="event.id"
            class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 cursor-pointer transform hover:-translate-y-2"
            @click="goToEvent(event.slug)"
          >
            <!-- Image / Placeholder -->
            <div class="relative h-64 overflow-hidden">
              <div class="absolute inset-0 bg-gradient-to-br from-[#EE9446] to-[#E17111] opacity-90 group-hover:scale-110 transition-transform duration-700"></div>
              <img 
                v-if="event.image" 
                :src="event.image" 
                :alt="event.title"
                class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div v-else class="absolute inset-0 flex items-center justify-center text-white/20">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              
              <!-- Date Badge -->
              <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg">
                <div class="text-[#252A34] font-bold font-display text-lg leading-none text-center">
                  {{ new Date(event.event_date).getDate() }}
                </div>
                <div class="text-[#EE9446] text-xs font-bold uppercase tracking-wider text-center">
                  {{ new Date(event.event_date).toLocaleString('default', { month: 'short' }) }}
                </div>
              </div>

              <!-- Category Badge -->
              <div class="absolute top-4 right-4">
                <span
                  class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full bg-black/30 backdrop-blur-md text-white border border-white/20"
                >
                  {{ translateCategory(event.category) }}
                </span>
              </div>
            </div>
            
            <div class="p-8">
              <h3 class="text-2xl font-display font-bold text-[#252A34] mb-3 group-hover:text-[#EE9446] transition-colors duration-300 line-clamp-2">
                {{ event.title }}
              </h3>
              <p class="text-[#555555] font-sans text-sm mb-6 line-clamp-3 leading-relaxed">
                {{ event.excerpt }}
              </p>
              
              <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <div class="flex items-center text-sm text-gray-500">
                  <svg class="w-4 h-4 mr-2 text-[#EE9446]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ new Date(event.event_date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                </div>
                <span class="text-[#EE9446] font-bold text-sm uppercase tracking-wide group-hover:translate-x-1 transition-transform duration-300 flex items-center">
                  Learn More
                  <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-24">
          <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-2">No Events Found</h3>
          <p class="text-gray-500">We couldn't find any events matching your criteria.</p>
        </div>
      </div>
    </section>
  </AppLayout>
</template>
