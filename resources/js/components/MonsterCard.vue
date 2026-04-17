<template>
  <div v-if="monster" class="max-w-md w-full bg-zinc-900/60 backdrop-blur-md border border-red-900/30 rounded-2xl p-6 shadow-2xl relative z-[1] flex flex-col gap-4">
    <div class="absolute inset-0 bg-gradient-to-br from-red-950/20 to-transparent rounded-2xl pointer-events-none"></div>
    
    <div class="flex items-center justify-between relative z-10">
      <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded bg-zinc-800 border-2 border-red-900/50 flex items-center justify-center text-2xl shadow-inner">
          👹
        </div>
        <div>
          <h2 class="text-xl font-bold font-serif text-rose-100 tracking-wide">{{ monster.name }}</h2>
          <p class="text-[11px] text-rose-400 font-medium tracking-widest uppercase mt-1">Level {{ monster.level }}</p>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="h-px w-full bg-gradient-to-r from-transparent via-red-900/50 to-transparent relative z-10"></div>

    <div class="relative z-10">
      <ProgressBar
        color="red"
        :current="monster.hp"
        :max="monster.max_hp"
        label="Monster HP"
      />
    </div>
  </div>
</template>

<script setup>
import ProgressBar from './ProgressBar.vue';

defineProps({
  monster: {
    type: Object,
    default: null
  }
});
</script>
