<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Ziggy } from '../../../ziggy';
import { ArrowLeft, Save } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  testimonial: Object,
});

const form = useForm({
  name: props.testimonial.name,
  email: props.testimonial.email,
  position: props.testimonial.position,
  organization: props.testimonial.organization,
  content: props.testimonial.content,
  rating: props.testimonial.rating,
  status: props.testimonial.status,
  is_featured: Boolean(props.testimonial.is_featured),
  display_order: props.testimonial.display_order,
});

const submit = () => {
  form.put(route('admin.testimonials.update', props.testimonial.id, undefined, Ziggy), {
    preserveScroll: true,
  });
};
</script>

<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto py-8">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Link 
            :href="route('admin.testimonials.show', testimonial.id, undefined, Ziggy)" 
            class="inline-flex items-center text-gray-600 hover:text-gray-900 transition font-medium"
          >
            <ArrowLeft :size="20" class="mr-2" />
            {{ $t('testimonials.backToTestimonials') }}
          </Link>
          <h1 class="text-2xl font-bold text-gray-900">{{ $t('testimonials.editTestimonial') }}</h1>
        </div>
      </div>

      <!-- Form Card -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <form @submit.prevent="submit" class="p-8 space-y-6">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.name') }}</label>
              <input 
                v-model="form.name"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                required
              />
              <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.email') }}</label>
              <input 
                v-model="form.email"
                type="email" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
              <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
            </div>

            <!-- Position -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.position') }}</label>
              <input 
                v-model="form.position"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>

            <!-- Organization -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.organization') }}</label>
              <input 
                v-model="form.organization"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Content -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.content') }}</label>
            <textarea 
              v-model="form.content"
              rows="4"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              required
            ></textarea>
            <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">{{ form.errors.content }}</div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Rating -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.rating') }}</label>
              <select 
                v-model="form.rating"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              >
                <option :value="5">5 Stars</option>
                <option :value="4">4 Stars</option>
                <option :value="3">3 Stars</option>
                <option :value="2">2 Stars</option>
                <option :value="1">1 Star</option>
              </select>
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.status') }}</label>
              <select 
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              >
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

            <!-- Display Order -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.displayOrder') }}</label>
              <input 
                v-model="form.display_order"
                type="number" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
              />
            </div>
          </div>

          <!-- Featured Toggle -->
          <div class="flex items-center">
            <input 
              v-model="form.is_featured"
              type="checkbox" 
              id="is_featured"
              class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
            />
            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
              {{ $t('testimonials.featureOnHomepage') }}
            </label>
          </div>

          <!-- Actions -->
          <div class="flex justify-end pt-6 border-t">
            <button 
              type="submit" 
              :disabled="form.processing"
              class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition disabled:opacity-50"
            >
              <Save :size="20" class="mr-2" />
              {{ $t('testimonials.saveChanges') }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </AdminLayout>
</template>
