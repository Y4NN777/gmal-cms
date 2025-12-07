<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative h-[60vh] min-h-[500px] overflow-hidden bg-[#252A34]">
      <div class="absolute inset-0">
        <img 
          :src="`${$page.props.assetUrl || ''}/images/home.jpg`" 
          alt="About Hero" 
          class="w-full h-full object-cover opacity-100"
          @error="$event.target.style.display='none'"
        />
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-[#252A34]/90 to-[#252A34]/40"></div>
      
      <div class="relative z-10 container mx-auto px-4 h-full flex items-center">
        <div class="max-w-3xl">
          <div class="flex items-center gap-3 mb-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
            <div class="h-px w-12 bg-[#EE9446]"></div>
            <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">
              Who We Are
            </span>
          </div>
          <h1 
            class="text-white font-display font-bold text-5xl md:text-7xl leading-tight mb-6 opacity-0 animate-fade-in-up"
            style="animation-delay: 0.4s; animation-fill-mode: forwards;"
          >
            Empowering Communities Through <span class="text-[#EE9446]">Compassion</span>
          </h1>
          <p 
            class="text-gray-300 font-sans text-lg md:text-xl leading-relaxed max-w-2xl opacity-0 animate-fade-in-up"
            style="animation-delay: 0.6s; animation-fill-mode: forwards;"
          >
            We are dedicated to uplifting underserved communities by providing essential resources, education, and opportunities for a brighter future.
          </p>
        </div>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-24 bg-white overflow-hidden">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Mission -->
        <div class="grid lg:grid-cols-2 gap-16 items-center mb-24">
          <div class="relative group">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl transform group-hover:scale-[1.01] transition-transform duration-500">
              <img 
                :src="`${$page.props.assetUrl || ''}/images/image_28_.jpg`" 
                alt="Our Mission" 
                class="w-full h-[500px] object-cover"
                @error="$event.target.style.display='none'"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-[#252A34]/50 to-transparent"></div>
            </div>
            <div class="absolute -bottom-8 -right-8 bg-white p-8 rounded-2xl shadow-xl max-w-xs hidden md:block">
              <p class="font-display font-bold text-2xl text-[#252A34] mb-2">"To serve is to love."</p>
              <p class="font-sans text-[#EE9446] font-semibold uppercase tracking-wider text-sm">Our Motto</p>
            </div>
          </div>
          
          <div>
            <div class="flex items-center gap-3 mb-6">
              <div class="h-px w-12 bg-[#219D80]"></div>
              <span class="text-[#219D80] font-sans font-bold text-sm uppercase tracking-[0.2em]">Our Mission</span>
            </div>
            <h2 class="font-display font-bold text-4xl md:text-5xl text-[#252A34] mb-6">
              Building a Foundation for <span class="text-[#219D80]">Success</span>
            </h2>
            <p class="text-[#555555] font-sans text-lg leading-relaxed mb-8">
              To uplift and empower underserved communities by providing essential resources, education, and opportunities. We believe every person deserves access to basic necessities, quality education, and the chance to thrive.
            </p>
            <ul class="space-y-4">
              <li class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-[#219D80]/10 flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-4 h-4 text-[#219D80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-[#555555] font-sans">Providing quality education to children in need</span>
              </li>
              <li class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-[#219D80]/10 flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-4 h-4 text-[#219D80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-[#555555] font-sans">Ensuring access to basic healthcare services</span>
              </li>
              <li class="flex items-start gap-3">
                <div class="w-6 h-6 rounded-full bg-[#219D80]/10 flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-4 h-4 text-[#219D80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-[#555555] font-sans">Empowering communities through sustainable development</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Vision -->
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div class="order-2 lg:order-1">
            <div class="flex items-center gap-3 mb-6">
              <div class="h-px w-12 bg-[#EE9446]"></div>
              <span class="text-[#EE9446] font-sans font-bold text-sm uppercase tracking-[0.2em]">Our Vision</span>
            </div>
            <h2 class="font-display font-bold text-4xl md:text-5xl text-[#252A34] mb-6">
              A World Where No One is <span class="text-[#EE9446]">Left Behind</span>
            </h2>
            <p class="text-[#555555] font-sans text-lg leading-relaxed mb-8">
              A world where no one is left behind, where communities are self-sufficient, and where compassion drives action. We envision thriving neighborhoods where everyone has the tools to build a better future.
            </p>
            <div class="bg-[#FEF5EE] p-8 rounded-2xl border border-[#EE9446]/10">
              <p class="font-display italic text-xl text-[#252A34] leading-relaxed">
                "We dream of a future where every child has a school to go to, every family has food on the table, and every community has the power to shape its own destiny."
              </p>
            </div>
          </div>
          
          <div class="order-1 lg:order-2 relative group">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl transform group-hover:scale-[1.01] transition-transform duration-500">
              <img 
                :src="`${$page.props.assetUrl || ''}/images/image_18_225x300.jpg`" 
                alt="Our Vision" 
                class="w-full h-[500px] object-cover"
                @error="$event.target.style.display='none'"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-[#252A34]/50 to-transparent"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Core Values -->
    <section class="py-24 bg-[#F8F9FA] relative">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="font-display font-bold text-4xl md:text-5xl text-[#252A34] mb-6">Our Core Values</h2>
          <p class="text-[#555555] font-sans text-lg">The principles that guide our every action and decision.</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          <!-- Value 1 -->
          <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="w-16 h-16 bg-[#EE9446]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#EE9446] transition-colors duration-300">
              <svg class="w-8 h-8 text-[#EE9446] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <h3 class="font-display font-bold text-xl text-[#252A34] mb-3">Compassion</h3>
            <p class="text-[#555555] font-sans text-sm leading-relaxed">We lead with empathy and understanding in everything we do.</p>
          </div>
          
          <!-- Value 2 -->
          <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="w-16 h-16 bg-[#219D80]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#219D80] transition-colors duration-300">
              <svg class="w-8 h-8 text-[#219D80] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="font-display font-bold text-xl text-[#252A34] mb-3">Community</h3>
            <p class="text-[#555555] font-sans text-sm leading-relaxed">Together we achieve more than we ever could alone.</p>
          </div>
          
          <!-- Value 3 -->
          <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="w-16 h-16 bg-[#252A34]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#252A34] transition-colors duration-300">
              <svg class="w-8 h-8 text-[#252A34] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="font-display font-bold text-xl text-[#252A34] mb-3">Integrity</h3>
            <p class="text-[#555555] font-sans text-sm leading-relaxed">Transparency and honesty in all our actions and operations.</p>
          </div>
          
          <!-- Value 4 -->
          <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">
            <div class="w-16 h-16 bg-[#EE9446]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#EE9446] transition-colors duration-300">
              <svg class="w-8 h-8 text-[#EE9446] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="font-display font-bold text-xl text-[#252A34] mb-3">Impact</h3>
            <p class="text-[#555555] font-sans text-sm leading-relaxed">Dedicated to making a real, measurable difference.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Stats -->
    <section class="relative py-24 bg-[#252A34] overflow-hidden">
      <!-- Decorative Background -->
      <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#EE9446]/10 rounded-full blur-[100px] pointer-events-none"></div>
      <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#219D80]/10 rounded-full blur-[100px] pointer-events-none"></div>
      
      <div class="container mx-auto px-4 relative z-10">
        <div class="grid md:grid-cols-4 gap-12 text-center">
          <div class="group">
            <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2 group-hover:text-[#EE9446] transition-colors duration-300">500+</div>
            <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">Families Helped</p>
          </div>
          <div class="group">
            <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2 group-hover:text-[#219D80] transition-colors duration-300">50+</div>
            <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">Community Events</p>
          </div>
          <div class="group">
            <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2 group-hover:text-[#EE9446] transition-colors duration-300">$250K+</div>
            <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">Funds Raised</p>
          </div>
          <div class="group">
            <div class="text-5xl md:text-6xl font-display font-bold text-white mb-2 group-hover:text-[#219D80] transition-colors duration-300">1,000+</div>
            <p class="text-gray-400 font-sans uppercase tracking-widest text-sm">Volunteers</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="py-24 bg-white">
      <div class="container mx-auto px-4">
        <div class="bg-gradient-to-r from-[#EE9446] to-[#E17111] rounded-3xl p-12 md:p-20 text-center text-white shadow-2xl relative overflow-hidden">
          <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/10 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
          
          <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="font-display font-bold text-4xl md:text-5xl mb-6">Join Our Movement</h2>
            <p class="font-sans text-lg md:text-xl opacity-90 mb-10 leading-relaxed">
              Together, we can create lasting change in our communities. Whether you donate, volunteer, or spread the word, every action counts.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
              <a
                href="/events"
                class="px-8 py-4 bg-white text-[#E17111] rounded-full font-sans font-bold uppercase tracking-wide hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1 shadow-lg"
              >
                View Our Events
              </a>
              <a
                href="/contact"
                class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-full font-sans font-bold uppercase tracking-wide hover:bg-white hover:text-[#E17111] transition-all duration-300 transform hover:-translate-y-1"
              >
                Get Involved
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>
