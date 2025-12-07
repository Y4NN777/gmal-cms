<template>
  <div>
    <Head title="Testimonial Details" />
    
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <!-- Header with Back Button -->
      <div class="mb-6 flex items-center justify-between">
        <Link 
          :href="route('admin.testimonials.index', undefined, undefined, Ziggy)" 
          class="inline-flex items-center text-gray-600 hover:text-gray-900 transition font-medium"
        >
          <ArrowLeft :size="18" class="mr-2" />
          {{ $t('testimonials.backToTestimonials') }}
        </Link>
        
        <!-- Status Badge -->
        <span 
          class="px-3 py-1 rounded-full text-sm font-semibold"
          :class="{
            'bg-green-100 text-green-800': testimonial.status === 'approved',
            'bg-yellow-100 text-yellow-800': testimonial.status === 'pending',
            'bg-red-100 text-red-800': testimonial.status === 'rejected'
          }"
        >
          {{ testimonial.status.charAt(0).toUpperCase() + testimonial.status.slice(1) }}
        </span>
      </div>

      <!-- Main Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header with Avatar -->
        <div class="bg-gradient-to-r from-primary-green to-green-600 p-8 text-white">
          <div class="flex items-center space-x-4">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-3xl font-bold text-primary-green overflow-hidden">
              <img 
                v-if="testimonial.avatar"
                :src="testimonial.avatar.url" 
                :alt="testimonial.avatar.alt"
                class="w-full h-full object-cover"
              />
              <span v-else>{{ testimonial.name.charAt(0) }}</span>
            </div>
            <div>
              <h1 class="text-3xl font-bold">{{ testimonial.name }}</h1>
              <p class="text-green-50">{{ testimonial.position }}</p>
              <p v-if="testimonial.organization" class="text-green-100 text-sm">{{ testimonial.organization }}</p>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-8 space-y-6">
          <!-- Rating -->
          <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ $t('testimonials.rating') }}</h3>
            <div class="flex items-center space-x-1">
              <Star 
                v-for="star in 5" 
                :key="star"
                :size="20"
                :class="star <= testimonial.rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
              />
              <span class="ml-2 text-gray-600">({{ testimonial.rating }}/5)</span>
            </div>
          </div>

          <!-- Message -->
          <div>
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">{{ $t('testimonials.message') }}</h3>
            <p class="text-gray-800 text-lg leading-relaxed">{{ testimonial.content }}</p>
          </div>

          <!-- Metadata -->
          <div class="grid grid-cols-2 gap-4 pt-4 border-t">
            <div>
              <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">{{ $t('testimonials.form.email') }}</h3>
              <p class="text-gray-800">{{ testimonial.email || 'Not provided' }}</p>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">{{ $t('testimonials.submitted') }}</h3>
              <p class="text-gray-800">{{ formatDate(testimonial.created_at) }}</p>
            </div>
            <div v-if="testimonial.approver">
              <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">{{ $t('testimonials.approvedBy') }}</h3>
              <p class="text-gray-800">{{ testimonial.approver.name }}</p>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">{{ $t('testimonials.displayOrder') }}</h3>
              <p class="text-gray-800">{{ testimonial.display_order || 0 }}</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-8 py-4 flex flex-wrap gap-3">
          <!-- Approve Button -->
          <button
            v-if="testimonial.status !== 'approved'"
            @click="approveTestimonial"
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition"
          >
            <Check :size="18" class="mr-2" />
            {{ $t('testimonials.approve') }}
          </button>

          <!-- Reject Button -->
          <button
            v-if="testimonial.status !== 'rejected'"
            @click="rejectTestimonial"
            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition"
          >
            <X :size="18" class="mr-2" />
            {{ $t('testimonials.reject') }}
          </button>

          <!-- Toggle Feature Button -->
          <button
            @click="toggleFeature"
            class="inline-flex items-center px-4 py-2 font-semibold rounded-lg transition"
            :class="testimonial.is_featured 
              ? 'bg-yellow-500 hover:bg-yellow-600 text-white' 
              : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
          >
            <Star :size="18" class="mr-2" :class="testimonial.is_featured ? 'fill-white' : ''" />
            {{ testimonial.is_featured ? $t('testimonials.unfeature') : $t('testimonials.feature') }}
          </button>

          <!-- Edit Button -->
          <Link
            :href="route('admin.testimonials.edit', testimonial.id, undefined, Ziggy)"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition"
          >
            <Edit :size="18" class="mr-2" />
            {{ $t('testimonials.edit') }}
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Ziggy } from '../../../ziggy';
import { ArrowLeft, Star, Check, X, Edit } from 'lucide-vue-next';

const props = defineProps({
  testimonial: Object
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const approveTestimonial = () => {
  router.post(route('admin.testimonials.approve', props.testimonial.id, undefined, Ziggy));
};

const rejectTestimonial = () => {
  router.post(route('admin.testimonials.reject', props.testimonial.id, undefined, Ziggy));
};

const toggleFeature = () => {
  router.post(route('admin.testimonials.toggle-feature', props.testimonial.id, undefined, Ziggy));
};
</script>
