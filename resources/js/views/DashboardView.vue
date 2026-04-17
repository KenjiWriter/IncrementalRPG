<template>
  <div class="min-h-screen bg-zinc-950 text-zinc-100 flex flex-col font-sans overflow-hidden">
    <header class="h-16 bg-zinc-950/80 border-b border-zinc-800/60 backdrop-blur-md flex items-center px-6 sticky top-0 z-10 w-full justify-between shadow-sm">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded shrink-0 bg-gradient-to-br from-rose-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-rose-500/20">
          <span class="font-bold text-white tracking-widest text-sm">ES</span>
        </div>
        <h1 class="font-bold text-lg tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-zinc-100 to-zinc-400">
          ETERNAL SHARD
        </h1>
      </div>
      <div>
        <button @click="handleLogout" class="text-xs font-semibold uppercase tracking-widest text-zinc-400 hover:text-zinc-100 transition-colors bg-zinc-800/50 hover:bg-zinc-700/50 py-1.5 px-3 rounded border border-zinc-700 cursor-pointer">Logout</button>
      </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-1 flex flex-col items-center p-6 sm:p-12 relative overflow-y-auto w-full mx-auto gap-8">
      <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/10 via-zinc-950/0 to-zinc-950 pointer-events-none"></div>

      <div class="flex flex-col md:flex-row items-center md:items-start justify-center gap-6 md:gap-12 w-full max-w-5xl z-[1] mt-4">
        
        <!-- Character Card -->
        <div 
          class="w-full md:w-1/2 max-w-md bg-zinc-900/60 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-6 shadow-2xl flex flex-col gap-6 relative overflow-hidden transition-all duration-1000"
          :class="characterStore.isDead ? 'grayscale-[0.8] opacity-80 border-rose-900/50' : ''"
        >
          <!-- Defeated Overlay -->
          <div v-if="characterStore.isDead" class="absolute inset-0 bg-rose-950/20 z-[0] pointer-events-none flex items-center justify-center">
             <span class="absolute top-4 right-4 text-xs font-bold text-rose-500 uppercase tracking-widest bg-rose-950/80 px-2 py-1 rounded shadow">Resting</span>
          </div>
          
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
            <div class="flex justify-between items-center mt-2 px-1">
              <span class="text-xs text-zinc-400 font-medium tracking-widest uppercase">Wealth</span>
              <span class="text-sm text-yellow-400 font-mono font-bold">{{ characterStore.gold }}<span class="text-yellow-600 ml-1">g</span></span>
            </div>
          </div>
        </div>

        <!-- VS Divider for Desktop -->
        <div class="hidden md:flex flex-col items-center justify-center pt-24">
          <div class="w-10 h-10 rounded-full bg-zinc-900 border-2 border-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-500 shadow-lg shrink-0">
            VS
          </div>
        </div>

        <!-- VS Divider for Mobile -->
        <div class="md:hidden w-full flex items-center justify-center">
           <div class="w-8 h-8 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[10px] font-bold text-zinc-600 shadow-lg shrink-0">
            VS
          </div>
        </div>

        <!-- Monster Card -->
        <div class="w-full md:w-1/2 max-w-md flex justify-center">
          <MonsterCard :monster="characterStore.monster" />
        </div>

      </div>

      <!-- Combat Log Area -->
      <div class="w-full max-w-2xl mt-4 flex justify-center pb-8">
        <CombatLog :logs="characterStore.logs" />
      </div>

    </main>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/useAuthStore';
import { useCharacterStore } from '../store/useCharacterStore';
import ProgressBar from '../components/ProgressBar.vue';
import MonsterCard from '../components/MonsterCard.vue';
import CombatLog from '../components/CombatLog.vue';

const authStore = useAuthStore();
const characterStore = useCharacterStore();
const router = useRouter();
let heartbeatInterval = null;

const handleLogout = async () => {
    await authStore.logout();
    router.push('/');
};

onMounted(async () => {
  // Fetch initial character state from backend
  await characterStore.fetchActiveCharacter();

  // Start the RPG Combat ticker
  heartbeatInterval = setInterval(() => {
      characterStore.heartbeatTick();
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
