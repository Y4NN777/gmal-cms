<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success'); // 'success' | 'error' | 'info'

let timeoutId = null;

const showToast = (msg, toastType = 'success') => {
  message.value = msg;
  type.value = toastType;
  show.value = true;

  // Auto-hide after 5 seconds
  if (timeoutId) clearTimeout(timeoutId);
  timeoutId = setTimeout(() => {
    show.value = false;
  }, 5000);
};

const hideToast = () => {
  show.value = false;
  if (timeoutId) clearTimeout(timeoutId);
};

// Watch for flash messages from Laravel
watch(
  () => [page.props.flash?.success, page.props.flash?.error],
  ([success, error]) => {
    if (success) {
      showToast(success, 'success');
    } else if (error) {
      showToast(error, 'error');
    }
  },
  { immediate: true }
);

const getIcon = () => {
  if (type.value === 'success') {
    return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>`;
  } else if (type.value === 'error') {
    return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>`;
  }
  return `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>`;
};

const getColorClasses = () => {
  if (type.value === 'success') {
    return 'bg-green-50 text-green-800 border-green-200';
  } else if (type.value === 'error') {
    return 'bg-red-50 text-red-800 border-red-200';
  }
  return 'bg-blue-50 text-blue-800 border-blue-200';
};
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-300"
    enter-from-class="opacity-0 translate-y-2"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-200"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-2"
  >
    <div
      v-if="show"
      class="fixed top-20 right-4 z-50 max-w-md w-full shadow-lg rounded-lg border pointer-events-auto"
      :class="getColorClasses()"
    >
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0" v-html="getIcon()"></div>
          <div class="ml-3 w-0 flex-1">
            <p class="text-sm font-medium">
              {{ message }}
            </p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="hideToast"
              class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
              :class="type === 'success' ? 'text-green-500 hover:text-green-600 focus:ring-green-500' : 'text-red-500 hover:text-red-600 focus:ring-red-500'"
            >
              <span class="sr-only">Close</span>
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>
