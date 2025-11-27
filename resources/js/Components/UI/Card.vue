<template>
  <div :class="cardClasses">
    <div v-if="$slots.image" class="card-image">
      <slot name="image" />
    </div>
    
    <div :class="contentClasses">
      <div v-if="$slots.header" class="card-header">
        <slot name="header" />
      </div>
      
      <div v-if="$slots.default" class="card-body">
        <slot />
      </div>
      
      <div v-if="$slots.footer" class="card-footer">
        <slot name="footer" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'bordered', 'elevated', 'flat'].includes(value)
  },
  padding: {
    type: String,
    default: 'md',
    validator: (value) => ['none', 'sm', 'md', 'lg'].includes(value)
  },
  hoverable: {
    type: Boolean,
    default: false
  }
});

const cardClasses = computed(() => {
  const base = 'bg-white rounded-lg overflow-hidden';
  
  const variants = {
    default: 'border border-gray-200 shadow-sm',
    bordered: 'border-2 border-gray-200',
    elevated: 'shadow-lg',
    flat: ''
  };
  
  const classes = [base, variants[props.variant]];
  
  if (props.hoverable) {
    classes.push('transition-transform duration-200 hover:scale-105 hover:shadow-xl cursor-pointer');
  }
  
  return classes.join(' ');
});

const contentClasses = computed(() => {
  const paddings = {
    none: '',
    sm: 'p-4',
    md: 'p-6',
    lg: 'p-8'
  };
  
  return paddings[props.padding];
});
</script>

<style scoped>
.card-image {
  width: 100%;
  overflow: hidden;
}

.card-header {
  margin-bottom: 1rem;
}

.card-body {
  color: rgb(75 85 99);
}

.card-footer {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top-width: 1px;
  border-color: rgb(229 231 235);
}
</style>
