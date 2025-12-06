<template>
  <header 
    class="sticky top-0 z-50 transition-all duration-300"
    :class="[
      isScrolled ? 'bg-white/95 backdrop-blur-md shadow-md py-3' : 'bg-white py-5'
    ]"
  >
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
          <Link href="/" class="flex items-center gap-3 group">
            <div class="h-12 w-12 rounded-xl overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow-md transition-all duration-300">
              <img :src="logoImg" alt="GiveMeALift" class="h-full w-full object-cover">
            </div>
            <span class="text-2xl font-display font-bold text-[#252A34] tracking-tight group-hover:text-[#EE9446] transition-colors">
              GiveMeALift
            </span>
          </Link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center gap-8">
          <div class="flex items-center gap-6">
            <Link 
              v-for="item in navItems" 
              :key="item.href"
              :href="item.href" 
              class="relative font-sans font-semibold text-sm uppercase tracking-wider text-[#555555] hover:text-[#EE9446] transition-colors py-2 group"
              :class="{ 'text-[#EE9446]': isActive(item.href) }"
            >
              {{ $t(item.label) }}
              <span 
                class="absolute bottom-0 left-0 w-0 h-0.5 bg-[#EE9446] transition-all duration-300 group-hover:w-full"
                :class="{ 'w-full': isActive(item.href) }"
              ></span>
            </Link>
          </div>
          
          <div class="h-6 w-px bg-gray-200 mx-2"></div>

          <!-- Language Switcher -->
          <LanguageSwitcher />
          
          <!-- Donate Button -->
          <Link 
            href="/donate" 
            class="px-8 py-3 bg-[#EE9446] hover:bg-[#E17111] text-white rounded-full font-sans font-bold text-sm uppercase tracking-widest shadow-lg hover:shadow-xl hover:shadow-[#EE9446]/30 transition-all duration-300 transform hover:-translate-y-0.5"
          >
            {{ $t('nav.donate') }}
          </Link>
        </div>

        <!-- Mobile Menu Button -->
        <button 
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="lg:hidden p-2 rounded-lg text-[#252A34] hover:bg-gray-100 transition-colors focus:outline-none"
          aria-label="Toggle menu"
        >
          <div class="w-6 h-5 relative flex flex-col justify-between">
            <span 
              class="w-full h-0.5 bg-current rounded-full transition-all duration-300 origin-left"
              :class="{ 'rotate-45 translate-x-px': mobileMenuOpen }"
            ></span>
            <span 
              class="w-full h-0.5 bg-current rounded-full transition-all duration-300"
              :class="{ 'opacity-0': mobileMenuOpen }"
            ></span>
            <span 
              class="w-full h-0.5 bg-current rounded-full transition-all duration-300 origin-left"
              :class="{ '-rotate-45 translate-x-px': mobileMenuOpen }"
            ></span>
          </div>
        </button>
      </div>

      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="transform -translate-y-4 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-4 opacity-0"
      >
        <div 
          v-show="mobileMenuOpen"
          class="lg:hidden mt-4 py-4 border-t border-gray-100 bg-white absolute left-0 right-0 px-4 shadow-lg z-40"
        >
          <div class="flex flex-col space-y-2">
            <Link 
              v-for="item in navItems" 
              :key="item.href"
              :href="item.href" 
              class="px-4 py-3 rounded-lg font-sans font-semibold text-[#555555] hover:bg-[#FEF5EE] hover:text-[#EE9446] transition-colors"
              :class="{ 'bg-[#FEF5EE] text-[#EE9446]': isActive(item.href) }"
              @click="mobileMenuOpen = false"
            >
              {{ $t(item.label) }}
            </Link>
            
            <div class="px-4 py-3 border-t border-gray-100 mt-2">
              <LanguageSwitcher />
            </div>
            
            <Link 
              href="/donate" 
              class="mt-4 w-full block text-center px-6 py-3 bg-[#EE9446] text-white rounded-lg font-sans font-bold uppercase tracking-wide shadow-md"
              @click="mobileMenuOpen = false"
            >
              {{ $t('nav.donate') }}
            </Link>
          </div>
        </div>
      </transition>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import logoImg from '@/assets/favicon.png';

const mobileMenuOpen = ref(false);
const isScrolled = ref(false);
const page = usePage();

const navItems = [
  { href: '/', label: 'nav.home' },
  { href: '/about', label: 'nav.about' },
  { href: '/events', label: 'nav.events' },
  { href: '/gallery', label: 'nav.gallery' },
  { href: '/contact', label: 'nav.contact' },
];

const isActive = (href) => {
  if (href === '/') {
    return page.url === '/';
  }
  return page.url.startsWith(href);
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>
