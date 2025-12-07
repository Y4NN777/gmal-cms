<template>
  <section class="relative h-screen min-h-[800px] overflow-hidden bg-[#252A34] group">
    <swiper
      :modules="modules"
      :effect="'fade'"
      :speed="1500"
      :autoplay="{
        delay: 6000,
        disableOnInteraction: false,
      }"
      :loop="true"
      class="h-full w-full"
    >
      <swiper-slide v-for="(slide, index) in slides" :key="index" class="relative h-full w-full">
        <!-- Background Image -->
        <div class="absolute inset-0">
          <img 
            :src="slide.image" 
            :alt="slide.title" 
            class="w-full h-full object-cover"
          />
        </div>
        
        <!-- Mixing Layer Effect -->
        <div class="absolute inset-0 bg-[#252A34] mix-blend-multiply opacity-40 transition-opacity duration-700 group-hover:opacity-60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#252A34] via-transparent to-[#252A34]/30 mix-blend-soft-light"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#EE9446]/30 to-transparent mix-blend-overlay"></div>
        
        <!-- Content -->
        <div class="relative z-10 h-full flex items-center justify-center container mx-auto px-4">
          <div class="max-w-5xl mx-auto text-center">
            <!-- Subheading -->
            <p 
              class="text-[#EE9446] font-sans font-bold text-sm md:text-base uppercase tracking-[0.2em] mb-6"
            >
              {{ slide.subtitle }}
            </p>
            
            <!-- Main Heading -->
            <h1 
              class="text-white font-display font-bold text-5xl md:text-7xl lg:text-[90px] leading-[1.1] mb-8 drop-shadow-lg"
            >
              <span v-html="slide.title"></span>
            </h1>
            
            <!-- Description -->
            <p 
              class="text-gray-100 font-sans text-lg md:text-2xl leading-relaxed mb-12 max-w-3xl mx-auto drop-shadow-md"
            >
              {{ slide.description }}
            </p>
            
            <!-- CTA Buttons -->
            <div 
              class="flex flex-col sm:flex-row gap-6 justify-center"
            >
              <Link 
                href="/donate" 
                class="px-10 py-4 bg-[#EE9446] hover:bg-[#E17111] text-white rounded-full font-sans font-bold text-sm uppercase tracking-widest transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg hover:shadow-[#EE9446]/30"
              >
                {{ $t('home.hero.donateNow') }}
              </Link>
              
              <Link opacity
                href="/about" 
                class="px-10 py-4 bg-transparent border-2 border-white hover:bg-white hover:text-[#252A34] text-white rounded-full font-sans font-bold text-sm uppercase tracking-widest transition-all duration-300 transform hover:-translate-y-1"
              >
                {{ $t('home.hero.learnMore') }}
              </Link>
            </div>
          </div>
        </div>
      </swiper-slide>
    </swiper>
  </section>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectFade, Navigation, Pagination } from 'swiper/modules';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const modules = [Autoplay, EffectFade, Navigation, Pagination];
const page = usePage();
const assetUrl = page.props.assetUrl || '';
const { t } = useI18n();

const slides = computed(() => [
  {
    image: `${assetUrl}/images/home.jpg`,
    subtitle: t('home.hero.slides.1.subtitle'),
    title: t('home.hero.slides.1.title'),
    description: t('home.hero.slides.1.description')
  },
  {
    image: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1600&auto=format&fit=crop',
    subtitle: t('home.hero.slides.2.subtitle'),
    title: t('home.hero.slides.2.title'),
    description: t('home.hero.slides.2.description')
  },
  {
    image: 'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?q=80&w=1600&auto=format&fit=crop',
    subtitle: t('home.hero.slides.3.subtitle'),
    title: t('home.hero.slides.3.title'),
    description: t('home.hero.slides.3.description')
  }
]);
</script>

<style scoped>
/* No animations */
</style>
