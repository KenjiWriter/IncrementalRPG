<template>
  <div class="max-w-md w-full h-48 bg-black/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl flex flex-col relative z-[1] overflow-hidden shadow-inner">
    <div class="absolute inset-0 bg-gradient-to-b from-zinc-900/50 to-transparent pointer-events-none"></div>

    <div class="px-4 py-3 border-b border-zinc-800/80 bg-zinc-900/80">
      <h3 class="text-xs font-bold text-zinc-400 uppercase tracking-widest flex items-center gap-2">
        <span class="w-1.5 h-1.5 rounded-full bg-zinc-500 animate-pulse"></span>
        Combat Log
      </h3>
    </div>

    <div class="flex-1 overflow-y-auto p-4 flex flex-col-reverse gap-2 text-sm font-mono scroll-smooth" ref="logContainer">
      <transition-group name="log-list">
        <div 
          v-for="(log, index) in reversedLogs" 
          :key="log.id || index"
          class="py-1 px-2 rounded hover:bg-zinc-800/30 transition-colors"
          :class="getLogStyle(log.message)"
        >
          <span class="opacity-50 mr-2 text-xs select-none">></span>
          {{ log.message }}
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  logs: {
    type: Array,
    default: () => []
  }
});

// Assuming logs are appended to the end of the array, we show newest at the bottom naturally if flex-col-reverse is used
// or we can reverse them. With flex-col-reverse, the first element in DOM is at the bottom.
const reversedLogs = computed(() => {
  return [...props.logs].reverse();
});

const getLogStyle = (message) => {
  if (message.includes('defeated')) return 'text-emerald-400 font-bold';
  if (message.includes('gold')) return 'text-yellow-400';
  if (message.includes('looted')) return 'text-purple-400 font-bold drop-shadow-[0_0_2px_rgba(168,85,247,0.8)]';
  if (message.includes('You attacked')) return 'text-zinc-300';
  if (message.includes('attacked you')) return 'text-rose-400';
  return 'text-zinc-500';
};
</script>

<style scoped>
.log-list-enter-active,
.log-list-leave-active {
  transition: all 0.3s ease;
}
.log-list-enter-from {
  opacity: 0;
  transform: translateX(-10px);
}
.log-list-leave-to {
  opacity: 0;
  transform: translateX(10px);
}

/* Custom scrollbar for webkit */
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2);
}
::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.2);
}
</style>
