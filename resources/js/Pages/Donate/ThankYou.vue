<template>
  <AppLayout>
    <!-- Success Hero -->
    <section class="bg-gradient-to-r from-primary-green to-green-700 text-white py-20">
      <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto">
          <!-- Success Icon -->
          <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-in">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          </div>

          <h1 class="text-5xl font-display font-bold mb-6">
            Thank You for Your Generosity!
          </h1>
          
          <p class="text-xl text-white/90 mb-8">
            Your donation is making a real difference in our community. 
            We're grateful for your support and commitment to creating positive change.
          </p>

          <div class="bg-white/10 backdrop-blur rounded-lg p-6 mb-8">
            <p class="text-white/80 text-sm mb-2">
              You should receive a confirmation email shortly with your tax receipt.
            </p>
            <p class="text-white/80 text-sm">
              If you don't receive it within 24 hours, please check your spam folder 
              or <a href="/contact" class="underline hover:text-white">contact us</a>.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
          <h2 class="text-3xl font-bold text-gray-900 mb-6">
            Your Impact Starts Today
          </h2>
          <p class="text-lg text-gray-600 mb-12">
            Thanks to donors like you, we've been able to:
          </p>

          <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="p-6">
              <div class="text-5xl font-bold text-primary-orange mb-2">500+</div>
              <div class="text-gray-600">Families Fed</div>
            </div>
            <div class="p-6">
              <div class="text-5xl font-bold text-primary-green mb-2">1,000+</div>
              <div class="text-gray-600">Students Supported</div>
            </div>
            <div class="p-6">
              <div class="text-5xl font-bold text-red-600 mb-2">$250K+</div>
              <div class="text-gray-600">Total Impact</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Next Steps -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            Ways to Stay Involved
          </h2>

          <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-md text-center">
              <div class="w-16 h-16 bg-primary-orange rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold mb-3">Attend Events</h3>
              <p class="text-gray-600 mb-4">
                Join our community events and see your impact firsthand
              </p>
              <Button variant="ghost" href="/events">
                View Events
              </Button>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md text-center">
              <div class="w-16 h-16 bg-primary-green rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold mb-3">Follow Us</h3>
              <p class="text-gray-600 mb-4">
                Stay updated on our work and impact stories
              </p>
              <div class="flex justify-center gap-3">
                <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-primary-orange hover:text-white transition-colors">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                  </svg>
                </a>
                <a href="#" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-primary-orange hover:text-white transition-colors">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                  </svg>
                </a>
              </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md text-center">
              <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold mb-3">Get in Touch</h3>
              <p class="text-gray-600 mb-4">
                Have questions or want to learn more about our work?
              </p>
              <Button variant="ghost" href="/contact">
                Contact Us
              </Button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Return Home -->
    <section class="py-12 bg-white text-center">
      <Button variant="primary" size="lg" href="/">
        Return to Homepage
      </Button>
    </section>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
</script>

<style scoped>
@keyframes bounce-in {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-bounce-in {
  animation: bounce-in 0.6s ease-out;
}
</style>
