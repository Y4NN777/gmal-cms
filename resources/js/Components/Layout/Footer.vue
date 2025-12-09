<template>
  <footer class="bg-[#252A34] text-white relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-[#EE9446]/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#219D80]/5 rounded-full blur-[100px] pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-20 pb-10">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-16">
        <!-- Brand Section -->
        <div class="space-y-6">
          <div class="flex items-center gap-3">
            <div class="h-12 w-12 rounded-xl overflow-hidden flex-shrink-0 bg-white/5 p-1">
              <img :src="siteLogo" :alt="siteName" class="h-full w-full object-cover rounded-lg">
            </div>
            <h3 class="text-2xl font-display font-bold text-white">{{ siteName }}</h3>
          </div>
          <p class="text-gray-400 font-sans text-sm leading-relaxed">
            {{ $t('footer.mission') }}
          </p>
          
          <!-- Social Links -->
          <div v-if="socialNetworks.length > 0" class="flex gap-4">
            <a 
              v-for="network in socialNetworks" 
              :key="network.platform"
              :href="network.url" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-[#EE9446] hover:text-white transition-all duration-300 transform hover:-translate-y-1" 
              :aria-label="network.platform"
            >
              <svg v-if="getSocialIcon(network.platform)" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path :d="getSocialIcon(network.platform)"/>
              </svg>
              <span v-else class="text-xs font-bold">{{ network.platform.charAt(0).toUpperCase() }}</span>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-xl font-display font-bold mb-6 text-white">{{ $t('footer.quickLinks') }}</h3>
          <ul class="space-y-3">
            <li v-for="item in navItems" :key="item.href">
              <Link 
                :href="item.href" 
                class="text-gray-400 hover:text-[#EE9446] transition-colors flex items-center gap-2 group"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-[#EE9446] opacity-0 group-hover:opacity-100 transition-opacity"></span>
                {{ $t(item.label) }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div>
          <h3 class="text-xl font-display font-bold mb-6 text-white">{{ $t('footer.contact') }}</h3>
          <ul class="space-y-4">
            <li v-if="contactAddress" class="flex items-start gap-3 text-gray-400">
              <svg class="w-5 h-5 text-[#EE9446] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span class="text-sm whitespace-pre-line">{{ contactAddress }}</span>
            </li>
            <li v-if="contactEmail" class="flex items-center gap-3 text-gray-400">
              <svg class="w-5 h-5 text-[#219D80] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <a :href="`mailto:${contactEmail}`" class="text-sm hover:text-white transition-colors">{{ contactEmail }}</a>
            </li>
            <li v-if="contactPhone" class="flex items-start gap-3 text-gray-400">
              <svg class="w-5 h-5 text-[#EE9446] mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <div class="flex flex-col">
                <span v-for="phone in contactPhone.split('\n')" :key="phone" class="text-sm hover:text-white transition-colors">
                  <a :href="`tel:${phone.trim().replace(/\s/g, '')}`">{{ phone.trim() }}</a>
                </span>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-gray-500 text-sm">
          &copy; {{ new Date().getFullYear() }} {{ siteName }}. All rights reserved.
        </p>
        <div class="flex gap-6 text-sm text-gray-500">
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import logoImg from '@/assets/favicon.png';

const page = usePage();
const settings = computed(() => page.props.settings || {});

const siteName = computed(() => settings.value.general?.site_name || 'GiveMeALift');
const siteLogo = computed(() => settings.value.appearance?.site_logo || logoImg);
const contactEmail = computed(() => settings.value.contact?.contact_email || 'contactus@givemealift.org');
const contactPhone = computed(() => settings.value.contact?.contact_phone || '+1 405 589 0915');
const contactAddress = computed(() => settings.value.contact?.contact_address || 'United States & Burkina Faso');

const socialNetworks = computed(() => {
  const networks = settings.value.social?.social_networks;
  if (Array.isArray(networks)) {
    return networks.filter(network => network.is_active && network.url);
  }
  return [];
});

const getSocialIcon = (platform) => {
  const icons = {
    facebook: 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
    twitter: 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z',
    instagram: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
    linkedin: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
    youtube: 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
    tiktok: 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.11-4.89 2.64 2.64 0 0 1 .75.11V9.07a6.54 6.54 0 0 0-1-.05A6.59 6.59 0 0 0 2.43 15a6.59 6.59 0 0 0 11.07 4.89 6.65 6.65 0 0 0 1.93-4.66v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-.61-.06z',
    github: 'M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12',
  };
  return icons[platform.toLowerCase()] || '';
};

const navItems = [
  { href: '/', label: 'nav.home' },
  { href: '/about', label: 'nav.about' },
  { href: '/events', label: 'nav.events' },
  { href: '/gallery', label: 'nav.gallery' },
  { href: '/contact', label: 'nav.contact' },
];
</script>
