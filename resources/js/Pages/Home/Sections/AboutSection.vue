<template>
  <section class="relative py-24 md:py-32 bg-white overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] opacity-10 pointer-events-none">
      <img :src="`${$page.props.assetUrl || ''}/images/background-pattern.png`" alt="" class="w-full h-full object-contain" @error="$event.target.style.display='none'" />
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        <!-- Image Side -->
        <div class="relative group">
          <!-- Main Image -->
          <div class="relative rounded-3xl overflow-hidden shadow-2xl transform group-hover:scale-[1.01] transition-transform duration-500">
            <img 
              :src="`${$page.props.assetUrl || ''}/images/home_image3.jpeg`" 
              alt="About Give Me A Lift" 
              class="w-full h-[600px] object-cover"
              @error="$event.target.style.display='none'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#252A34]/40 to-transparent"></div>
          </div>
          
          <!-- Floating Card -->
          <div class="absolute -bottom-10 -right-10 bg-white rounded-xl p-8 shadow-[0_20px_50px_rgba(0,0,0,0.1)] max-w-xs animate-float">
            <div class="flex items-center gap-5">
              <div class="w-16 h-16 bg-[#EE9446] rounded-full flex items-center justify-center shadow-lg shadow-[#EE9446]/30">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
              </div>
              <div>
                <div class="text-4xl font-bold text-[#252A34] font-display">Many</div>
                <div class="text-sm text-[#555555] font-sans font-medium uppercase tracking-wide mt-1">Lives Impacted</div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Content Side -->
        <div class="pl-0 lg:pl-10">
          <!-- Section Label -->
          <div class="flex items-center gap-4 mb-6">
            <div class="h-0.5 w-12 bg-[#EE9446]"></div>
            <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">
              About Us
            </span>
          </div>
          
          <!-- Heading -->
          <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-[56px] text-[#252A34] leading-[1.2] mb-8">
            Your Support is <span class="text-[#EE9446]">Really</span> Powerful
          </h2>
          
          <!-- Description -->
          <p class="text-[#555555] font-sans text-lg leading-relaxed mb-10">
            At Give Me A Lift, we believe that everyone deserves the chance to learn, grow, and thrive. Founded in August 2017 in Oklahoma City, we are a 501(c)(3) non-profit organization dedicated to empowering education, improving health, and providing shelter for communities in Burkina Faso.
          </p>
          
          <!-- Key Points -->
          <div class="space-y-6 mb-10">
            <div class="flex items-start gap-5 group">
              <div class="w-14 h-14 bg-[#219D80]/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-[#219D80] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#219D80] group-hover:text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div>
                <h3 class="font-display font-bold text-xl text-[#252A34] mb-2">100% Direct Impact</h3>
                <p class="text-[#555555] font-sans leading-relaxed">Every dollar goes directly to those in need</p>
              </div>
            </div>
            
            <div class="flex items-start gap-5 group">
              <div class="w-14 h-14 bg-[#EE9446]/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-[#EE9446] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#EE9446] group-hover:text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
              </div>
              <div>
                <h3 class="font-display font-bold text-xl text-[#252A34] mb-2">Education First</h3>
                <p class="text-[#555555] font-sans leading-relaxed">Empowering children through quality education</p>
              </div>
            </div>
            
            <div class="flex items-start gap-5 group">
              <div class="w-14 h-14 bg-[#219D80]/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-[#219D80] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#219D80] group-hover:text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div>
                <h3 class="font-display font-bold text-xl text-[#252A34] mb-2">Community Care</h3>
                <p class="text-[#555555] font-sans leading-relaxed">Building sustainable futures together</p>
              </div>
            </div>
          </div>
          
          <!-- CTA Button -->
          <Link 
            href="/about" 
            class="inline-flex items-center gap-3 px-8 py-4 bg-[#EE9446] hover:bg-[#E17111] text-white rounded-full font-sans font-bold uppercase tracking-widest shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
          >
            Learn More About Us
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}
</style>
