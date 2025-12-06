<template>
  <section class="relative py-24 md:py-32 bg-white overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-[400px] h-[800px] opacity-[0.03] pointer-events-none">
      <img 
        :src="`${$page.props.assetUrl || ''}/images/background-pattern0.png`" 
        alt="" 
        class="w-full h-full object-contain" 
        @error="$event.target.style.display='none'" 
      />
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="flex items-center justify-center gap-4 mb-6">
          <div class="h-0.5 w-12 bg-primary-orange"></div>
          <span class="text-primary-orange font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Our Stories
          </span>
          <div class="h-0.5 w-12 bg-primary-orange"></div>
        </div>
        
        <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl text-gray-900 leading-[1.1] mb-6">
          Stories of <span class="text-primary-orange">Hope</span> & <span class="text-primary-green">Change</span>
        </h2>
        
        <p class="text-gray-600 font-sans text-lg leading-relaxed">
          Real stories from the communities we serve, showcasing the transformative power of collective compassion.
        </p>
      </div>
      
      <!-- Story Cards Grid -->
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="(story, index) in stories" :key="index" 
          class="group bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2"
        >
          <!-- Image Container -->
          <div class="relative h-64 overflow-hidden">
            <img 
              :src="`${$page.props.assetUrl || ''}${story.image}`" 
              :alt="story.category" 
              class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
              @error="$event.target.style.display='none'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
            
            <!-- Category Tag -->
            <div class="absolute top-4 left-4">
              <span 
                class="px-4 py-1.5 text-white text-xs font-sans font-bold rounded-full uppercase tracking-wider shadow-lg"
                :class="story.categoryColor"
              >
                {{ story.category }}
              </span>
            </div>
          </div>
          
          <!-- Content -->
          <div class="p-8">
            <h3 class="text-gray-900 font-display font-bold text-xl mb-3 group-hover:text-primary-orange transition-colors duration-300">
              {{ story.title }}
            </h3>
            <p class="text-gray-500 font-sans text-sm mb-6 line-clamp-2 leading-relaxed">
              {{ story.description }}
            </p>
            
            <Link 
              href="/events" 
              class="inline-flex items-center gap-2 font-sans font-bold text-sm uppercase tracking-wide transition-all duration-300 group/link"
              :class="story.linkColor"
            >
              Read More
              <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </Link>
          </div>
        </div>
      </div>
      
      <!-- View All Button -->
      <div class="text-center mt-16">
        <Link 
          href="/events" 
          class="inline-flex items-center gap-3 px-10 py-5 border-2 border-primary-orange text-primary-orange hover:bg-primary-orange hover:text-white rounded-full font-sans font-bold text-lg uppercase tracking-wide transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/20"
        >
          View All Stories
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </Link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const stories = [
  {
    image: '/images/image_28_.jpg',
    category: 'Education',
    categoryColor: 'bg-primary-orange',
    title: 'A Year of Growth & Learning',
    description: 'Celebrating the academic achievements and personal development of our students.',
    linkColor: 'text-primary-orange'
  },
  {
    image: '/images/image_33_.jpg',
    category: 'Community',
    categoryColor: 'bg-primary-green',
    title: 'Christmas 2020',
    description: 'Bringing joy and celebration to children during the holiday season.',
    linkColor: 'text-primary-green'
  },
  {
    image: '/images/image_21_.jpeg',
    category: 'Food',
    categoryColor: 'bg-primary-orange',
    title: 'Community Support',
    description: 'Providing nutritious meals to families in need across our communities.',
    linkColor: 'text-primary-orange'
  },
  {
    image: '/images/image_18_225x300.jpg',
    category: 'Health',
    categoryColor: 'bg-primary-green',
    title: 'School Year 2022-2023',
    description: 'Ensuring health and wellness for every student in our programs.',
    linkColor: 'text-primary-green'
  }
];
</script>
