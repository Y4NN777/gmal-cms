<template>
  <section class="relative h-screen min-h-[800px] overflow-hidden bg-[#252A34]">
    <!-- Background Image -->
    <div class="absolute inset-0">
      <img 
        :src="`${$page.props.assetUrl || ''}/images/home/hero-background.png`" 
        alt="Hero Background" 
        class="w-full h-full object-cover opacity-40"
        @error="$event.target.style.display='none'"
      />
    </div>
    
    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#EE9446]/20 to-transparent mix-blend-overlay"></div>
    
    <!-- Content -->
    <div class="relative z-10 h-full flex items-center justify-center container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <!-- Subheading -->
        <p 
          class="text-[#EEEEEE] font-sans font-semibold text-sm md:text-base uppercase tracking-[0.2em] mb-6 opacity-0 animate-fade-in-up"
          style="animation-delay: 0.2s; animation-fill-mode: forwards;"
        >
          Give Hope For Homeless
        </p>
        
        <!-- Main Heading -->
        <h1 
          class="text-white font-display font-bold text-5xl md:text-7xl lg:text-[80px] leading-[1.1] mb-8 opacity-0 animate-fade-in-up"
          style="animation-delay: 0.4s; animation-fill-mode: forwards;"
        >
          Helping each other can make <span class="text-[#EE9446]">world better</span>
        </h1>
        
        <!-- Description -->
        <p 
          class="text-[#EEEEEE] font-sans text-lg md:text-2xl leading-relaxed mb-12 max-w-2xl mx-auto opacity-0 animate-fade-in-up"
          style="animation-delay: 0.6s; animation-fill-mode: forwards;"
        >
          At Give Me A Lift, we believe that everyone deserves the chance to learn, grow, and thrive
        </p>
        
        <!-- CTA Buttons -->
        <div 
          class="flex flex-col sm:flex-row gap-6 justify-center opacity-0 animate-fade-in-up"
          style="animation-delay: 0.8s; animation-fill-mode: forwards;"
        >
          <Link 
            href="/donate" 
            class="px-10 py-4 bg-[#EE9446] hover:bg-[#E17111] text-white rounded-sm font-sans font-bold text-sm uppercase tracking-widest transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg"
          >
            Donate Now
          </Link>
          
          <Link 
            href="/about" 
            class="px-10 py-4 bg-transparent border-2 border-white hover:bg-white hover:text-[#252A34] text-white rounded-sm font-sans font-bold text-sm uppercase tracking-widest transition-all duration-300 transform hover:-translate-y-1"
          >
            Learn More
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
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation-name: fadeInUp;
  animation-duration: 0.8s;
  animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
}
</style>
