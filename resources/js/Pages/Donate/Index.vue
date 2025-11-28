<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-orange to-red-600 text-white py-20">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl md:text-6xl font-display font-bold mb-6 animate-fade-in">
          Make a Difference Today
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-white/90">
          Your generous donation helps us transform lives and build stronger communities.
          Every contribution, no matter the size, creates lasting impact.
        </p>
      </div>
    </section>

    <!-- Donation Form Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
          <!-- Amount Selection -->
          <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
              Choose Your Donation Amount
            </h2>

            <!-- Suggested Amounts -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
              <button
                v-for="amount in suggestedAmounts"
                :key="amount.value"
                @click="selectedAmount = amount.value"
                :class="[
                  'p-6 rounded-xl border-2 transition-all duration-200 text-center',
                  selectedAmount === amount.value
                    ? 'border-primary-orange bg-primary-orange text-white shadow-lg scale-105'
                    : 'border-gray-200 hover:border-primary-orange hover:shadow-md'
                ]"
              >
                <div class="text-3xl font-bold mb-2">${{ amount.value }}</div>
                <div class="text-sm opacity-80">{{ amount.impact }}</div>
              </button>
            </div>

            <!-- Custom Amount -->
            <div class="mb-8">
              <label class="block text-gray-700 font-semibold mb-3">
                Or enter a custom amount:
              </label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-xl">
                  $
                </span>
                <input
                  v-model.number="customAmount"
                  type="number"
                  min="1"
                  step="1"
                  placeholder="Enter amount"
                  class="w-full pl-10 pr-4 py-4 text-xl border-2 border-gray-300 rounded-lg focus:border-primary-orange focus:outline-none transition-colors"
                  @input="selectedAmount = null"
                />
              </div>
            </div>

            <!-- Donor Information (Optional) -->
            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
              <h3 class="text-lg font-semibold mb-4 text-gray-900">
                Donor Information (Optional)
              </h3>
              <div class="grid md:grid-cols-2 gap-4">
                <input
                  v-model="donorInfo.name"
                  type="text"
                  placeholder="Your Name"
                  class="px-4 py-3 border border-gray-300 rounded-lg focus:border-primary-orange focus:outline-none"
                />
                <input
                  v-model="donorInfo.email"
                  type="email"
                  placeholder="Email Address"
                  class="px-4 py-3 border border-gray-300 rounded-lg focus:border-primary-orange focus:outline-none"
                />
              </div>
              <p class="text-sm text-gray-500 mt-2">
                Provide your details to receive a tax receipt and stay updated on our impact.
              </p>
            </div>

            <!-- PayPal Button -->
            <div class="text-center">
              <button
                @click="proceedToPayPal"
                class="px-12 py-4 rounded-lg text-lg font-semibold transition-all duration-200 transform bg-gradient-to-r from-primary-orange to-red-600 text-white hover:from-red-600 hover:to-primary-orange shadow-lg hover:shadow-xl hover:scale-105"
              >
                <span v-if="finalAmount > 0">
                  Proceed to PayPal (${{ finalAmount }} suggested)
                </span>
                <span v-else>
                  Donate via PayPal
                </span>
              </button>
              
              <p class="text-sm text-gray-500 mt-4">
                Secure payment powered by PayPal · You'll choose the final amount on PayPal
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Section -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            Your Impact in Action
          </h2>
          
          <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-xl shadow-lg text-center">
              <div class="w-16 h-16 bg-primary-orange rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Feed Families</h3>
              <p class="text-gray-600">
                $50 provides nutritious meals for 10 families in need
              </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg text-center">
              <div class="w-16 h-16 bg-primary-green rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Educate Children</h3>
              <p class="text-gray-600">
                $100 provides school supplies for 20 students for a year
              </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-lg text-center">
              <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900 mb-3">Support Health</h3>
              <p class="text-gray-600">
                $200 provides medical care for families without insurance
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
          <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            Frequently Asked Questions
          </h2>
          
          <div class="space-y-6">
            <div class="border-b border-gray-200 pb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                Is my donation tax-deductible?
              </h3>
              <p class="text-gray-600">
                Yes! GiveMeALift is a registered 501(c)(3) nonprofit organization. 
                You will receive a tax receipt via email after your donation.
              </p>
            </div>

            <div class="border-b border-gray-200 pb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                How is my donation used?
              </h3>
              <p class="text-gray-600">
                100% of your donation goes directly to our programs. Administrative costs 
                are covered by separate grants and fundraising efforts.
              </p>
            </div>

            <div class="border-b border-gray-200 pb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                Is my payment secure?
              </h3>
              <p class="text-gray-600">
                Absolutely. All transactions are processed securely through PayPal, 
                and we never store your payment information.
              </p>
            </div>

            <div class="border-b border-gray-200 pb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                Can I make a recurring donation?
              </h3>
              <p class="text-gray-600">
                Yes! During the PayPal checkout, you can choose to make your donation 
                monthly to provide sustained support.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 bg-gradient-to-r from-primary-green to-primary-dark text-white">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Have Questions?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto text-white/90">
          Our team is here to help. Contact us for more information about donations, 
          partnerships, or volunteering opportunities.
        </p>
        <Button variant="secondary" size="lg" href="/contact">
          Contact Us
        </Button>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';

const suggestedAmounts = [
  { value: 10, impact: 'Feeds 2 families' },
  { value: 25, impact: 'Feeds 5 families' },
  { value: 50, impact: 'Feeds 10 families' },
  { value: 100, impact: 'School supplies' }
];

const selectedAmount = ref(null);
const customAmount = ref(null);
const donorInfo = ref({
  name: '',
  email: ''
});

const finalAmount = computed(() => {
  return selectedAmount.value || customAmount.value || 0;
});

const proceedToPayPal = () => {
  if (finalAmount.value === 0) return;

  // Store donor info in sessionStorage for return URL
  if (donorInfo.value.name || donorInfo.value.email) {
    sessionStorage.setItem('donor_info', JSON.stringify(donorInfo.value));
  }
  
  // Store amount for potential tracking
  sessionStorage.setItem('donation_amount', finalAmount.value.toString());

  // Use your hosted PayPal button with custom return URLs
  const paypalParams = new URLSearchParams({
    cmd: '_s-xclick',
    hosted_button_id: '7BDJM7FP95CMC',
    return: `${window.location.origin}/donate/thank-you`,
    cancel_return: `${window.location.origin}/donate`,
    // Pass custom data (donor info + amount for reference)
    custom: JSON.stringify({
      donor_name: donorInfo.value.name,
      donor_email: donorInfo.value.email,
      suggested_amount: finalAmount.value,
      timestamp: new Date().toISOString()
    })
  });

  // Redirect to PayPal (your hosted button is already on live)
  window.location.href = `https://www.paypal.com/cgi-bin/webscr?${paypalParams.toString()}`;
};
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}
</style>
