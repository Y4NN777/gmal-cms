<template>
  <section class="relative py-10 md:py-16 bg-gray-900 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary-orange/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-primary-green/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-10">
        <div class="flex items-center justify-center gap-4 mb-4">
          <div class="h-0.5 w-12 bg-primary-orange"></div>
          <span class="text-primary-orange font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Testimonials
          </span>
          <div class="h-0.5 w-12 bg-primary-orange"></div>
        </div>
        
        <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white leading-[1.1]">
          What People Say About <span class="text-primary-orange">Our Work</span>
        </h2>
      </div>
      
      <!-- Testimonial Slider -->
      <div class="max-w-4xl mx-auto relative group">
        <!-- Navigation Buttons -->
        <button class="swiper-button-prev-custom absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 md:-translate-x-12 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-primary-orange border border-white/20 hover:border-primary-orange text-white transition-all duration-300 flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        
        <button class="swiper-button-next-custom absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 md:translate-x-12 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-primary-orange border border-white/20 hover:border-primary-orange text-white transition-all duration-300 flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="30"
          :loop="true"
          :autoplay="{
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
          }"
          :pagination="{
            clickable: true,
            el: '.swiper-pagination-custom'
          }"
          :navigation="{
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom'
          }"
          class="!pb-12 !pt-12"
        >
          <swiper-slide v-for="(testimonial, index) in testimonials" :key="index">
            <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-[2rem] p-6 md:p-10 shadow-2xl mx-4 my-4">
              <!-- Quote Icon -->
              <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-orange to-orange-600 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30">
                  <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.165 1.4.615 2.52 1.35 3.35.732.833 1.646 1.25 2.742 1.25.967 0 1.768-.29 2.402-.876.627-.576.942-1.365.942-2.368v.01z"/>
                  </svg>
                </div>
              </div>
              
              <!-- Testimonial Text -->
              <blockquote class="text-center mb-8 mt-6">
                <p class="text-white/90 font-display font-medium text-xl md:text-2xl leading-relaxed italic">
                  "{{ testimonial.text }}"
                </p>
              </blockquote>
              
              <!-- Author -->
              <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full border-4 border-primary-orange shadow-xl mb-3 overflow-hidden">
                  <img 
                    :src="`${$page.props.assetUrl || ''}/images/${testimonial.image}`" 
                    :alt="testimonial.author" 
                    class="w-full h-full object-cover"
                    @error="$event.target.style.display='none'"
                  />
                </div>
                <cite class="not-italic text-center">
                  <h4 class="text-white font-display font-bold text-xl mb-0.5">{{ testimonial.author }}</h4>
                  <span class="text-white/60 font-sans font-semibold text-xs uppercase tracking-widest">{{ testimonial.role }}</span>
                </cite>
              </div>
            </div>
          </swiper-slide>
          
          <!-- Custom Pagination -->
          <div class="swiper-pagination-custom flex justify-center gap-4 mt-6"></div>
        </swiper>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

const modules = [Autoplay, Pagination, Navigation];

const testimonials = [
  {
    text: "Give Me A Lift is truly making a difference! Seeing how 100% of donations go directly to helping children in need gives me confidence that my support is changing lives. Their transparency and dedication are inspiring.",
    author: "Cameron Williamson",
    role: "Founder",
    image: "testimonials1.png"
  },
  {
    text: "The impact this organization has on the local community is visible and profound. Every child they help represents a brighter future for the entire region.",
    author: "Sarah Jenkins",
    role: "Community Leader",
    image: "testimonials2.png"
  },
  {
    text: "I've never seen an NGO so committed to transparency. Knowing exactly where my money goes makes all the difference in the world.",
    author: "Michael Chen",
    role: "Donor",
    image: "testimonials3.png"
  }
];
</script>

<style>
.swiper-pagination-custom .swiper-pagination-bullet {
  width: 10px;
  height: 10px;
  background: rgba(255, 255, 255, 0.2);
  opacity: 1;
  transition: all 0.3s ease;
}

.swiper-pagination-custom .swiper-pagination-bullet-active {
  background: #EE9446; /* primary-orange */
  transform: scale(1.2);
}
</style>
