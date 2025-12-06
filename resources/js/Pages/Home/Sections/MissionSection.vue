<template>
  <section class="relative py-24 md:py-32 bg-gray-50 overflow-hidden" ref="sectionRef">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
        <!-- Content Side -->
        <div class="order-2 lg:order-1">
          <!-- Section Label -->
          <div class="flex items-center gap-4 mb-8">
            <div class="h-0.5 w-12 bg-primary-green"></div>
            <span class="text-primary-green font-sans font-bold text-sm uppercase tracking-[0.2em]">
              Our Mission
            </span>
          </div>
          
          <!-- Heading -->
          <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl text-gray-900 leading-[1.1] mb-8">
            Let Us Come Together To Make a <span class="text-primary-green">Difference</span>
          </h2>
          
          <!-- Description -->
          <p class="text-gray-600 font-sans text-lg leading-relaxed mb-12">
            Our mission is to empower children with hope and opportunity through education, healthcare, and sustainable community development. We believe in transparency and direct impact.
          </p>
          
          <!-- Mission Cards -->
          <div class="space-y-6 mb-12">
            <!-- Card 1 -->
            <div class="group relative p-8 bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
              <div class="absolute top-0 right-0 w-32 h-32 bg-primary-orange/5 rounded-full blur-3xl group-hover:bg-primary-orange/10 transition-colors"></div>
              <h3 class="font-display font-bold text-2xl text-primary-orange mb-3 uppercase relative z-10">
                Our Mission
              </h3>
              <p class="text-gray-600 font-sans text-base leading-relaxed relative z-10">
                To provide sustainable solutions for poverty alleviation through education, healthcare, and community empowerment.
              </p>
            </div>
            
            <!-- Card 2 -->
            <div class="group relative p-8 bg-white rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
              <div class="absolute top-0 right-0 w-32 h-32 bg-primary-green/5 rounded-full blur-3xl group-hover:bg-primary-green/10 transition-colors"></div>
              <h3 class="font-display font-bold text-2xl text-primary-green mb-3 uppercase relative z-10">
                Your Support
              </h3>
              <p class="text-gray-600 font-sans text-base leading-relaxed relative z-10">
                100% of your donation goes directly to helping children in need, providing them with education, healthcare, and shelter.
              </p>
            </div>
          </div>
          
          <!-- Progress Bars -->
          <div class="space-y-8">
            <div>
              <div class="flex justify-between items-center mb-3">
                <span class="text-gray-900 font-sans font-bold text-lg uppercase tracking-wide">Donations Impact</span>
                <span class="text-primary-orange font-bold text-lg">100%</span>
              </div>
              <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-primary-orange to-orange-600 rounded-full transition-all duration-[1.5s] ease-out"
                  :style="{ width: isVisible ? '100%' : '0%' }"
                ></div>
              </div>
            </div>
            
            <div>
              <div class="flex justify-between items-center mb-3">
                <span class="text-gray-900 font-sans font-bold text-lg uppercase tracking-wide">Education Focus</span>
                <span class="text-primary-green font-bold text-lg">85%</span>
              </div>
              <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-primary-green to-green-600 rounded-full transition-all duration-[1.5s] ease-out delay-200"
                  :style="{ width: isVisible ? '85%' : '0%' }"
                ></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Image Side -->
        <div class="order-1 lg:order-2 relative">
          <!-- Main Image -->
          <div class="relative rounded-[2rem] overflow-hidden shadow-2xl">
            <img 
              :src="`${$page.props.assetUrl || ''}/images/about_right.jpg`" 
              alt="Our Mission" 
              class="w-full h-[700px] object-cover transform hover:scale-105 transition-transform duration-700"
              @error="$event.target.style.display='none'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
            
            <!-- Overlay Card -->
            <div class="absolute bottom-8 left-8 right-8 bg-white/95 backdrop-blur-md rounded-2xl p-8 shadow-2xl border border-white/20">
              <h4 class="text-primary-orange font-display font-bold text-xl mb-6 uppercase tracking-wide">
                Together, we create the future
              </h4>
              
              <ul class="space-y-4">
                <li class="flex items-start gap-4">
                  <div class="w-2 h-2 rounded-full bg-primary-orange mt-2.5 flex-shrink-0 shadow-[0_0_10px_rgba(238,148,70,0.5)]"></div>
                  <span class="text-gray-700 font-sans font-medium">Providing safe shelter and basic necessities</span>
                </li>
                <li class="flex items-start gap-4">
                  <div class="w-2 h-2 rounded-full bg-primary-green mt-2.5 flex-shrink-0 shadow-[0_0_10px_rgba(33,157,128,0.5)]"></div>
                  <span class="text-gray-700 font-sans font-medium">Ensuring access to quality education</span>
                </li>
                <li class="flex items-start gap-4">
                  <div class="w-2 h-2 rounded-full bg-primary-orange mt-2.5 flex-shrink-0 shadow-[0_0_10px_rgba(238,148,70,0.5)]"></div>
                  <span class="text-gray-700 font-sans font-medium">Building sustainable communities</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const sectionRef = ref(null);
const isVisible = ref(false);

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        isVisible.value = true;
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  if (sectionRef.value) {
    observer.observe(sectionRef.value);
  }
});
</script>
