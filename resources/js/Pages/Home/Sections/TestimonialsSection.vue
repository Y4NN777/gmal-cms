<template>
  <section class="relative py-10 md:py-16 bg-gray-900 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary-orange/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-primary-green/10 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto mb-10">
        <div class="flex items-center justify-center gap-4 mb-4">
          <div class="h-0.5 w-12 bg-primary-orange"></div>
          <span class="text-primary-orange font-sans font-bold text-sm uppercase tracking-[0.2em]">
            {{ $t('footer.testimonials') }}
          </span>
          <div class="h-0.5 w-12 bg-primary-orange"></div>
        </div>
        
        <h2 class="font-display font-bold text-3xl md:text-4xl lg:text-5xl text-white leading-[1.1] mb-6" v-html="$t('home.testimonials.title')">
          </h2>

        <button 
          @click="isModalOpen = true"
          class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white font-sans font-bold text-sm uppercase tracking-wide transition-all duration-300 backdrop-blur-sm group"
        >
          <span>{{ $t('testimonials.shareYourStory') }}</span>
          <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </button>
      </div>
      
      <!-- Testimonial Slider -->
      <div class="max-w-4xl mx-auto relative group">
        <!-- Navigation Buttons -->
        <button class="swiper-button-prev-custom absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 md:-translate-x-12 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-primary-orange border border-white/20 hover:border-primary-orange text-white transition-all duration-300 flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        
        <button class="swiper-button-next-custom absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 md:translate-x-12 z-20 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-primary-orange border border-white/20 hover:border-primary-orange text-white transition-all duration-300 flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>

        <swiper
          :modules="modules"
          :slides-per-view="1"
          :space-between="30"
          :loop="true"
          :autoplay="{
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
          }"
          :pagination="{
            clickable: true,
            el: '.swiper-pagination-custom'
          }"
          :navigation="{
            nextEl: '.swiper-button-next-custom',
            prevEl: '.swiper-button-prev-custom'
          }"
          class="!pb-12 !pt-12"
        >
          <swiper-slide v-for="(testimonial, index) in displayTestimonials" :key="index">
            <div class="relative bg-white/5 backdrop-blur-md border border-white/10 rounded-[2rem] p-6 md:p-10 shadow-2xl mx-4 my-4">
              <!-- Quote Icon -->
              <div class="absolute -top-8 left-1/2 transform -translate-x-1/2">
                <div class="w-16 h-16 bg-gradient-to-br from-primary-orange to-orange-600 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30">
                  <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.77-.692-1.327-.817-.56-.124-1.074-.13-1.54-.022-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.165 1.4.615 2.52 1.35 3.35.732.833 1.646 1.25 2.742 1.25.967 0 1.768-.29 2.402-.876.627-.576.942-1.365.942-2.368v.01z"/>
                  </svg>
                </div>
              </div>
              
              <!-- Testimonial Text -->
              <blockquote class="text-center mb-8 mt-6">
                <p class="text-white/90 font-display font-medium text-xl md:text-2xl leading-relaxed italic">
                  "{{ testimonial.content }}"
                </p>
              </blockquote>
              
              <!-- Author -->
              <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full border-4 border-primary-orange shadow-xl mb-3 overflow-hidden bg-gray-800 flex items-center justify-center">
                  <img 
                    v-if="testimonial.avatar?.url || testimonial.image"
                    :src="testimonial.avatar?.url || `${$page.props.assetUrl || ''}/images/${testimonial.image}`" 
                    :alt="testimonial.name" 
                    class="w-full h-full object-cover"
                    @error="$event.target.style.display='none'"
                  />
                  <span v-else class="text-2xl font-bold text-white">{{ testimonial.name.charAt(0) }}</span>
                </div>
                <cite class="not-italic text-center">
                  <h4 class="text-white font-display font-bold text-xl mb-0.5">{{ testimonial.name }}</h4>
                  <span class="text-white/60 font-sans font-semibold text-xs uppercase tracking-widest">{{ testimonial.position }}</span>
                </cite>
              </div>
            </div>
          </swiper-slide>
          
          <!-- Custom Pagination -->
          <div class="swiper-pagination-custom flex justify-center gap-4 mt-6"></div>
        </swiper>
      </div>
    </div>

    <!-- Submission Modal -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900/80 transition-opacity" aria-hidden="true" @click="isModalOpen = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative z-50 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                <h3 class="text-2xl leading-6 font-display font-bold text-gray-900 mb-2" id="modal-title">
                  {{ $t('testimonials.submitModalTitle') }}
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                  {{ $t('testimonials.submitModalDescription') }}
                </p>

                <form @submit.prevent="submitTestimonial" class="space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.name') }}</label>
                    <input 
                      type="text" 
                      id="name" 
                      v-model="form.name"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-primary-orange outline-none transition-all"
                      required
                    >
                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                  </div>

                  <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.position') }}</label>
                    <input 
                      type="text" 
                      id="position" 
                      v-model="form.position"
                      :placeholder="$t('home.testimonials.positionPlaceholder')"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-primary-orange outline-none transition-all"
                      required
                    >
                    <div v-if="form.errors.position" class="text-red-500 text-xs mt-1">{{ form.errors.position }}</div>
                  </div>

                  <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.rating') }}</label>
                    <div class="flex gap-2">
                      <button 
                        v-for="star in 5" 
                        :key="star"
                        type="button"
                        @click="form.rating = star"
                        class="focus:outline-none transition-transform hover:scale-110"
                      >
                        <svg 
                          class="w-8 h-8" 
                          :class="star <= form.rating ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                          viewBox="0 0 24 24" 
                          stroke="currentColor" 
                          stroke-width="2"
                          fill="none"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                      </button>
                    </div>
                  </div>

                  <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.photo') }} ({{ $t('common.optional') }})</label>
                    <input 
                      type="file" 
                      id="photo" 
                      @input="form.photo = $event.target.files[0]"
                      accept="image/*"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-primary-orange outline-none transition-all text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-orange/10 file:text-primary-orange hover:file:bg-primary-orange/20"
                    >
                    <div v-if="form.errors.photo" class="text-red-500 text-xs mt-1">{{ form.errors.photo }}</div>
                  </div>

                  <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">{{ $t('testimonials.form.content') }}</label>
                    <textarea 
                      id="content" 
                      v-model="form.content"
                      rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-orange focus:border-primary-orange outline-none transition-all"
                      required
                    ></textarea>
                    <div v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              @click="submitTestimonial"
              :disabled="form.processing"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-orange text-base font-medium text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-orange sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              {{ form.processing ? $t('testimonials.form.submitting') : $t('testimonials.form.submit') }}
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-orange sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all"
              @click="isModalOpen = false"
            >
              {{ $t('common.cancel') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Ziggy } from '../../../ziggy';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

const props = defineProps({
  testimonials: {
    type: Array,
    default: () => []
  }
});

const modules = [Autoplay, Pagination, Navigation];
const isModalOpen = ref(false);

const form = useForm({
  name: '',
  position: '',
  content: '',
  rating: 5,
  photo: null,
});

const submitTestimonial = () => {
  form.post(route('testimonials.store', undefined, undefined, Ziggy), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      form.reset();
      isModalOpen.value = false;
      // Optional: Show success notification
    },
  });
};

