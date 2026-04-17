<template>
  <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans overflow-hidden">
    <!-- Header -->
    <header class="h-16 bg-zinc-950/80 border-b border-zinc-800/60 backdrop-blur-md flex items-center px-6 sticky top-0 z-10 w-full justify-between shadow-sm">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded shrink-0 bg-gradient-to-br from-rose-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
          <span class="font-bold text-white tracking-widest text-sm">ES</span>
        </div>
        <h1 class="font-bold text-lg tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-zinc-100 to-zinc-400">
          ETERNAL SHARD
        </h1>
      </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-1 flex flex-col items-center p-6 sm:p-12 relative overflow-y-auto">
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/10 via-zinc-950/0 to-zinc-950 pointer-events-none"></div>

      <!-- Character Card -->
      <div class="max-w-md w-full bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-6 shadow-2xl flex flex-col gap-6 relative z-[1]">
        
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold font-serif text-white tracking-wide drop-shadow-sm">{{ characterStore.name || 'Unknown Adventurer' }}</h2>
            <p class="text-[11px] text-zinc-400 font-medium tracking-widest uppercase mt-1">Level {{ characterStore.level }} • Idle RPG</p>
          </div>
          <div class="w-14 h-14 rounded-full border-2 border-zinc-700 bg-zinc-800 shadow-inner flex items-center justify-center shrink-0">
            <span class="text-2xl drop-shadow-md pb-0.5">👤</span>
          </div>
        </div>

        <!-- Divider -->
        <div class="h-px w-full bg-gradient-to-r from-transparent via-zinc-700/50 to-transparent"></div>

        <!-- Vitals section -->
        <div class="flex flex-col gap-4">
          <ProgressBar
            color="red"
            :current="characterStore.hp"
            :max="characterStore.max_hp"
            label="Health Points"
          />
          <ProgressBar
            color="blue"
            :current="characterStore.mana"
            :max="characterStore.max_mana"
            label="Mana"
          />
          <ProgressBar
            color="yellow"
            :current="characterStore.experience"
            :max="characterStore.level * 1000"
            label="Experience"
          />
        </div>
      </div>
      
    </main>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import { useCharacterStore } from '../store/useCharacterStore';
import ProgressBar from './ProgressBar.vue';

const characterStore = useCharacterStore();
let heartbeatInterval = null;

onMounted(async () => {
  // Fetch initial character state from backend
  await characterStore.fetchActiveCharacter();

  // Start the RPG Combat ticker
  heartbeatInterval = setInterval(() => {
    if (!characterStore.isDead) {
        characterStore.heartbeatTick();
    }
  }, 2000); // 2 second combat tick
});

onUnmounted(() => {
  if (heartbeatInterval) clearInterval(heartbeatInterval);
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
  font-family: 'Cinzel', serif;
}
.font-sans {
  font-family: 'Inter', sans-serif;
}
</style>
