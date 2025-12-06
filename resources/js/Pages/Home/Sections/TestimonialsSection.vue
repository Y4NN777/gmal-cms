<template>
  <section class="relative py-24 md:py-32 bg-gray-900 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary-orange/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-primary-green/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="flex items-center justify-center gap-4 mb-6">
          <div class="h-0.5 w-12 bg-primary-orange"></div>
          <span class="text-primary-orange font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Testimonials
          </span>
          <div class="h-0.5 w-12 bg-primary-orange"></div>
        </div>
        
        <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl text-white leading-[1.1]">
          What People Say About <span class="text-primary-orange">Our Work</span>
        </h2>
      </div>
      
      <!-- Testimonial Card -->
      <div class="max-w-4xl mx-auto">
        <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-[2.5rem] p-12 md:p-16 shadow-2xl">
          <!-- Quote Icon -->
          <div class="absolute -top-10 left-1/2 transform -translate-x-1/2">
            <div class="w-20 h-20 bg-gradient-to-br from-primary-orange to-orange-600 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30">
              <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.165 1.4.615 2.52 1.35 3.35.732.833 1.646 1.25 2.742 1.25.967 0 1.768-.29 2.402-.876.627-.576.942-1.365.942-2.368v.01z"/>
              </svg>
            </div>
          </div>
          
          <!-- Testimonial Text -->
          <blockquote class="text-center mb-12 mt-6">
            <p class="text-white/90 font-display font-medium text-2xl md:text-3xl leading-relaxed italic">
              "Give Me A Lift is truly making a difference! Seeing how 100% of donations go directly to helping children in need gives me confidence that my support is changing lives. Their transparency and dedication are inspiring."
            </p>
          </blockquote>
          
          <!-- Author -->
          <div class="flex flex-col items-center">
            <div class="w-20 h-20 rounded-full border-4 border-primary-orange shadow-xl mb-4 overflow-hidden">
              <img 
                :src="`${$page.props.assetUrl || ''}/images/logo.png`" 
                alt="Cameron Williamson" 
                class="w-full h-full object-cover"
                @error="$event.target.style.display='none'"
              />
            </div>
            <cite class="not-italic text-center">
              <h4 class="text-white font-display font-bold text-2xl mb-1">Cameron Williamson</h4>
              <span class="text-white/60 font-sans font-semibold text-sm uppercase tracking-widest">Founder</span>
            </cite>
          </div>
          
          <!-- Pagination Dots -->
          <div class="flex justify-center gap-4 mt-12">
            <button class="w-3 h-3 rounded-full bg-primary-orange transition-all duration-300 transform hover:scale-150" aria-label="Slide 1"></button>
            <button class="w-3 h-3 rounded-full bg-white/20 hover:bg-white/50 transition-all duration-300 transform hover:scale-150" aria-label="Slide 2"></button>
            <button class="w-3 h-3 rounded-full bg-white/20 hover:bg-white/50 transition-all duration-300 transform hover:scale-150" aria-label="Slide 3"></button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
</script>
