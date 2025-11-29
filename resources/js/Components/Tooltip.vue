<template>
  <div class="relative inline-block">
    <div 
      @mouseenter="show = true" 
      @mouseleave="show = false"
      class="inline-flex items-center"
    >
      <slot name="trigger">
        <button 
          type="button"
          class="inline-flex items-center justify-center w-4 h-4 ml-1 text-gray-400 hover:text-gray-600 focus:outline-none"
          :aria-label="$t('common.help')"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
        </button>
      </slot>
    </div>
    
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 translate-y-1"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-1"
    >
      <div 
        v-if="show"
        :class="[
          'absolute z-50 px-3 py-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg',
          'max-w-xs',
          positionClass
        ]"
        role="tooltip"
      >
        <slot>{{ content }}</slot>
        <div 
          :class="[
            'absolute w-2 h-2 bg-gray-900 transform rotate-45',
            arrowClass
          ]"
        ></div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  content: {
    type: String,
    default: ''
  },
  position: {
    type: String,
    default: 'top',
    validator: (value) => ['top', 'bottom', 'left', 'right'].includes(value)
  }
});

const show = ref(false);

const positionClass = computed(() => {
  const positions = {
    top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
    left: 'right-full top-1/2 -translate-y-1/2 mr-2',
    right: 'left-full top-1/2 -translate-y-1/2 ml-2'
  };
  return positions[props.position];
});

const arrowClass = computed(() => {
  const arrows = {
    top: 'top-full left-1/2 -translate-x-1/2 -mt-1',
    bottom: 'bottom-full left-1/2 -translate-x-1/2 -mb-1',
    left: 'left-full top-1/2 -translate-y-1/2 -ml-1',
    right: 'right-full top-1/2 -translate-y-1/2 -mr-1'
  };
  return arrows[props.position];
});
</script>
