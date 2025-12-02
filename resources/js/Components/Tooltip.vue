<template>
  <div class="relative inline-block">
    <div 
      @mouseenter="showTooltip" 
      @mouseleave="hideTooltip"
      @focus="showTooltip"
      @blur="hideTooltip"
      class="inline-flex items-center"
    >
      <slot></slot>
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
          'absolute z-50 px-3 py-2 text-xs rounded-md shadow-lg border',
          'w-48 whitespace-normal break-words leading-relaxed overflow-hidden',
          variantClass,
          positionClass
        ]"
        role="tooltip"
      >
        {{ content }}
        <div 
          :class="[
            'absolute w-2 h-2 transform rotate-45',
            arrowVariantClass,
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
  },
  delay: {
    type: Number,
    default: 200
  },
  variant: {
    type: String,
    default: 'warning',
    validator: (value) => ['info', 'warning', 'error', 'success'].includes(value)
  }
});

const show = ref(false);
let timeoutId = null;

const showTooltip = () => {
  timeoutId = setTimeout(() => {
    show.value = true;
  }, props.delay);
};

const hideTooltip = () => {
  if (timeoutId) {
    clearTimeout(timeoutId);
  }
  show.value = false;
};

const variantClass = computed(() => {
  const variants = {
    info: 'bg-blue-50 border-blue-200 text-blue-900',
    warning: 'bg-orange-50 border-orange-200 text-orange-900',
    error: 'bg-red-50 border-red-200 text-red-900',
    success: 'bg-green-50 border-green-200 text-green-900'
  };
  return variants[props.variant];
});

const arrowVariantClass = computed(() => {
  const variants = {
    info: 'bg-blue-50 border-blue-200',
    warning: 'bg-orange-50 border-orange-200',
    error: 'bg-red-50 border-red-200',
    success: 'bg-green-50 border-green-200'
  };
  return variants[props.variant];
});

const positionClass = computed(() => {
  const positions = {
    top: 'bottom-full right-0 mb-2',
    bottom: 'top-full right-0 mt-2',
    left: 'right-full top-1/2 -translate-y-1/2 mr-2',
    right: 'left-full top-1/2 -translate-y-1/2 ml-2'
  };
  return positions[props.position];
});

const arrowClass = computed(() => {
  const arrows = {
    top: 'top-full right-3 -mt-1 border-l border-t',
    bottom: 'bottom-full right-3 -mb-1 border-r border-b',
    left: 'left-full top-1/2 -translate-y-1/2 -ml-1 border-l border-b',
    right: 'right-full top-1/2 -translate-y-1/2 -mr-1 border-r border-t'
  };
  return arrows[props.position];
});
</script>
