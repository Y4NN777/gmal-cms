<template>
  <!-- Floating Help Button -->
  <div class="fixed bottom-6 right-6 z-40">
    <button
      @click="showModal = true"
      class="group relative flex items-center justify-center w-14 h-14 bg-orange-600 hover:bg-orange-700 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110"
      :aria-label="$t('help.center')"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      
      <!-- Tooltip on hover -->
      <span class="absolute right-full mr-3 px-3 py-1 bg-gray-900 text-white text-sm rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
        {{ $t('help.center') }}
      </span>
    </button>
  </div>

  <!-- Help Modal -->
  <Teleport to="body">
    <Transition
      enter-active-class="transition ease-out duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="showModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        @click="showModal = false"
      >
        <Transition
          enter-active-class="transition ease-out duration-300"
          enter-from-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="transition ease-in duration-200"
          leave-from-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div 
            v-if="showModal"
            class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
            @click.stop
          >
            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-red-600 px-6 py-4 text-white">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-xl font-bold">{{ $t('help.center') }}</h2>
                    <p class="text-sm text-orange-100">{{ $t('help.subtitle') }}</p>
                  </div>
                </div>
                <button
                  @click="showModal = false"
                  class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Search Bar -->
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  :placeholder="$t('help.searchPlaceholder')"
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                />
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>

            <!-- Content -->
            <div class="flex h-[calc(90vh-180px)]">
              <!-- Sidebar -->
              <div class="w-64 bg-gray-50 border-r border-gray-200 overflow-y-auto">
                <nav class="p-4 space-y-1">
                  <button
                    v-for="section in sections"
                    :key="section.id"
                    @click="activeSection = section.id"
                    :class="[
                      'w-full text-left px-4 py-3 rounded-lg transition-colors flex items-center gap-3',
                      activeSection === section.id
                        ? 'bg-orange-100 text-orange-700 font-medium'
                        : 'text-gray-700 hover:bg-gray-100'
                    ]"
                  >
                    <component :is="section.icon" class="w-5 h-5" />
                    <span>{{ $t(section.label) }}</span>
                  </button>
                </nav>
              </div>

              <!-- Main Content -->
              <div class="flex-1 overflow-y-auto p-6">
                <!-- Search Results -->
                <div v-if="searchQuery && filteredFaqs.length > 0" class="space-y-4">
                  <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $t('help.searchResults') }}</h3>
                    <span class="text-sm text-gray-500">{{ filteredFaqs.length }} {{ $t('help.resultsFound') }}</span>
                  </div>
                  <div v-for="faq in filteredFaqs" :key="`${faq.section}-${faq.id}`" class="bg-white border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition-colors">
                    <div class="flex items-start gap-3">
                      <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $t(faq.question) }}</h4>
                        <p class="text-sm text-gray-600">{{ $t(faq.answer) }}</p>
                        <button 
                          @click="activeSection = faq.section; searchQuery = ''"
                          class="text-xs text-orange-600 hover:text-orange-700 font-medium mt-2"
                        >
                          {{ $t('help.viewSection') }} →
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- No Search Results -->
                <div v-else-if="searchQuery && filteredFaqs.length === 0" class="text-center py-12">
                  <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  <p class="text-gray-500 text-lg">{{ $t('help.noResults') }}</p>
                </div>

                <!-- Getting Started with Progress -->
                <div v-else-if="activeSection === 'getting-started'" class="space-y-6">
                  <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $t('help.gettingStarted.title') }}</h3>
                    <p class="text-gray-600">{{ $t('help.gettingStarted.intro') }}</p>
                  </div>

                  <!-- Progress Bar -->
                  <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                      <span class="text-sm font-medium text-gray-700">{{ $t('help.progress') }}</span>
                      <span class="text-sm font-bold text-orange-600">{{ Math.round(progressPercentage) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div 
                        class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full transition-all duration-500"
                        :style="{ width: progressPercentage + '%' }"
                      ></div>
                    </div>
                  </div>

                  <!-- Interactive Steps -->
                  <div class="space-y-4">
                    <div 
                      v-for="step in gettingStartedSteps" 
                      :key="step.id"
                      :class="[
                        'bg-white border-2 rounded-lg p-4 transition-all duration-200',
                        completedSteps.includes(step.id) 
                          ? 'border-green-500 bg-green-50' 
                          : 'border-gray-200 hover:border-orange-200'
                      ]"
                    >
                      <div class="flex items-start gap-4">
                        <button
                          @click="toggleStep(step.id)"
                          :class="[
                            'flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all',
                            completedSteps.includes(step.id)
                              ? 'bg-green-500 border-green-500'
                              : 'border-gray-300 hover:border-orange-500'
                          ]"
                        >
                          <svg 
                            v-if="completedSteps.includes(step.id)"
                            class="w-4 h-4 text-white" 
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                          >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                          </svg>
                        </button>
                        <div class="flex-1">
                          <h4 :class="['font-semibold mb-2', completedSteps.includes(step.id) ? 'text-green-700' : 'text-gray-900']">
                            {{ $t(step.title) }}
                          </h4>
                          <p class="text-sm text-gray-600">{{ $t(step.description) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Quick Actions -->
                  <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ $t('help.quickActions.title') }}</h4>
                    <div class="grid grid-cols-2 gap-3">
                      <button
                        v-for="action in quickActions"
                        :key="action.id"
                        @click="action.action(); showModal = false"
                        class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all group"
                      >
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                          <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="action.icon === 'tag'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            <path v-if="action.icon === 'calendar'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            <path v-if="action.icon === 'upload'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            <path v-if="action.icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                          </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 group-hover:text-orange-700">{{ $t(action.label) }}</span>
                      </button>
                    </div>
                  </div>
                </div>

                <div v-if="activeSection === 'categories'">
                  <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $t('help.categories.title') }}</h3>
                  <div class="space-y-6">
                    <div v-for="faq in categoriesFaq" :key="faq.id" class="border-b border-gray-200 pb-4">
                      <h4 class="font-semibold text-gray-900 mb-2">{{ $t(faq.question) }}</h4>
                      <p class="text-gray-600 text-sm">{{ $t(faq.answer) }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="activeSection === 'events'">
                  <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $t('help.events.title') }}</h3>
                  <div class="space-y-6">
                    <div v-for="faq in eventsFaq" :key="faq.id" class="border-b border-gray-200 pb-4">
                      <h4 class="font-semibold text-gray-900 mb-2">{{ $t(faq.question) }}</h4>
                      <p class="text-gray-600 text-sm">{{ $t(faq.answer) }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="activeSection === 'users'">
                  <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $t('help.users.title') }}</h3>
                  <div class="space-y-6">
                    <div v-for="faq in usersFaq" :key="faq.id" class="border-b border-gray-200 pb-4">
                      <h4 class="font-semibold text-gray-900 mb-2">{{ $t(faq.question) }}</h4>
                      <p class="text-gray-600 text-sm">{{ $t(faq.answer) }}</p>
                    </div>
                  </div>
                </div>

                <div v-if="activeSection === 'media'">
                  <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $t('help.media.title') }}</h3>
                  <div class="space-y-6">
                    <div v-for="faq in mediaFaq" :key="faq.id" class="border-b border-gray-200 pb-4">
                      <h4 class="font-semibold text-gray-900 mb-2">{{ $t(faq.question) }}</h4>
                      <p class="text-gray-600 text-sm">{{ $t(faq.answer) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, h } from 'vue';
import { router } from '@inertiajs/vue3';

const showModal = ref(false);
const activeSection = ref('getting-started');
const searchQuery = ref('');
const completedSteps = ref([]);

const sections = [
  { 
    id: 'getting-started', 
    label: 'help.gettingStarted.title',
    icon: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M13 10V3L4 14h7v7l9-11h-7z' })
    ])
  },
  { 
    id: 'categories', 
    label: 'help.categories.title',
    icon: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' })
    ])
  },
  { 
    id: 'events', 
    label: 'help.events.title',
    icon: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' })
    ])
  },
  { 
    id: 'users', 
    label: 'help.users.title',
    icon: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' })
    ])
  },
  { 
    id: 'media', 
    label: 'help.media.title',
    icon: () => h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
      h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' })
    ])
  }
];

