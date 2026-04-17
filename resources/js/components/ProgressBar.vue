<template>
  <div class="flex flex-col gap-1.5 w-full">
    <div class="flex justify-between items-end px-0.5">
      <span class="text-[11px] font-bold text-zinc-300 uppercase tracking-widest drop-shadow-sm">
        {{ label }}
      </span>
      <span class="text-[11px] font-mono text-zinc-400 tabular-nums">
        <span class="text-white font-medium">{{ Math.round(current) }}</span> / {{ Math.round(max) }}
      </span>
    </div>
    
    <div class="h-3.5 w-full bg-zinc-950 rounded-full border border-zinc-800 p-[1.5px] shadow-inner overflow-hidden relative">
      <!-- Glow effect behind the bar -->
      <div 
        class="absolute top-0 bottom-0 left-0 transition-all duration-500 ease-out opacity-40 blur-[3px]"
        :class="bgClasses"
        :style="{ width: `${percentage}%` }"
      ></div>
      
      <!-- Actual progress bar -->
      <div 
        class="h-full rounded-full transition-all duration-500 ease-out shadow-sm relative overflow-hidden"
        :class="bgClasses"
        :style="{ width: `${percentage}%` }"
      >
        <!-- Inner juicy highlight/gradient -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/20 to-transparent"></div>
        <div class="absolute inset-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.15)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.15)_50%,rgba(255,255,255,0.15)_75%,transparent_75%,transparent)] bg-[length:16px_16px] animate-[slide_1.5s_linear_infinite] opacity-30"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  color: {
    type: String,
    required: true,
    validator(value) {
      return ['red', 'blue', 'yellow', 'green', 'purple', 'emerald'].includes(value);
    }
  },
  current: {
    type: Number,
    required: true,
    default: 0
  },
  max: {
    type: Number,
    required: true,
    default: 100
  },
  label: {
    type: String,
    default: 'Progress'
  }
});

const percentage = computed(() => {
  if (props.max <= 0) return 0;
  return Math.min(100, Math.max(0, (props.current / props.max) * 100));
});

const bgClasses = computed(() => {
  const map = {
    red: 'bg-rose-600',
    blue: 'bg-indigo-600',
    yellow: 'bg-amber-500',
    green: 'bg-emerald-600',
    purple: 'bg-purple-600',
    emerald: 'bg-teal-500'
  };
  return map[props.color] || 'bg-zinc-500';
});
</script>

<style scoped>
@keyframes slide {
  from {
    background-position: 16px 0;
  }
  to {
    background-position: 0 0;
  }
}
</style>
