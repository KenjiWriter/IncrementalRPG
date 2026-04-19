<template>
  <div class="w-full bg-zinc-900/40 backdrop-blur-xl border border-zinc-800/80 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-zinc-500 mb-1">World Map</h3>
        <h2 class="text-xl font-serif text-white tracking-wide">Travel to Zone</h2>
      </div>
      <div class="p-2 rounded-lg bg-zinc-800/50 border border-zinc-700/50">
        <span class="text-xl">🗺️</span>
      </div>
    </div>

    <!-- Location list -->
    <div class="flex flex-col gap-3">
      <div 
        v-for="location in locationStore.locations" 
        :key="location.id"
        @click="handleTravel(location)"
        class="relative p-4 rounded-xl border transition-all duration-300 cursor-pointer overflow-hidden"
        :class="[
          characterStore.current_location_id === location.id 
            ? 'bg-zinc-800/80 border-rose-500/50 shadow-lg shadow-rose-500/5 scale-[1.02] z-[2]' 
            : 'bg-zinc-900/60 border-zinc-800 hover:border-zinc-700 hover:bg-zinc-800/40 hover:scale-[1.01]',
          characterStore.level < location.min_level ? 'opacity-60 grayscale cursor-not-allowed' : ''
        ]"
      >
        <!-- Background Accent (Only for Active) -->
        <div 
          v-if="characterStore.current_location_id === location.id"
          class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-rose-500 via-indigo-500 to-rose-500 animate-pulse"
        ></div>

        <div class="flex justify-between items-center relative z-[1]">
          <div>
            <div class="flex items-center gap-2">
              <span class="font-serif text-lg text-zinc-100 tracking-wide">{{ location.name }}</span>
              <span v-if="characterStore.current_location_id === location.id" class="text-[10px] bg-rose-500/20 text-rose-400 px-2 py-0.5 rounded-full font-bold uppercase tracking-widest border border-rose-500/30">Current</span>
              <span v-if="characterStore.level < location.min_level" class="text-[10px] bg-zinc-950/60 text-zinc-400 px-2 py-0.5 rounded-full font-bold uppercase tracking-widest border border-zinc-800 flex items-center gap-1">
                <span class="text-[8px]">🔒</span> LVL {{ location.min_level }}
              </span>
            </div>
            <p class="text-xs text-zinc-500 mt-1 line-clamp-1 italic">{{ location.description }}</p>
          </div>
          
          <div class="flex flex-col items-end gap-1">
             <div class="flex items-center gap-1.5 bg-zinc-950/40 px-2 py-1 rounded-md border border-zinc-800/50">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                <span class="text-[10px] font-bold text-zinc-300">{{ getPresenceCount(location.id) }}</span>
             </div>
          </div>
        </div>

        <!-- Travel Progress Overlay (Visual fluff) -->
        <div v-if="isTraveling === location.id" class="absolute inset-0 bg-zinc-950/80 flex items-center justify-center backdrop-blur-sm z-[10]">
            <div class="flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full border-2 border-rose-500 border-t-transparent animate-spin"></div>
                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-widest">Traveling...</span>
            </div>
        </div>
      </div>
    </div>

    <!-- Footer info -->
    <div class="mt-6 pt-4 border-t border-zinc-800/50 flex justify-between items-center px-1">
        <span class="text-[10px] text-zinc-500 uppercase tracking-widest font-medium">World Status</span>
        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">Reverb Online</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useCharacterStore } from '../store/useCharacterStore';
import { useLocationStore } from '../store/useLocationStore';

const characterStore = useCharacterStore();
const locationStore = useLocationStore();
const isTraveling = ref(null);

const handleTravel = async (location) => {
    if (characterStore.current_location_id === location.id) return;
    if (characterStore.level < location.min_level) return;
    
    isTraveling.value = location.id;
    await characterStore.changeLocation(location.id);
    setTimeout(() => {
        isTraveling.value = null;
    }, 600);
};

const getPresenceCount = (locationId) => {
    // If we are currently at this location, we kow the count
    if (characterStore.current_location_id === locationId) {
        return characterStore.presentPlayers.length;
    }
    // TODO: In Phase 3, we could fetch global counts for all zones
    return Math.floor(Math.random() * 3) + 1; // Fake noise for other zones for now
};
</script>
