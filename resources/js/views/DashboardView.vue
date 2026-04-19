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

    <!-- Layout Wrapper -->
    <div class="flex-1 flex overflow-hidden relative">
      
      <!-- Left Sidebar: Character Sheet -->
      <SidePanel side="left" :isOpen="isLeftPanelOpen">
        <CharacterStats />
        <div class="mt-4">
          <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500 mb-4 flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-rose-500"></span>
            Equipment
          </h3>
          <EquipmentSlots />
        </div>
      </SidePanel>

      <!-- Main Combat Column -->
      <main class="flex-1 relative overflow-y-auto px-6 py-8 sm:px-12 transition-all duration-300"
            :style="{ 
              backgroundColor: characterStore.currentLocation?.css_theme?.from || '#09090b',
              backgroundImage: `radial-gradient(ellipse at top, ${characterStore.currentLocation?.css_theme?.to || '#09090b'} 0%, transparent 70%)`
            }">
        
        <!-- Toggle Buttons -->
        <!-- Left Toggle -->
        <button 
          @click="toggleLeftPanel"
          class="fixed left-4 bottom-24 lg:top-20 lg:bottom-auto z-50 w-10 h-10 rounded-full bg-zinc-900 border flex items-center justify-center text-xl shadow-lg transition-all active:scale-90 cursor-pointer"
          :class="isLeftPanelOpen ? 'lg:left-84' : 'left-4'"
          :style="{ 
            boxShadow: `0 0 15px ${characterStore.currentLocation?.css_theme?.to || '#3b82f6'}44`,
            borderColor: `${characterStore.currentLocation?.css_theme?.to || '#3b82f6'}66`
          }"
        >
          {{ isLeftPanelOpen ? '◀' : '👤' }}
        </button>

        <!-- Right Toggle -->
        <button 
          @click="toggleRightPanel"
          class="fixed right-4 bottom-24 lg:top-20 lg:bottom-auto z-50 w-10 h-10 rounded-full bg-zinc-900 border flex items-center justify-center text-xl shadow-lg transition-all active:scale-90 cursor-pointer"
          :class="isRightPanelOpen ? 'lg:right-84' : 'right-4'"
          :style="{ 
            boxShadow: `0 0 15px ${characterStore.currentLocation?.css_theme?.to || '#3b82f6'}44`,
            borderColor: `${characterStore.currentLocation?.css_theme?.to || '#3b82f6'}66`
          }"
        >
          {{ isRightPanelOpen ? '▶' : '🎒' }}
        </button>

        <!-- Content Area -->
        <div class="max-w-4xl mx-auto flex flex-col gap-8">
          <!-- Zone Presence Banner -->
          <ZonePresenceBanner />

          <div class="flex flex-col md:flex-row items-center md:items-start justify-center gap-6 md:gap-12 w-full z-[1]">
            
            <!-- Character Summary Card (Mini) -->
            <div 
              class="w-full md:w-1/2 max-w-md bg-zinc-900/40 backdrop-blur-md border border-zinc-800/80 rounded-2xl p-6 shadow-2xl flex flex-col gap-6 relative overflow-hidden transition-all duration-1000"
              :class="characterStore.isDead ? 'grayscale-[0.8] opacity-80 border-rose-900/50' : ''"
            >
              <div v-if="characterStore.isDead" class="absolute inset-0 bg-rose-950/20 z-[0] pointer-events-none flex items-center justify-center">
                 <span class="absolute top-4 right-4 text-xs font-bold text-rose-500 uppercase tracking-widest bg-rose-950/80 px-2 py-1 rounded shadow">Resting</span>
              </div>
              
              <div class="flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-bold font-serif text-white tracking-wide drop-shadow-sm">{{ authStore.user?.name || 'Unknown Adventurer' }}</h2>
                  <p class="text-[11px] text-zinc-400 font-medium tracking-widest uppercase mt-1">Level {{ characterStore.level }} • {{ characterStore.name }}</p>
                </div>
                <div class="w-12 h-12 rounded-full border-2 border-zinc-700 bg-zinc-800 shadow-inner flex items-center justify-center shrink-0">
                  <span class="text-xl drop-shadow-md pb-0.5">👤</span>
                </div>
              </div>

              <div class="h-px w-full bg-gradient-to-r from-transparent via-zinc-700/50 to-transparent"></div>

              <div class="flex flex-col gap-4">
                <ProgressBar color="red" :current="characterStore.hp" :max="characterStore.max_hp" label="Health" />
                <ProgressBar color="blue" :current="characterStore.mana" :max="characterStore.max_mana" label="Mana" />
                <ProgressBar color="yellow" :current="characterStore.experience" :max="characterStore.level * 1000" label="EXP" />
              </div>
            </div>

            <!-- VS Divider -->
            <div class="hidden md:flex flex-col items-center justify-center pt-16">
              <div class="w-8 h-8 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-[10px] font-bold text-zinc-600 shadow-lg shrink-0">
                VS
              </div>
            </div>

            <!-- Monster Card -->
            <div class="w-full md:w-1/2 max-w-md flex justify-center">
              <MonsterCard :monster="characterStore.monster" />
            </div>
          </div>

          <!-- Combat Log -->
          <div class="w-full pb-8">
               <CombatLog :logs="characterStore.logs" />
          </div>
        </div>
      </main>

      <!-- Right Sidebar: Inventory & World Map -->
      <SidePanel side="right" :isOpen="isRightPanelOpen">
        <InventoryPanel />
        <div class="mt-4">
          <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500 mb-4 flex items-center gap-2">
            <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
            World Map
          </h3>
          <TravelMenu />
        </div>
      </SidePanel>

    </div>

    <!-- Notification Overlay (Stays on Top) -->
    <NotificationOverlay />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/useAuthStore';