const gettingStartedSteps = [
  { id: 1, title: 'help.gettingStarted.step1.title', description: 'help.gettingStarted.step1.description' },
  { id: 2, title: 'help.gettingStarted.step2.title', description: 'help.gettingStarted.step2.description' },
  { id: 3, title: 'help.gettingStarted.step3.title', description: 'help.gettingStarted.step3.description' },
  { id: 4, title: 'help.gettingStarted.step4.title', description: 'help.gettingStarted.step4.description' }
];

const categoriesFaq = [
  { id: 1, question: 'help.categories.q1', answer: 'help.categories.a1' },
  { id: 2, question: 'help.categories.q2', answer: 'help.categories.a2' },
  { id: 3, question: 'help.categories.q3', answer: 'help.categories.a3' }
];

const eventsFaq = [
  { id: 1, question: 'help.events.q1', answer: 'help.events.a1' },
  { id: 2, question: 'help.events.q2', answer: 'help.events.a2' },
  { id: 3, question: 'help.events.q3', answer: 'help.events.a3' }
];

const usersFaq = [
  { id: 1, question: 'help.users.q1', answer: 'help.users.a1' },
  { id: 2, question: 'help.users.q2', answer: 'help.users.a2' },
  { id: 3, question: 'help.users.q3', answer: 'help.users.a3' }
];

