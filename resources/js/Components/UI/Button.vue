<template>
  <component
    :is="componentType"
    :href="href"
    :type="type"
    :disabled="disabled || loading"
    :class="buttonClasses"
    @click="handleClick"
  >
    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <slot />
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'outline', 'ghost', 'danger'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  href: {
    type: String,
    default: null
  },
  type: {
    type: String,
    default: 'button'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  fullWidth: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['click']);

const componentType = computed(() => {
  if (props.href) {
    return props.href.startsWith('http') ? 'a' : Link;
  }
  return 'button';
});

const buttonClasses = computed(() => {
  const base = 'inline-flex items-center justify-center font-semibold transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2';
  
  const variants = {
    primary: 'bg-primary-orange text-white hover:bg-orange-600 focus:ring-primary-orange',
    secondary: 'bg-primary-green text-white hover:bg-green-700 focus:ring-primary-green',
    outline: 'bg-transparent border-2 border-primary-orange text-primary-orange hover:bg-primary-orange hover:text-white focus:ring-primary-orange',
    ghost: 'bg-transparent text-primary-dark hover:bg-gray-100 focus:ring-gray-300',
    danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500'
  };
  
  const sizes = {
    sm: 'px-4 py-2 text-sm',
    md: 'px-6 py-3 text-base',
    lg: 'px-8 py-4 text-lg'
  };
  
  const classes = [base, variants[props.variant], sizes[props.size]];
  
  if (props.fullWidth) {
    classes.push('w-full');
  }
  
  if (props.disabled || props.loading) {
    classes.push('opacity-50 cursor-not-allowed');
  }
  
  return classes.join(' ');
});

const handleClick = (event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event);
  }
};
</script>