import { useCharacterStore } from '../store/useCharacterStore';
import { useLocationStore } from '../store/useLocationStore';
import ProgressBar from '../components/ProgressBar.vue';
import MonsterCard from '../components/MonsterCard.vue';
import CombatLog from '../components/CombatLog.vue';
import TravelMenu from '../components/TravelMenu.vue';
import ZonePresenceBanner from '../components/ZonePresenceBanner.vue';
import NotificationOverlay from '../components/NotificationOverlay.vue';
import CharacterStats from '../components/CharacterStats.vue';
import EquipmentSlots from '../components/EquipmentSlots.vue';
import InventoryPanel from '../components/InventoryPanel.vue';
import SidePanel from '../components/SidePanel.vue';

const authStore = useAuthStore();
const characterStore = useCharacterStore();
const locationStore = useLocationStore();
const router = useRouter();

const isLeftPanelOpen = ref(window.innerWidth >= 1280);
const isRightPanelOpen = ref(window.innerWidth >= 1280);

let heartbeatInterval = null;

const toggleLeftPanel = () => isLeftPanelOpen.value = !isLeftPanelOpen.value;
const toggleRightPanel = () => isRightPanelOpen.value = !isRightPanelOpen.value;

const handleLogout = async () => {
    await authStore.logout();
    router.push('/');
};

onMounted(async () => {
  // Sync the latest user info
  await authStore.fetchUser();
  // Fetch initial character state from backend
  await characterStore.fetchActiveCharacter();
  // Fetch initial inventory
  await characterStore.fetchInventory();
  // Fetch all available locations
  await locationStore.fetchLocations();

  // Subscribe to the private WebSocket channel for real-time combat updates
  characterStore.initWebSocket(authStore.user.id);
  
  // Join the presence channel for the current location
  if (characterStore.current_location_id) {
    characterStore.joinLocationChannel(characterStore.current_location_id);
  }

  // TODO Phase 2.2: Remove this polling interval once Reverb is confirmed stable.
  // Kept as a safety net — the HTTP heartbeat still drives server-side logic.
  heartbeatInterval = setInterval(() => {
      characterStore.heartbeatTick();
  }, 2000); // 2 second combat tick
});

onUnmounted(() => {
  if (heartbeatInterval) clearInterval(heartbeatInterval);
  // Close all Echo subscriptions to prevent ghost listeners
  characterStore.destroyWebSocket();
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
