<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden bg-[#252A34]">
      <div class="absolute inset-0">
        <img 
          :src="`${$page.props.assetUrl || ''}/images/home/hero-background.png`" 
          alt="Contact Hero" 
          class="w-full h-full object-cover opacity-30"
          @error="$event.target.style.display='none'"
        />
      </div>
      <div class="absolute inset-0 bg-gradient-to-b from-[#252A34]/80 to-[#252A34]/40"></div>
      
      <div class="relative z-10 container mx-auto px-4 h-full flex flex-col justify-center items-center text-center">
        <div class="flex items-center gap-3 mb-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
          <div class="h-px w-12 bg-[#EE9446]"></div>
          <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Get In Touch
          </span>
          <div class="h-px w-12 bg-[#EE9446]"></div>
        </div>
        <h1 
          class="text-white font-display font-bold text-5xl md:text-7xl leading-tight mb-6 opacity-0 animate-fade-in-up"
          style="animation-delay: 0.4s; animation-fill-mode: forwards;"
        >
          We'd Love to <span class="text-[#EE9446]">Hear From You</span>
        </h1>
        <p 
          class="text-gray-300 font-sans text-lg md:text-xl leading-relaxed max-w-2xl opacity-0 animate-fade-in-up"
          style="animation-delay: 0.6s; animation-fill-mode: forwards;"
        >
          Have questions or want to get involved? Reach out to us and let's make a difference together.
        </p>
      </div>
    </section>

    <!-- Contact Section Component -->
    <ContactSection />
    
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContactSection from '@/Pages/Home/Sections/ContactSection.vue';
</script>
