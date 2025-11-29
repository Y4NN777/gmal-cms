<template>
  <AppLayout>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
      <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $t('contact.title') }}</h1>
        <p class="text-xl text-orange-100">{{ $t('contact.subtitle') }}</p>
      </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="py-16">
      <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-12 max-w-6xl mx-auto">
          <!-- Contact Form -->
          <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $t('contact.form.title') }}</h2>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('contact.form.name') }} *
                </label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': form.errors.name }"
                  placeholder="John Doe"
                />
                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('contact.form.email') }} *
                </label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': form.errors.email }"
                  placeholder="john@example.com"
                />
                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
              </div>

              <!-- Phone -->
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('contact.form.phone') }}
                </label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': form.errors.phone }"
                  placeholder="+1 (555) 123-4567"
                />
                <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
              </div>

              <!-- Subject -->
              <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('contact.form.subject') }} *
                </label>
                <input
                  id="subject"
                  v-model="form.subject"
                  type="text"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': form.errors.subject }"
                  placeholder="How can we help?"
                />
                <p v-if="form.errors.subject" class="mt-1 text-sm text-red-600">{{ form.errors.subject }}</p>
              </div>

              <!-- Message -->
              <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                  {{ $t('contact.form.message') }} *
                </label>
                <textarea
                  id="message"
                  v-model="form.message"
                  required
                  rows="5"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                  :class="{ 'border-red-500': form.errors.message }"
                  placeholder="Tell us more about your inquiry..."
                ></textarea>
                <p v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</p>
              </div>

              <!-- Success Message -->
              <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ successMessage }}
              </div>

              <!-- Error Message -->
              <div v-if="form.errors.general" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ form.errors.general }}
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 px-6 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="!submitting">{{ $t('contact.form.submit') }}</span>
                <span v-else class="flex items-center justify-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ $t('contact.form.sending') }}
                </span>
              </button>
            </form>
          </div>

          <!-- Contact Info -->
          <div class="space-y-8">
            <div>
              <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $t('contact.info.title') }}</h2>
              <p class="text-gray-600 mb-8">
                {{ $t('contact.info.description') }}
              </p>
            </div>

            <!-- Contact Cards -->
            <div class="space-y-6">
              <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 mb-1">{{ $t('contact.info.email') }}</h3>
                  <a href="mailto:info@givemealift.org" class="text-orange-600 hover:text-orange-700">
                    info@givemealift.org
                  </a>
                </div>
              </div>

              <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 mb-1">{{ $t('contact.info.phone') }}</h3>
                  <a href="tel:+15551234567" class="text-green-600 hover:text-green-700">
                    +1 (555) 123-4567
                  </a>
                </div>
              </div>

              <div class="flex items-start gap-4 p-6 bg-gray-50 rounded-lg">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900 mb-1">{{ $t('contact.info.address') }}</h3>
                  <p class="text-gray-600">
                    {{ $t('contact.address.street') }}<br>
                    {{ $t('contact.address.city') }}<br>
                    {{ $t('contact.address.country') }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Office Hours -->
            <div class="bg-gradient-to-br from-orange-50 to-green-50 p-6 rounded-lg">
              <h3 class="font-semibold text-gray-900 mb-4">{{ $t('contact.info.hours') }}</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                  <span>{{ $t('contact.hours.weekdays') }}:</span>
                  <span class="font-medium text-gray-900">{{ $t('contact.hours.weekdaysTime') }}</span>
                </div>
                <div class="flex justify-between">
                  <span>{{ $t('contact.hours.saturday') }}:</span>
                  <span class="font-medium text-gray-900">{{ $t('contact.hours.saturdayTime') }}</span>
                </div>
                <div class="flex justify-between">
                  <span>{{ $t('contact.hours.sunday') }}:</span>
                  <span class="font-medium text-gray-900">{{ $t('contact.hours.closed') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

const form = useForm({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
});

const successMessage = ref('');

const submitForm = () => {
  form.post('/contact', {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = '';
      form.reset();
      
      // Auto-hide success message after 5 seconds
      setTimeout(() => {
        successMessage.value = '';
      }, 5000);
    },
  });
};
</script>
