<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();

const isOpen = ref(false);
const currentLocale = computed(() => locale.value);

const languages = [
  { code: 'en', name: 'English', flag: '🇬🇧' },
  { code: 'fr', name: 'Français', flag: '🇫🇷' },
];

const closeDropdown = () => {
  isOpen.value = false;
};

const selectLanguage = (langCode) => {
  isOpen.value = false;
  router.post('/locale', { locale: langCode }, {
    preserveScroll: true,
    onSuccess: () => {
      locale.value = langCode;
      document.documentElement.lang = langCode;
    },
  });
};

// Click outside directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = (event) => {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value();
      }
    };
    document.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.removeEventListener('click', el.clickOutsideEvent);
  },
};
</script>

<template>
  <div class="relative inline-block text-left">
    <button
      type="button"
      @click="isOpen = !isOpen"
      class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-orange-600 transition-colors rounded-lg hover:bg-gray-50"
    >
      <span class="text-lg">{{ languages.find(l => l.code === currentLocale)?.flag }}</span>
      <span class="hidden sm:inline">{{ languages.find(l => l.code === currentLocale)?.name }}</span>
      <svg 
        class="w-4 h-4 transition-transform duration-200" 
        :class="{ 'rotate-180': isOpen }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Dropdown -->
    <Transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div 
        v-if="isOpen"
        v-click-outside="closeDropdown"
        class="absolute right-0 mt-2 w-44 origin-top-right bg-white rounded-lg shadow-lg border border-gray-200 z-[100]"
      >
        <div class="py-1">
          <button
            v-for="lang in languages"
            :key="lang.code"
            @click="selectLanguage(lang.code)"
            class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors"
            :class="{ 'bg-orange-50 text-orange-600 font-semibold': currentLocale === lang.code }"
          >
            <span class="text-lg">{{ lang.flag }}</span>
            <span>{{ lang.name }}</span>
            <svg
              v-if="currentLocale === lang.code"
              class="w-4 h-4 ml-auto text-orange-600"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>
