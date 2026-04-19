<template>
  <div
    class="fixed lg:relative z-40 h-[calc(100vh-4rem)] transition-all duration-300 ease-in-out flex"
    :class="[
      side === 'left' ? 'left-0' : 'right-0',
      isOpen ? 'w-80 opacity-100 translate-x-0' : 'w-0 opacity-0 lg:opacity-100 ' + (side === 'left' ? '-translate-x-full' : 'translate-x-full')
    ]"
  >
    <!-- Background / Blur Overlay for Sidebars -->
    <div class="absolute inset-0 bg-zinc-900/90 backdrop-blur-xl border-zinc-800/80"
         :class="side === 'left' ? 'border-r' : 'border-l'">
    </div>

    <!-- Content Slot -->
    <div class="relative w-80 h-full overflow-y-auto p-4 custom-scrollbar flex flex-col gap-6" v-show="isOpen || (isDesktop && !isOpen)">
       <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
  isOpen: Boolean,
  side: {
    type: String,
    default: 'left'
  }
});

const isDesktop = ref(window.innerWidth >= 1024);

const updateBreakpoint = () => {
  isDesktop.value = window.innerWidth >= 1024;
};

onMounted(() => {
  window.addEventListener('resize', updateBreakpoint);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateBreakpoint);
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #27272a;
  border-radius: 10px;
}
</style>
