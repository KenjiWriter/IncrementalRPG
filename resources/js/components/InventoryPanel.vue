<template>
  <div class="bg-zinc-900/60 backdrop-blur-xl border border-zinc-800/80 rounded-2xl overflow-hidden shadow-2xl flex flex-col h-full max-h-[500px]">
    <div class="p-4 border-b border-zinc-800/60 flex items-center justify-between bg-zinc-950/20">
      <div class="flex items-center gap-2">
        <span class="text-xl">🎒</span>
        <h3 class="font-bold text-zinc-100 tracking-wide uppercase text-sm">Inventory</h3>
      </div>
      <span class="text-[10px] font-mono text-zinc-500 bg-zinc-950 px-2 py-0.5 rounded border border-zinc-800/50">
        {{ unequippedItems.length }} / 50 Items
      </span>
    </div>

    <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
      <div v-if="unequippedItems.length === 0" class="flex flex-col items-center justify-center py-12 opacity-30 select-none">
        <span class="text-4xl mb-2">🍃</span>
        <p class="text-xs font-medium uppercase tracking-widest text-zinc-400">Empty bag</p>
      </div>

      <div v-else class="flex flex-col gap-2">
        <div v-for="item in unequippedItems" :key="item.id" 
             class="group bg-zinc-950/30 hover:bg-zinc-800/40 border border-zinc-800/50 hover:border-zinc-700/80 rounded-xl p-3 transition-all duration-200">
          
          <div class="flex items-start justify-between gap-4">
            <div class="flex gap-3">
              <div class="w-10 h-10 rounded-lg bg-zinc-900 border border-zinc-800 flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
                {{ getItemEmoji(item.slot) }}
              </div>
              <div class="flex flex-col">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-bold text-zinc-200 group-hover:text-white transition-colors">{{ item.name }}</span>
                  <span :class="getRarityClasses(item.rarity)" class="text-[8px] font-black uppercase px-1.5 py-0.5 rounded leading-none">
                    {{ item.rarity }}
                  </span>
                </div>
                <p class="text-[10px] text-zinc-500 mt-0.5">{{ item.description }}</p>
                <div class="flex flex-wrap gap-1.5 mt-2">
                  <span v-for="(val, stat) in item.bonuses" :key="stat" class="text-[9px] font-mono text-zinc-400 border border-zinc-800/50 bg-zinc-900/50 px-1.5 py-0.5 rounded">
                    +{{ val }} <span class="text-[8px] opacity-60">{{ stat }}</span>
                  </span>
                </div>
              </div>
            </div>

            <button @click="characterStore.equipItem(item.id)" 
                    class="bg-zinc-800 hover:bg-indigo-600 text-zinc-300 hover:text-white px-3 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-widest border border-zinc-700 hover:border-indigo-500 transition-all active:scale-95 cursor-pointer">
              Equip
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCharacterStore } from '../store/useCharacterStore';

const characterStore = useCharacterStore();

const unequippedItems = computed(() => {
  return characterStore.inventory.filter(i => !i.is_equipped);
});

const getItemEmoji = (slot) => {
  switch(slot) {
    case 'head': return '🪖';
    case 'chest': return '🛡️';
    case 'weapon': return '⚔️';
    case 'off_hand': return '🛡️';
    case 'ring': return '💍';
    case 'accessory': return '📿';
    default: return '📦';
  }
};

const getRarityClasses = (rarity) => {
  switch(rarity) {
    case 'common': return 'bg-zinc-800 text-zinc-400 border border-zinc-700';
    case 'uncommon': return 'bg-emerald-950/50 text-emerald-400 border border-emerald-800/50';
    case 'rare': return 'bg-blue-950/50 text-blue-400 border border-blue-800/50';
    case 'epic': return 'bg-purple-950/50 text-purple-400 border border-purple-800/50';
    case 'legendary': return 'bg-orange-950/50 text-orange-400 border border-orange-800/50';
    default: return 'bg-zinc-800 text-zinc-400';
  }
};
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
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #3f3f46;
}
</style>
