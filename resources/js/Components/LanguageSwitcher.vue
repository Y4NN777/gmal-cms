<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const currentLocale = computed(() => locale.value);

const languages = [
  { code: 'en', name: 'English', flag: '🇬🇧' },
  { code: 'fr', name: 'Français', flag: '🇫🇷' },
];

const changeLanguage = (langCode) => {
  router.post('/locale', { locale: langCode }, {
    preserveScroll: true,
    onSuccess: () => {
      // Force full page reload to reinitialize vue-i18n with new locale
      window.location.reload();
    },
  });
};
</script>

<template>
  <div class="relative inline-block text-left group">
    <button
      type="button"
      class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-orange-600 transition-colors"
    >
      <span>{{ languages.find(l => l.code === currentLocale)?.flag }}</span>
      <span class="hidden sm:inline">{{ languages.find(l => l.code === currentLocale)?.name }}</span>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown -->
    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
      <div class="py-1">
        <button
          v-for="lang in languages"
          :key="lang.code"
          @click="changeLanguage(lang.code)"
          class="flex items-center gap-3 w-full px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors"
          :class="{ 'bg-orange-50 text-orange-600 font-medium': currentLocale === lang.code }"
        >
          <span class="text-xl">{{ lang.flag }}</span>
          <span>{{ lang.name }}</span>
          <svg
            v-if="currentLocale === lang.code"
            class="w-4 h-4 ml-auto"
            fill="currentColor"
            viewBox="0 0 20 20"
          >
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>
