<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-green-600 to-green-700 text-white py-20">
      <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About GiveMeALift</h1>
        <p class="text-xl text-green-100">Empowering communities through compassion and action</p>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
          <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-lg">
            <div class="w-16 h-16 bg-orange-600 rounded-lg flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-700 leading-relaxed">
              To uplift and empower underserved communities by providing essential resources, 
              education, and opportunities. We believe every person deserves access to basic 
              necessities, quality education, and the chance to thrive.
            </p>
          </div>

          <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-lg">
            <div class="w-16 h-16 bg-green-600 rounded-lg flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Vision</h2>
            <p class="text-gray-700 leading-relaxed">
              A world where no one is left behind, where communities are self-sufficient, 
              and where compassion drives action. We envision thriving neighborhoods where 
              everyone has the tools to build a better future.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Our Core Values</h2>
        
        <div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto">
          <div class="text-center">
            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Compassion</h3>
            <p class="text-gray-600 text-sm">We lead with empathy and understanding</p>
          </div>

          <div class="text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Community</h3>
            <p class="text-gray-600 text-sm">Together we achieve more</p>
          </div>

          <div class="text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Integrity</h3>
            <p class="text-gray-600 text-sm">Transparency in all we do</p>
          </div>

          <div class="text-center">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Impact</h3>
            <p class="text-gray-600 text-sm">Making a real difference</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Stats -->
    <section class="py-16 bg-gradient-to-r from-orange-600 to-green-600 text-white">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Our Impact</h2>
        
        <div class="grid md:grid-cols-4 gap-8 max-w-6xl mx-auto text-center">
          <div>
            <div class="text-5xl font-bold mb-2">500+</div>
            <p class="text-orange-100">Families Helped</p>
          </div>
          <div>
            <div class="text-5xl font-bold mb-2">50+</div>
            <p class="text-orange-100">Community Events</p>
          </div>
          <div>
            <div class="text-5xl font-bold mb-2">$250K+</div>
            <p class="text-orange-100">Funds Raised</p>
          </div>
          <div>
            <div class="text-5xl font-bold mb-2">1,000+</div>
            <p class="text-orange-100">Volunteers</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 text-center max-w-3xl">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Join Our Movement</h2>
        <p class="text-xl text-gray-600 mb-8">
          Together, we can create lasting change in our communities. 
          Whether you donate, volunteer, or spread the word, every action counts.
        </p>
        <div class="flex gap-4 justify-center">
          <a
            href="/events"
            class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors"
          >
            View Our Events
          </a>
          <a
            href="/contact"
            class="px-8 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium transition-colors"
          >
            Get Involved
          </a>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';
</script>
