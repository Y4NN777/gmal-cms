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
        <div 
          class="col-span-2 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(0)"
        >
          <img 
            :src="images[0]" 
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
        <div 
          class="col-span-2 row-span-2 group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(1)"
        >
          <img 
            :src="images[1]" 
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
        <div 
          class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(2)"
        >
          <img 
            :src="images[2]" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-orange/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div 
          class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(3)"
        >
          <img 
            :src="images[3]" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-green/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div 
          class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(4)"
        >
          <img 
            :src="images[4]" 
            alt="Gallery" 
            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
            @error="$event.target.style.display='none'"
          />
          <div class="absolute inset-0 bg-primary-orange/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        </div>
        
        <div 
          class="group relative rounded-3xl overflow-hidden cursor-pointer shadow-lg hover:shadow-2xl transition-all duration-500"
          @click="showImg(5)"
        >
          <img 
            :src="images[5]" 
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

    <!-- Lightbox -->
    <VueEasyLightbox
      :visible="visible"
      :imgs="images"
      :index="index"
      @hide="onHide"
    />
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import VueEasyLightbox from 'vue-easy-lightbox';

const page = usePage();
const assetUrl = computed(() => page.props.assetUrl || '');

const images = computed(() => [
  `${assetUrl.value}/images/image_20_.jpeg`,
  `${assetUrl.value}/images/image_1_noel_300x225.jpeg`,
  `${assetUrl.value}/images/image_26_.jpg`,
  `${assetUrl.value}/images/image_28_.jpg`,
  `${assetUrl.value}/images/image_24_.jpeg`,
  `${assetUrl.value}/images/image_27_768x1024.jpg`
]);

const visible = ref(false);
const index = ref(0);

const showImg = (i) => {
  index.value = i;
  visible.value = true;
};

const onHide = () => {
  visible.value = false;
};
</script>

<style>
/* Lightbox Customization */
.vel-modal {
  background: rgba(0, 0, 0, 0.95) !important;
}

.vel-btns-wrapper .vel-btn {
  background-color: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  width: 60px !important;
  height: 60px !important;
  margin: 0 10px;
  transition: all 0.3s ease;
}

.vel-btns-wrapper .vel-btn:hover {
  background-color: #EE9446 !important; /* primary-orange */
  transform: scale(1.1);
  border-color: #EE9446;
}

.vel-btns-wrapper .vel-btn svg {
  width: 32px !important;
  height: 32px !important;
  fill: white !important;
}

/* Close button specific */
.vel-toolbar .vel-btn.btn__close {
  background-color: #EF4444 !important; /* red for close */
  top: 20px;
  right: 20px;
}

/* Nav buttons */
.vel-btn.btn__prev, .vel-btn.btn__next {
  background-color: rgba(33, 157, 128, 0.8) !important; /* primary-green */
}

.vel-btn.btn__prev:hover, .vel-btn.btn__next:hover {
  background-color: #1a7f67 !important;
}
</style>
