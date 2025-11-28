<template>
  <Teleport to="body">
    <div
      class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center"
      @click.self="close"
    >
      <!-- Close Button -->
      <button
        @click="close"
        class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10"
        aria-label="Close lightbox"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Previous Button -->
      <button
        v-if="hasPrevious"
        @click="previous"
        class="absolute left-4 text-white hover:text-gray-300 transition-colors p-3 bg-black/50 rounded-full"
        aria-label="Previous image"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <!-- Image Container -->
      <div class="max-w-7xl max-h-screen p-8 flex flex-col items-center justify-center">
        <img
          :src="currentImage.image_url"
          :alt="currentImage.alt_text || currentImage.caption"
          class="max-h-[80vh] max-w-full object-contain"
        />
        
        <!-- Caption -->
        <div v-if="currentImage.caption" class="mt-4 text-center">
          <p class="text-white text-lg">{{ currentImage.caption }}</p>
          <p v-if="currentImage.album" class="text-gray-400 text-sm mt-1">
            {{ currentImage.album.title }}
          </p>
        </div>

        <!-- Image Counter -->
        <div class="mt-4 text-gray-400 text-sm">
          {{ currentIndex + 1 }} / {{ images.length }}
        </div>
      </div>

      <!-- Next Button -->
      <button
        v-if="hasNext"
        @click="next"
        class="absolute right-4 text-white hover:text-gray-300 transition-colors p-3 bg-black/50 rounded-full"
        aria-label="Next image"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  images: {
    type: Array,
    required: true
  },
  initialIndex: {
    type: Number,
    default: 0
  }
});

const emit = defineEmits(['close']);

const currentIndex = ref(props.initialIndex);

const currentImage = computed(() => props.images[currentIndex.value]);
const hasPrevious = computed(() => currentIndex.value > 0);
const hasNext = computed(() => currentIndex.value < props.images.length - 1);

const previous = () => {
  if (hasPrevious.value) {
    currentIndex.value--;
  }
};

const next = () => {
  if (hasNext.value) {
    currentIndex.value++;
  }
};

const close = () => {
  emit('close');
};

const handleKeydown = (e) => {
  if (e.key === 'Escape') {
    close();
  } else if (e.key === 'ArrowLeft') {
    previous();
  } else if (e.key === 'ArrowRight') {
    next();
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleKeydown);
  document.body.style.overflow = 'hidden';
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
  document.body.style.overflow = '';
});
</script>