const fallbackTestimonials = [
  {
    content: "Give Me A Lift is truly making a difference! Seeing how 100% of donations go directly to helping children in need gives me confidence that my support is changing lives. Their transparency and dedication are inspiring.",
    name: "Cameron Williamson",
    position: "Founder",
    image: "testimonials1.png"
  },
  {
    content: "The impact this organization has on the local community is visible and profound. Every child they help represents a brighter future for the entire region.",
    name: "Sarah Jenkins",
    position: "Community Leader",
    image: "testimonials2.png"
  },
  {
    content: "I've never seen an NGO so committed to transparency. Knowing exactly where my money goes makes all the difference in the world.",
    name: "Michael Chen",
    position: "Donor",
    image: "testimonials3.png"
  }
];

const displayTestimonials = computed(() => {
  return props.testimonials && props.testimonials.length > 0 
    ? props.testimonials 
    : fallbackTestimonials;
});
</script>

<style>
.swiper-pagination-custom .swiper-pagination-bullet {
  width: 10px;
  height: 10px;
  background: rgba(255, 255, 255, 0.2);
  opacity: 1;
  transition: all 0.3s ease;
}

.swiper-pagination-custom .swiper-pagination-bullet-active {
  background: #EE9446; /* primary-orange */
  transform: scale(1.2);
}
</style>
