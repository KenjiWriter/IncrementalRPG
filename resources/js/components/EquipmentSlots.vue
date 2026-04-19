<template>
  <div class="flex flex-col gap-4">
    <div class="grid grid-cols-2 gap-4">
      <div v-for="slot in displaySlots" :key="slot.id" 
           class="relative aspect-square w-16 h-16 rounded-xl border-2 flex items-center justify-center transition-all duration-300"
           :class="getSlotClasses(slot.id)">
        
        <!-- Equipped Item Icon -->
        <div v-if="getEquippedInSlot(slot.id)" class="w-full h-full flex items-center justify-center cursor-help group" @click="handleSlotClick(slot.id)">
          <span class="text-2xl drop-shadow-lg">{{ getSlotEmoji(slot.id) }}</span>
          
          <!-- Tooltip on hover -->
          <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-zinc-900 border border-zinc-800 rounded-lg p-3 opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50 shadow-2xl">
            <h4 class="text-xs font-bold text-white mb-1">{{ getEquippedInSlot(slot.id).name }}</h4>
            <div class="flex flex-wrap gap-1 mb-2">
              <span v-for="(val, stat) in getEquippedInSlot(slot.id).bonuses" :key="stat" class="text-[9px] font-mono text-emerald-400 bg-emerald-500/10 px-1 rounded">
                +{{ val }} {{ stat }}
              </span>
            </div>
            <p class="text-[10px] text-zinc-500 italic">Click to unequip</p>
          </div>
        </div>

        <!-- Empty Slot Placeholder -->
        <div v-else class="text-zinc-700/50 text-xl font-bold select-none">
          {{ getSlotEmojiPlaceholder(slot.id) }}
        </div>

        <!-- Slot Label -->
        <span class="absolute -top-1.5 -right-1.5 bg-zinc-950 border border-zinc-800 text-[8px] font-black uppercase px-1.5 py-0.5 rounded text-zinc-500 tracking-tighter">
          {{ slot.label }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCharacterStore } from '../store/useCharacterStore';

const characterStore = useCharacterStore();

const displaySlots = [
  { id: 'head', label: 'Helm' },
  { id: 'chest', label: 'Body' },
  { id: 'weapon', label: 'Main' },
  { id: 'off_hand', label: 'Off' },
  { id: 'ring', label: 'Ring' },
  { id: 'accessory', label: 'Neck' },
];

const getEquippedInSlot = (slotId) => {
  return characterStore.inventory.find(i => i.is_equipped && i.slot === slotId);
};

const getSlotEmoji = (slotId) => {
  const item = getEquippedInSlot(slotId);
  if (!item) return '';
  switch(slotId) {
    case 'head': return '🪖';
    case 'chest': return '🛡️';
    case 'weapon': return '⚔️';
    case 'off_hand': return '🛡️';
    case 'ring': return '💍';
    case 'accessory': return '📿';
    default: return '📦';
  }
};

const getSlotEmojiPlaceholder = (slotId) => {
  switch(slotId) {
    case 'head': return '⛑️';
    case 'chest': return '👕';
    case 'weapon': return '🗡️';
    case 'off_hand': return '💠';
    case 'ring': return '⭕';
    case 'accessory': return '🎗️';
    default: return '□';
  }
};

const getSlotClasses = (slotId) => {
  const item = getEquippedInSlot(slotId);
  if (item) {
    switch(item.rarity) {
      case 'uncommon': return 'border-emerald-500/40 bg-emerald-500/5 shadow-[0_0_15px_-5px_theme(colors.emerald.500)]';
      case 'rare': return 'border-blue-500/40 bg-blue-500/5 shadow-[0_0_15px_-5px_theme(colors.blue.500)]';
      case 'epic': return 'border-purple-500/40 bg-purple-500/5 shadow-[0_0_15px_-5px_theme(colors.purple.500)]';
      case 'legendary': return 'border-orange-500/40 bg-orange-500/5 shadow-[0_0_15px_-5px_theme(colors.orange.500)]';
      default: return 'border-zinc-700 bg-zinc-800/20';
    }
  }
  return 'border-zinc-800/50 bg-zinc-950/40 opacity-40 grayscale';
};

const handleSlotClick = async (slotId) => {
  const item = getEquippedInSlot(slotId);
  if (item) {
    await characterStore.unequipItem(item.id);
  }
};
</script>
