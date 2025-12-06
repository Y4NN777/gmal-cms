<template>
  <section class="relative py-24 md:py-32 bg-gray-50 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="flex items-center justify-center gap-4 mb-6">
          <div class="h-0.5 w-12 bg-primary-green"></div>
          <span class="text-primary-green font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Our Gallery
          </span>
          <div class="h-0.5 w-12 bg-primary-green"></div>
        </div>
        
        <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl text-gray-900 leading-[1.1] mb-6">
          See the <span class="text-primary-green">Impact</span> of Your Support
        </h2>
        
        <p class="text-gray-600 font-sans text-lg leading-relaxed">
          Real moments, real people, real change. Every image tells a story of hope and transformation.
        </p>
      </div>
      
      <!-- Masonry Gallery Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 auto-rows-[200px]">
        <!-- Large Image 1 -->
        <div class="col-span-2 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_20_.jpeg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            <div class="absolute bottom-6 left-6 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
              <p class="font-display font-bold text-xl">Community Gathering</p>
            </div>
          </div>
        </div>
        
        <!-- Large Image 2 -->
        <div class="col-span-2 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_1_noel_300x225.jpeg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            <div class="absolute bottom-6 left-6 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
              <p class="font-display font-bold text-xl">Holiday Celebration</p>
            </div>
          </div>
        </div>
        
        <!-- Small Images -->
        <div class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_30_Cassou_150x150.jpg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-orange/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_22_150x150.jpeg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-green/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_13_School_150x150.jpeg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-orange/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500">
          <img 
            :src="`${$page.props.assetUrl || ''}/images/image_23_150x150.jpeg`" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-green/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
      </div>
      
      <!-- View All Button -->
      <div class="text-center mt-16">
        <Link 
          href="/gallery" 
          class="inline-flex items-center gap-3 px-10 py-5 border-2 border-primary-green text-primary-green hover:bg-primary-green hover:text-white rounded-full font-sans font-bold text-lg uppercase tracking-wide transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-green-500/20"
        >
          View Full Gallery
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </Link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
