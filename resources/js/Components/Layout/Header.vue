<template>
  <header class="sticky top-0 z-50 bg-white shadow-md">
    <nav class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
          <Link href="/" class="flex items-center space-x-2">
            <div class="h-10 w-10 rounded-lg overflow-hidden flex-shrink-0">
              <img :src="logoImg" alt="GiveMeALift" class="h-full w-full object-cover">
            </div>
            <span class="text-2xl font-bold text-primary-dark">GiveMeALift</span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <Link 
            href="/" 
            class="nav-link"
            :class="{ 'active': $page.component === 'Home/Index' }"
          >
            {{ $t('nav.home') }}
          </Link>
          <Link 
            href="/about" 
            class="nav-link"
            :class="{ 'active': $page.component === 'About/Index' }"
          >
            {{ $t('nav.about') }}
          </Link>
          <Link 
            href="/events" 
            class="nav-link"
            :class="{ 'active': $page.component.startsWith('Events') }"
          >
            {{ $t('nav.events') }}
          </Link>
          <Link 
            href="/gallery" 
            class="nav-link"
            :class="{ 'active': $page.component.startsWith('Gallery') }"
          >
            {{ $t('nav.gallery') }}
          </Link>
          <Link 
            href="/contact" 
            class="nav-link"
            :class="{ 'active': $page.component === 'Contact/Index' }"
          >
            {{ $t('nav.contact') }}
          </Link>
          
          <!-- Language Switcher -->
          <LanguageSwitcher />
          
          <!-- Donate Button -->
          <Link 
            href="/donate" 
            class="btn-primary"
          >
            {{ $t('nav.donate') }}
          </Link>
        </div>

        <!-- Mobile Menu Button -->
        <button 
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="md:hidden p-2 rounded-lg hover:bg-gray-100"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path 
              v-if="!mobileMenuOpen"
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M4 6h16M4 12h16M4 18h16"
            />
            <path 
              v-else
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div 
        v-show="mobileMenuOpen"
        class="md:hidden mt-4 pb-4 border-t pt-4"
      >
        <div class="flex flex-col space-y-4">
          <Link href="/" class="mobile-nav-link">{{ $t('nav.home') }}</Link>
          <Link href="/about" class="mobile-nav-link">{{ $t('nav.about') }}</Link>
          <Link href="/events" class="mobile-nav-link">{{ $t('nav.events') }}</Link>
          <Link href="/gallery" class="mobile-nav-link">{{ $t('nav.gallery') }}</Link>
          <Link href="/contact" class="mobile-nav-link">{{ $t('nav.contact') }}</Link>
          <div class="pt-2 border-t">
            <LanguageSwitcher />
          </div>
          <Link href="/donate" class="btn-primary w-full text-center">{{ $t('nav.donate') }}</Link>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import logoImg from '@/assets/favicon.png';

const mobileMenuOpen = ref(false);
</script>

<style scoped>
.nav-link {
  color: rgb(55 65 81);
  font-weight: 500;
  transition: color 0.2s;
}

.nav-link:hover {
  color: var(--color-primary-orange);
}

.nav-link.active {
  color: var(--color-primary-orange);
}

.mobile-nav-link {
  color: rgb(55 65 81);
  font-weight: 500;
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  transition: color 0.2s;
}

.mobile-nav-link:hover {
  color: var(--color-primary-orange);
}

.btn-primary {
  background-color: var(--color-primary-orange);
  color: white;
  padding: 0.5rem 1.5rem;
  border-radius: 9999px;
  font-weight: 600;
  transition: background-color 0.2s;
}

.btn-primary:hover {
  background-color: #EA580C;
}
</style>
