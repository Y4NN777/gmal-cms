<template>
  <section class="relative py-24 md:py-32 bg-white overflow-hidden">
    <!-- Decorative Background -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] opacity-5 pointer-events-none">
      <img :src="`${$page.props.assetUrl || ''}/images/background-pattern.png`" alt="" class="w-full h-full object-contain" @error="$event.target.style.display='none'" />
    </div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-20">
        <div class="flex items-center justify-center gap-4 mb-6">
          <div class="h-0.5 w-12 bg-primary-orange"></div>
          <span class="text-primary-orange font-sans font-bold text-sm uppercase tracking-[0.2em]">
            Get In Touch
          </span>
          <div class="h-0.5 w-12 bg-primary-orange"></div>
        </div>
        
        <h2 class="font-display font-bold text-4xl md:text-5xl lg:text-6xl text-gray-900 leading-[1.1] mb-6">
          Let's Make a <span class="text-primary-orange">Difference</span> Together
        </h2>
        
        <p class="text-gray-600 font-sans text-lg leading-relaxed">
          Have questions or want to get involved? We'd love to hear from you.
        </p>
      </div>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 max-w-7xl mx-auto">
        <!-- Contact Form -->
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12">
          <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-gray-700 font-sans font-bold text-sm uppercase tracking-wide">
                  First Name
                </label>
                <input 
                  type="text" 
                  placeholder="John" 
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-orange/20 focus:border-primary-orange transition-all duration-300"
                />
              </div>
              <div class="space-y-2">
                <label class="block text-gray-700 font-sans font-bold text-sm uppercase tracking-wide">
                  Last Name
                </label>
                <input 
                  type="text" 
                  placeholder="Doe" 
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-orange/20 focus:border-primary-orange transition-all duration-300"
                />
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-gray-700 font-sans font-bold text-sm uppercase tracking-wide">
                  Email
                </label>
                <input 
                  type="email" 
                  placeholder="john@example.com" 
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-orange/20 focus:border-primary-orange transition-all duration-300"
                />
              </div>
              <div class="space-y-2">
                <label class="block text-gray-700 font-sans font-bold text-sm uppercase tracking-wide">
                  Phone
                </label>
                <input 
                  type="tel" 
                  placeholder="+1 (555) 000-0000" 
                  class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-orange/20 focus:border-primary-orange transition-all duration-300"
                />
              </div>
            </div>
            
            <div class="space-y-2">
              <label class="block text-gray-700 font-sans font-bold text-sm uppercase tracking-wide">
                Message
              </label>
              <textarea 
                rows="5" 
                placeholder="Tell us how you'd like to help or what questions you have..." 
                class="w-full px-6 py-4 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-orange/20 focus:border-primary-orange transition-all duration-300 resize-none"
              ></textarea>
            </div>
            
            <button 
              type="submit" 
              class="w-full py-5 bg-gradient-to-r from-primary-orange to-orange-600 text-white rounded-xl font-sans font-bold text-lg uppercase tracking-wide shadow-lg hover:shadow-2xl hover:shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-1"
            >
              Send Message
            </button>
          </form>
        </div>
        
        <!-- Contact Info & CTA -->
        <div class="space-y-8">
          <!-- Address Card -->
          <div class="bg-gray-50 rounded-[2rem] p-10 border border-gray-100">
            <h3 class="text-gray-900 font-display font-bold text-3xl mb-6">
              Our Office
            </h3>
            <p class="text-gray-600 font-sans text-base leading-relaxed mb-10">
              Visit us or reach out through any of these channels. We're here to answer your questions and help you make an impact.
            </p>
            
            <div class="space-y-8">
              <!-- Location -->
              <div class="flex items-start gap-6 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-6 h-6 text-primary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-gray-900 font-sans font-bold text-lg mb-1">Location</h4>
                  <p class="text-gray-600 font-sans text-base leading-relaxed">
                    4517 Washington Ave. Manchester,<br/>Kentucky 39495
                  </p>
                </div>
              </div>
              
              <!-- Phone -->
              <div class="flex items-start gap-6 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-6 h-6 text-primary-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-gray-900 font-sans font-bold text-lg mb-1">Phone</h4>
                  <p class="text-gray-600 font-sans text-base leading-relaxed">
                    +1 405 589 0915<br/>
                    +226 67 18 24 38
                  </p>
                </div>
              </div>
              
              <!-- Email -->
              <div class="flex items-start gap-6 group">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:scale-110 transition-transform duration-300">
                  <svg class="w-6 h-6 text-primary-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                </div>
                <div>
                  <h4 class="text-gray-900 font-sans font-bold text-lg mb-1">Email</h4>
                  <a href="mailto:contactus@givemealift.org" class="text-gray-600 font-sans text-base hover:text-primary-orange transition-colors">
                    contactus@givemealift.org
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <!-- CTA Card -->
          <div class="relative bg-gradient-to-br from-primary-orange to-orange-600 rounded-[2rem] p-10 shadow-xl overflow-hidden group">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl transform group-hover:scale-150 transition-transform duration-700"></div>
            
            <h3 class="relative z-10 font-display font-bold text-2xl text-white mb-4">
              Ready to Donate?
            </h3>
            <p class="relative z-10 font-sans text-white/90 text-base mb-8 leading-relaxed">
              Your contribution can change lives today. Every donation makes a real difference.
            </p>
            <Link 
              href="/donate" 
              class="relative z-10 inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-orange rounded-xl font-sans font-bold uppercase tracking-wide hover:bg-gray-50 transition-colors shadow-lg"
            >
              Donate Now
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"/>
              </svg>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