const mediaFaq = [
  { id: 1, question: 'help.media.q1', answer: 'help.media.a1' },
  { id: 2, question: 'help.media.q2', answer: 'help.media.a2' },
  { id: 3, question: 'help.media.q3', answer: 'help.media.a3' }
];

const quickActions = [
  { 
    id: 'create-category', 
    label: 'help.quickActions.createCategory',
    icon: 'tag',
    action: () => router.visit('/admin/categories')
  },
  { 
    id: 'create-event', 
    label: 'help.quickActions.createEvent',
    icon: 'calendar',
    action: () => router.visit('/admin/events/create')
  },
  { 
    id: 'upload-media', 
    label: 'help.quickActions.uploadMedia',
    icon: 'upload',
    action: () => router.visit('/admin/media')
  },
  { 
    id: 'manage-users', 
    label: 'help.quickActions.manageUsers',
    icon: 'users',
    action: () => router.visit('/admin/users')
  }
];

const allFaqs = computed(() => [
  ...categoriesFaq.map(faq => ({ ...faq, section: 'categories' })),
  ...eventsFaq.map(faq => ({ ...faq, section: 'events' })),
  ...usersFaq.map(faq => ({ ...faq, section: 'users' })),
  ...mediaFaq.map(faq => ({ ...faq, section: 'media' }))
]);

const filteredFaqs = computed(() => {
  if (!searchQuery.value) return [];
  
  const query = searchQuery.value.toLowerCase();
  return allFaqs.value.filter(faq => 
    faq.question.toLowerCase().includes(query) || 
    faq.answer.toLowerCase().includes(query)
  );
});

const toggleStep = (stepId) => {
  const index = completedSteps.value.indexOf(stepId);
  if (index > -1) {
    completedSteps.value.splice(index, 1);
  } else {
    completedSteps.value.push(stepId);
  }
};

const progressPercentage = computed(() => {
  return (completedSteps.value.length / gettingStartedSteps.length) * 100;
});
</script>
