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

    <!-- Loot Preview Section -->
    <div class="relative z-10 mt-2">
      <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500 mb-3 flex items-center gap-2">
        <span class="w-1 h-1 rounded-full bg-amber-500"></span>
        Potential Loot
      </h3>
      
      <div v-if="monster.loot_table && monster.loot_table.length > 0" class="flex flex-wrap gap-3">
        <div v-for="loot in monster.loot_table" :key="loot.item_id" class="group relative">
          <!-- Item Icon Wrapper -->
          <div 
            class="w-10 h-10 rounded-lg bg-zinc-900/80 border-2 flex items-center justify-center text-xl shadow-lg transition-all group-hover:scale-110 opacity-70 group-hover:opacity-100 cursor-help"
            :class="getRarityBorderClass(loot.rarity)"
          >
            {{ getItemEmoji(loot.slot) }}
          </div>
          
          <!-- Drop Percentage -->
          <div class="text-center mt-1">
            <span class="text-[9px] font-mono text-zinc-500 group-hover:text-zinc-300 transition-colors">
              {{ loot.chance }}%
            </span>
          </div>

          <!-- Hover Tooltip -->
          <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-zinc-950 border border-zinc-800 rounded-xl p-3 shadow-2xl opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 z-50 scale-90 group-hover:scale-100">
            <div class="flex items-center justify-between mb-1.5">
              <span class="text-[10px] font-bold text-zinc-100 leading-tight">{{ loot.name }}</span>
              <span :class="getRarityTextClass(loot.rarity)" class="text-[7px] font-black uppercase px-1.5 py-0.5 rounded leading-none border">
                {{ loot.rarity }}
              </span>
            </div>
            
            <div class="flex flex-wrap gap-1 mt-2">
              <span v-for="(val, stat) in loot.bonuses" :key="stat" class="text-[8px] font-mono text-zinc-400 border border-zinc-800/50 bg-zinc-900/50 px-1.5 py-0.5 rounded">
                +{{ val }} {{ stat }}
              </span>
            </div>
            
            <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-zinc-800"></div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-[11px] text-zinc-600 italic py-2">
        No potential drops
      </div>
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

const getRarityBorderClass = (rarity) => {
  switch(rarity) {
    case 'common': return 'border-zinc-800';
    case 'uncommon': return 'border-emerald-900/50 group-hover:border-emerald-500 shadow-emerald-900/10';
    case 'rare': return 'border-blue-900/50 group-hover:border-blue-500 shadow-blue-900/10';
    case 'epic': return 'border-purple-900/50 group-hover:border-purple-500 shadow-purple-900/10';
    case 'legendary': return 'border-orange-900/50 group-hover:border-orange-500 shadow-orange-900/10';
    default: return 'border-zinc-800';
  }
};

const getRarityTextClass = (rarity) => {
  switch(rarity) {
    case 'common': return 'text-zinc-500 border-zinc-800 bg-zinc-900';
    case 'uncommon': return 'text-emerald-400 border-emerald-900/50 bg-emerald-950/30';
    case 'rare': return 'text-blue-400 border-blue-900/50 bg-blue-950/30';
    case 'epic': return 'text-purple-400 border-purple-900/50 bg-purple-950/30';
    case 'legendary': return 'text-orange-400 border-orange-900/50 bg-orange-950/30';
    default: return 'text-zinc-500 border-zinc-800 bg-zinc-900';
  }
};
</script>
