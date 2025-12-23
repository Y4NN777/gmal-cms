<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="relative h-[60vh] min-h-[500px] overflow-hidden bg-[#252A34]">
      <div class="absolute inset-0">
        <img 
          :src="`${$page.props.assetUrl || ''}/images/home/hero-background.png`" 
          alt="Donate Hero" 
          class="w-full h-full object-cover opacity-40"
          @error="$event.target.style.display='none'"
        />
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-[#EE9446]/90 to-[#E17111]/80 mix-blend-multiply"></div>
      
      <div class="relative z-10 container mx-auto px-4 h-full flex items-center justify-center text-center">
        <div class="max-w-4xl">
          <div class="inline-block mb-6 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
            <span class="px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-white font-sans text-sm font-bold uppercase tracking-widest">
              Your Support Matters
            </span>
          </div>
          <h1 
            class="text-white font-display font-bold text-5xl md:text-7xl leading-tight mb-8 opacity-0 animate-fade-in-up"
            style="animation-delay: 0.4s; animation-fill-mode: forwards;"
          >
            Make a Difference <span class="text-white border-b-4 border-white/30">Today</span>
          </h1>
          <p 
            class="text-white/90 font-sans text-lg md:text-2xl leading-relaxed max-w-2xl mx-auto opacity-0 animate-fade-in-up"
            style="animation-delay: 0.6s; animation-fill-mode: forwards;"
          >
            Your generous donation helps us transform lives and build stronger communities. Every contribution creates lasting impact.
          </p>
        </div>
      </div>
    </section>

    <!-- Donation Form Section -->
    <section class="relative py-24 bg-[#F8F9FA] -mt-20">
      <div class="container mx-auto px-4 relative z-20">
        <div class="max-w-5xl mx-auto">
          <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid lg:grid-cols-3">
              <!-- Sidebar Info -->
              <div class="bg-[#252A34] p-10 text-white lg:col-span-1 flex flex-col justify-between">
                <div>
                  <h3 class="font-display font-bold text-2xl mb-6">Why Donate?</h3>
                  <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                      <div class="w-10 h-10 rounded-full bg-[#EE9446]/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#EE9446]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                      </div>
                      <div>
                        <h4 class="font-bold text-lg mb-1">100% Direct Impact</h4>
                        <p class="text-gray-400 text-sm">Every dollar goes directly to our programs.</p>
                      </div>
                    </li>
                    <li class="flex items-start gap-4">
                      <div class="w-10 h-10 rounded-full bg-[#219D80]/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#219D80]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      </div>
                      <div>
                        <h4 class="font-bold text-lg mb-1">Tax Deductible</h4>
                        <p class="text-gray-400 text-sm">We are a registered 501(c)(3) nonprofit.</p>
                      </div>
                    </li>
                    <li class="flex items-start gap-4">
                      <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                      </div>
                      <div>
                        <h4 class="font-bold text-lg mb-1">Secure Payment</h4>
                        <p class="text-gray-400 text-sm">Processed securely via PayPal.</p>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="mt-10 pt-10 border-t border-white/10">
                  <p class="text-sm text-gray-400 italic">"The best way to find yourself is to lose yourself in the service of others."</p>
                </div>
              </div>

              <!-- Main Form -->
              <div class="p-10 lg:col-span-2">
                <h2 class="font-display font-bold text-3xl text-[#252A34] mb-8 text-center">Choose Your Donation Amount</h2>

                <!-- Suggested Amounts -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                  <button
                    v-for="amount in suggestedAmounts"
                    :key="amount.value"
                    @click="selectedAmount = amount.value; customAmount = null"
                    class="relative p-6 rounded-xl border-2 transition-all duration-300 text-center group overflow-hidden"
                    :class="[
                      selectedAmount === amount.value
                        ? 'border-[#EE9446] bg-[#EE9446] text-white shadow-lg scale-105'
                        : 'border-gray-100 bg-white hover:border-[#EE9446] hover:shadow-md'
                    ]"
                  >
                    <div class="text-3xl font-bold mb-1 font-display">${{ amount.value }}</div>
                    <div class="text-xs font-sans uppercase tracking-wide" :class="selectedAmount === amount.value ? 'text-white/90' : 'text-gray-400'">{{ amount.label }}</div>
                  </button>
                </div>

                <!-- Custom Amount -->
                <div class="mb-10">
                  <label class="block text-[#555555] font-sans font-semibold mb-3 text-sm uppercase tracking-wide">
                    Or enter a custom amount
                  </label>
                  <div class="relative">
                    <span class="absolute left-6 top-1/2 transform -translate-y-1/2 text-[#252A34] text-2xl font-bold font-display">$</span>
                    <input
                      v-model.number="customAmount"
                      type="number"
                      min="1"
                      step="1"
                      placeholder="Other Amount"
                      class="w-full pl-12 pr-6 py-4 text-xl font-bold text-[#252A34] bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all"
                      @input="selectedAmount = null"
                    />
                  </div>
                </div>

                <!-- Donor Info -->
                <div class="mb-10">
                  <h3 class="font-display font-bold text-xl text-[#252A34] mb-4">Your Information (Optional)</h3>
                  <div class="grid md:grid-cols-2 gap-4">
                    <input
                      v-model="donorInfo.name"
                      type="text"
                      placeholder="Full Name"
                      class="px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all"
                    />
                    <input
                      v-model="donorInfo.email"
                      type="email"
                      placeholder="Email Address"
                      class="px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all"
                    />
                  </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-8">
                  <h3 class="font-display font-bold text-xl text-[#252A34] mb-4">Payment Method</h3>
                  <div class="grid md:grid-cols-2 gap-4">
                    <!-- PayPal Option -->
                    <button
                      @click="paymentMethod = 'paypal'"
                      class="p-6 rounded-xl border-2 transition-all duration-300 text-left"
                      :class="[
                        paymentMethod === 'paypal'
                          ? 'border-[#0070BA] bg-[#0070BA]/5 shadow-lg'
                          : 'border-gray-100 hover:border-gray-200'
                      ]"
                    >
                      <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#0070BA]/10 flex items-center justify-center">
                          <svg class="w-6 h-6 text-[#0070BA]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.72a.77.77 0 0 1 .76-.633h8.78c2.93 0 4.954 1.607 4.954 4.42 0 3.903-3.244 6.294-7.38 6.294H9.53l-1.698 7.536a.64.64 0 0 1-.756.5zm10.117-11.24c2.93 0 4.954 1.607 4.954 4.42 0 3.903-3.244 6.294-7.38 6.294h-2.528l-1.698 7.536a.64.64 0 0 1-.756.5H5.18a.641.641 0 0 1-.633-.74l3.107-16.877a.77.77 0 0 1 .76-.633h8.78z"/>
                          </svg>
                        </div>
                        <div>
                          <div class="font-bold text-[#252A34]">PayPal</div>
                          <div class="text-sm text-gray-500">Credit/Debit Card</div>
                        </div>
                      </div>
                    </button>

                    <!-- Mobile Money Option -->
                    <button
                      @click="paymentMethod = 'mobile_money'"
                      class="p-6 rounded-xl border-2 transition-all duration-300 text-left"
                      :class="[
                        paymentMethod === 'mobile_money'
                          ? 'border-[#EE9446] bg-[#EE9446]/5 shadow-lg'
                          : 'border-gray-100 hover:border-gray-200'
                      ]"
                    >
                      <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#EE9446]/10 flex items-center justify-center">
                          <svg class="w-6 h-6 text-[#EE9446]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                          </svg>
                        </div>
                        <div>
                          <div class="font-bold text-[#252A34]">Mobile Money</div>
                          <div class="text-sm text-gray-500">Orange, Moov</div>
                        </div>
                      </div>
                    </button>
                  </div>
                </div>

                <!-- Mobile Money Form (shown when selected) -->
                <div v-if="paymentMethod === 'mobile_money'" class="mb-8 space-y-4">
                  <!-- Country Selection -->
                  <div>
                    <label class="block text-[#555555] font-sans font-semibold mb-2 text-sm">Country</label>
                    <select
                      v-model="mobileMoneyData.country"
                      @change="loadOperators"
                      class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all"
                    >
                      <option value="">Select Country</option>
                      <option value="BF">🇧🇫 Burkina Faso</option>
                      <option value="CI">🇨🇮 Côte d'Ivoire</option>
                    </select>
                  </div>

                  <!-- Operator Selection -->
                  <div v-if="mobileMoneyData.country">
                    <label class="block text-[#555555] font-sans font-semibold mb-2 text-sm">Mobile Money Operator</label>
                    <div class="grid grid-cols-2 gap-4">
                      <button
                        v-for="operator in availableOperators"
                        :key="operator.code"
                        @click="mobileMoneyData.operator = operator.code"
                        class="p-4 rounded-xl border-2 transition-all duration-300"
                        :class="[
                          mobileMoneyData.operator === operator.code
                            ? 'border-[#EE9446] bg-[#EE9446]/5'
                            : 'border-gray-100 hover:border-gray-200'
                        ]"
                      >
                        <div class="font-bold text-[#252A34]">{{ operator.name }}</div>
                      </button>
                    </div>
                  </div>

                  <!-- Phone Number -->
                  <div v-if="mobileMoneyData.operator">
                    <label class="block text-[#555555] font-sans font-semibold mb-2 text-sm">Phone Number</label>
                    <input
                      v-model="mobileMoneyData.phone"
                      type="tel"
                      :placeholder="phoneFormat"
                      class="w-full px-6 py-4 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all"
                    />
                    <p class="text-xs text-gray-500 mt-2">Enter your mobile money number</p>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                  <button
                    v-if="paymentMethod === 'paypal'"
                    @click="proceedToPayPal"
                    class="w-full md:w-auto px-12 py-5 bg-[#EE9446] hover:bg-[#E17111] text-white rounded-xl font-sans font-bold text-lg uppercase tracking-widest shadow-xl hover:shadow-2xl hover:shadow-[#EE9446]/30 transition-all duration-300 transform hover:-translate-y-1"
                  >
                    <span v-if="finalAmount > 0">
                      Donate ${{ finalAmount }} via PayPal
                    </span>
                    <span v-else>
                      Donate via PayPal
                    </span>
                  </button>

                  <button
                    v-else-if="paymentMethod === 'mobile_money'"
                    @click="initiateMobileMoneyPayment"
                    :disabled="!canProcessMobileMoney || isProcessing"
                    class="w-full md:w-auto px-12 py-5 bg-[#EE9446] hover:bg-[#E17111] disabled:bg-gray-300 disabled:cursor-not-allowed text-white rounded-xl font-sans font-bold text-lg uppercase tracking-widest shadow-xl hover:shadow-2xl hover:shadow-[#EE9446]/30 transition-all duration-300 transform hover:-translate-y-1 disabled:transform-none"
                  >
                    <span v-if="isProcessing">Processing...</span>
                    <span v-else-if="finalAmount > 0">
                      Pay {{ convertedAmount }} XOF via Mobile Money
                    </span>
                    <span v-else>
                      Pay via Mobile Money
                    </span>
                  </button>

                  <p class="text-xs text-gray-400 mt-4 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Secure SSL Encrypted Transaction
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- OTP Modal -->
    <div
      v-if="showOtpModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="showOtpModal = false"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 animate-fade-in-up">
        <h3 class="font-display font-bold text-2xl text-[#252A34] mb-4">Enter OTP Code</h3>
        <p class="text-gray-600 mb-6">
          An OTP code has been sent to your phone <span class="font-bold">{{ mobileMoneyData.phone }}</span>
        </p>
        
        <input
          v-model="otpCode"
          type="text"
          maxlength="6"
          placeholder="000000"
          class="w-full px-6 py-4 text-center text-2xl font-bold tracking-widest bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#EE9446] focus:bg-white focus:outline-none transition-all mb-6"
          @keyup.enter="confirmPayment"
        />

        <div class="flex gap-4">
          <button
            @click="showOtpModal = false"
            class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-all"
          >
            Cancel
          </button>
          <button
            @click="confirmPayment"
            :disabled="otpCode.length !== 6 || isProcessing"
            class="flex-1 px-6 py-3 bg-[#EE9446] hover:bg-[#E17111] disabled:bg-gray-300 text-white rounded-xl font-bold transition-all disabled:cursor-not-allowed"
          >
            <span v-if="isProcessing">Confirming...</span>
            <span v-else>Confirm</span>
          </button>
        </div>
      </div>
    </div>
  </section>

    <!-- Impact Examples -->
    <section class="py-24 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-16">
          <h2 class="font-display font-bold text-4xl text-[#252A34] mb-4">Your Impact in Action</h2>
          <p class="text-[#555555] text-lg">See exactly what your donation can achieve.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 text-center group">
            <div class="w-20 h-20 bg-[#EE9446]/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-[#EE9446] transition-colors duration-300">
              <svg class="w-10 h-10 text-[#EE9446] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
            <h3 class="font-display font-bold text-2xl text-[#252A34] mb-2">$50</h3>
            <p class="text-[#555555] font-sans">Provides nutritious meals for 10 families in need for a week.</p>
          </div>

          <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 text-center group">
            <div class="w-20 h-20 bg-[#219D80]/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-[#219D80] transition-colors duration-300">
              <svg class="w-10 h-10 text-[#219D80] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <h3 class="font-display font-bold text-2xl text-[#252A34] mb-2">$100</h3>
            <p class="text-[#555555] font-sans">Provides essential school supplies for 20 students for a full year.</p>
          </div>

          <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 text-center group">
            <div class="w-20 h-20 bg-[#252A34]/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-[#252A34] transition-colors duration-300">
              <svg class="w-10 h-10 text-[#252A34] group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
              </svg>
            </div>
            <h3 class="font-display font-bold text-2xl text-[#252A34] mb-2">$200</h3>
            <p class="text-[#555555] font-sans">Provides basic medical care and checkups for families without insurance.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 bg-[#F8F9FA]">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
          <h2 class="font-display font-bold text-3xl text-center text-[#252A34] mb-12">Frequently Asked Questions</h2>
          
          <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="font-bold text-lg text-[#252A34] mb-2">Is my donation tax-deductible?</h3>
              <p class="text-[#555555]">Yes! GiveMeALift is a registered 501(c)(3) nonprofit organization. You will receive a tax receipt via email after your donation.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="font-bold text-lg text-[#252A34] mb-2">How is my donation used?</h3>
              <p class="text-[#555555]">100% of your donation goes directly to our programs. Administrative costs are covered by separate grants and fundraising efforts.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="font-bold text-lg text-[#252A34] mb-2">Is my payment secure?</h3>
              <p class="text-[#555555]">Absolutely. All transactions are processed securely through PayPal, and we never store your payment information.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

const suggestedAmounts = [
  { value: 10, label: 'Feed' },
  { value: 25, label: 'Support' },
  { value: 50, label: 'Empower' },
  { value: 100, label: 'Transform' }
];

const selectedAmount = ref(50);
const customAmount = ref(null);
const donorInfo = ref({
  name: '',
  email: ''
});

const paymentMethod = ref('paypal');
const mobileMoneyData = ref({
  country: '',
  operator: '',
  phone: ''
});

const availableOperators = ref([]);
const showOtpModal = ref(false);
const otpCode = ref('');
const currentDonationId = ref(null);
const isProcessing = ref(false);

const finalAmount = computed(() => {
  return customAmount.value || selectedAmount.value || 0;
});

const convertedAmount = computed(() => {
  return Math.round(finalAmount.value * 600); // 1 USD = 600 XOF
});

const phoneFormat = computed(() => {
  const formats = {
    'BF': '+226 XX XX XX XX',
    'CI': '+225 XX XX XX XX XX'
  };
  return formats[mobileMoneyData.value.country] || 'Phone Number';
});

const canProcessMobileMoney = computed(() => {
  return finalAmount.value > 0 &&
         mobileMoneyData.value.country &&
         mobileMoneyData.value.operator &&
         mobileMoneyData.value.phone;
});

const loadOperators = async () => {
  if (!mobileMoneyData.value.country) return;
  
  try {
    const response = await axios.get(`/donate/ligdicash/operators?country=${mobileMoneyData.value.country}`);
    if (response.data.success) {
      availableOperators.value = response.data.operators;
    }
  } catch (error) {
    console.error('Failed to load operators:', error);
    alert('Failed to load mobile money operators. Please try again.');
  }
};

const initiateMobileMoneyPayment = async () => {
  if (!canProcessMobileMoney.value || isProcessing.value) return;
  
  isProcessing.value = true;
  
  try {
    const response = await axios.post('/donate/ligdicash/initiate', {
      amount: finalAmount.value,
      phone: mobileMoneyData.value.phone,
      operator_code: mobileMoneyData.value.operator,
      country_code: mobileMoneyData.value.country,
      donor_name: donorInfo.value.name || 'Anonymous',
      donor_email: donorInfo.value.email || null
    });
    
    if (response.data.success) {
      currentDonationId.value = response.data.donation_id;
      showOtpModal.value = true;
    } else {
      alert(response.data.error || 'Failed to initiate payment. Please try again.');
    }
  } catch (error) {
    console.error('Payment initiation failed:', error);
    alert(error.response?.data?.error || 'Failed to initiate payment. Please try again.');
  } finally {
    isProcessing.value = false;
  }
};

const confirmPayment = async () => {
  if (otpCode.value.length !== 6 || isProcessing.value) return;
  
  isProcessing.value = true;
  
  try {
    const response = await axios.post('/donate/ligdicash/confirm', {
      otp: otpCode.value,
      donation_id: currentDonationId.value
    });
    
    if (response.data.success) {
      // Payment successful - redirect to thank you page
      router.visit('/donate/thank-you', {
        method: 'get',
        data: {
          amount: finalAmount.value,
          currency: 'XOF',
          method: 'mobile_money'
        }
      });
    } else {
      alert(response.data.error || 'Invalid OTP. Please try again.');
      otpCode.value = '';
    }
  } catch (error) {
    console.error('Payment confirmation failed:', error);
    alert(error.response?.data?.error || 'Payment confirmation failed. Please try again.');
    otpCode.value = '';
  } finally {
    isProcessing.value = false;
  }
};

const proceedToPayPal = () => {
  if (finalAmount.value === 0) return;

  if (donorInfo.value.name || donorInfo.value.email) {
    sessionStorage.setItem('donor_info', JSON.stringify(donorInfo.value));
  }
  
  sessionStorage.setItem('donation_amount', finalAmount.value.toString());

  const paypalParams = new URLSearchParams({
    cmd: '_s-xclick',
    hosted_button_id: '7BDJM7FP95CMC',
    return: `${window.location.origin}/donate/thank-you`,
    cancel_return: `${window.location.origin}/donate`,
    custom: JSON.stringify({
      donor_name: donorInfo.value.name,
      donor_email: donorInfo.value.email,
      suggested_amount: finalAmount.value,
      timestamp: new Date().toISOString()
    })
  });

  window.location.href = `https://www.paypal.com/cgi-bin/webscr?${paypalParams.toString()}`;
};
</script>
