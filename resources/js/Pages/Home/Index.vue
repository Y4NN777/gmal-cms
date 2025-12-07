<template>
  <AppLayout>
    <HeroSection />
    <AboutSection />
    <MissionSection />
    <StoriesSection />
    <GallerySection />
    <TestimonialsSection :testimonials="testimonials" />
    <ContactSection />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import HeroSection from './Sections/HeroSection.vue';
import AboutSection from './Sections/AboutSection.vue';
import MissionSection from './Sections/MissionSection.vue';
import StoriesSection from './Sections/StoriesSection.vue';
import GallerySection from './Sections/GallerySection.vue';
import TestimonialsSection from './Sections/TestimonialsSection.vue';
import ContactSection from './Sections/ContactSection.vue';

defineProps({
  featuredEvents: Array,
  testimonials: Array,
  recentGalleryImages: Array,
  stats: Object,
});
</script>

