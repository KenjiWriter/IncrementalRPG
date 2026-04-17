<template>
  <div class="w-full max-w-5xl flex items-center justify-between mb-2 px-4">
    <div class="flex items-center gap-4">
      <div class="flex -space-x-2 overflow-hidden">
        <div 
          v-for="player in displayedPlayers" 
          :key="player.id"
          class="inline-block h-8 w-8 rounded-full ring-2 ring-zinc-950 bg-zinc-800 flex items-center justify-center text-xs font-bold text-white relative group"
          :title="`${player.name} (Lvl ${player.level})`"
        >
          {{ player.name.charAt(0) }}
          <div class="absolute -bottom-1 -right-1 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-zinc-950"></div>
          
          <!-- Tooltip -->
          <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-zinc-900 border border-zinc-800 rounded text-[10px] whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 shadow-xl">
             <span class="text-rose-400 font-bold">{{ player.name }}</span>
             <span class="text-zinc-500 ml-2">Lvl {{ player.level }}</span>
          </div>
        </div>
        
        <div v-if="overflowCount > 0" class="inline-block h-8 w-8 rounded-full ring-2 ring-zinc-950 bg-zinc-700 flex items-center justify-center text-[10px] font-bold text-zinc-300">
          +{{ overflowCount }}
        </div>
      </div>
      
      <div class="flex flex-col">
          <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest leading-none">Present Seekers</span>
          <span class="text-xs text-zinc-500 font-medium mt-0.5">{{ characterStore.presentPlayers.length }} players hunting in this zone</span>
      </div>
    </div>
    
    <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-900/50 border border-zinc-800/80">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
        </span>
        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Real-time Presence</span>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCharacterStore } from '../store/useCharacterStore';

const characterStore = useCharacterStore();

const displayedPlayers = computed(() => {
    return characterStore.presentPlayers.slice(0, 5);
});

const overflowCount = computed(() => {
    return Math.max(0, characterStore.presentPlayers.length - 5);
});
</script>
