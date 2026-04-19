<template>
  <div class="bg-zinc-900/40 border border-zinc-800/50 rounded-xl p-4 flex flex-col gap-4">
    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-500 mb-1 flex items-center gap-2">
      <span class="w-1 h-1 rounded-full bg-indigo-500"></span>
      Attributes
    </h3>
    
    <div class="grid grid-cols-2 gap-3">
      <!-- Strength -->
      <div class="bg-zinc-950/40 border border-zinc-800/30 rounded-lg p-2.5 flex items-center justify-between group hover:border-orange-500/30 transition-colors">
        <div class="flex items-center gap-2">
          <span class="text-lg opacity-70 group-hover:opacity-100 transition-opacity">💪</span>
          <div class="flex flex-col">
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-tighter">Strength</span>
            <span class="text-sm font-bold text-zinc-200">{{ characterStore.strength }}</span>
          </div>
        </div>
      </div>

      <!-- Dexterity -->
      <div class="bg-zinc-950/40 border border-zinc-800/30 rounded-lg p-2.5 flex items-center justify-between group hover:border-emerald-500/30 transition-colors">
        <div class="flex items-center gap-2">
          <span class="text-lg opacity-70 group-hover:opacity-100 transition-opacity">⚡</span>
          <div class="flex flex-col">
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-tighter">Dexterity</span>
            <span class="text-sm font-bold text-zinc-200">{{ characterStore.dexterity }}</span>
          </div>
        </div>
      </div>

      <!-- Intelligence -->
      <div class="bg-zinc-950/40 border border-zinc-800/30 rounded-lg p-2.5 flex items-center justify-between group hover:border-blue-500/30 transition-colors">
        <div class="flex items-center gap-2">
          <span class="text-lg opacity-70 group-hover:opacity-100 transition-opacity">🧠</span>
          <div class="flex flex-col">
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-tighter">Intelligence</span>
            <span class="text-sm font-bold text-zinc-200">{{ characterStore.intelligence }}</span>
          </div>
        </div>
      </div>

      <!-- Vitality -->
      <div class="bg-zinc-950/40 border border-zinc-800/30 rounded-lg p-2.5 flex items-center justify-between group hover:border-rose-500/30 transition-colors">
        <div class="flex items-center gap-2">
          <span class="text-lg opacity-70 group-hover:opacity-100 transition-opacity">❤️</span>
          <div class="flex flex-col">
            <span class="text-[9px] font-bold text-zinc-500 uppercase tracking-tighter">Vitality</span>
            <span class="text-sm font-bold text-zinc-200">{{ characterStore.vitality }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Derived Combat Stats -->
    <div class="mt-2 pt-3 border-t border-zinc-800/50 grid grid-cols-3 gap-2">
      <div class="flex flex-col items-center p-2 rounded-lg bg-red-950/10 border border-red-900/10">
        <span class="text-[8px] font-black uppercase text-red-500/80 tracking-widest">Attack</span>
        <span class="text-sm font-mono font-bold text-red-400">{{ characterStore.attack }}</span>
      </div>
      <div class="flex flex-col items-center p-2 rounded-lg bg-blue-950/10 border border-blue-900/10">
        <span class="text-[8px] font-black uppercase text-blue-500/80 tracking-widest">Defense</span>
        <span class="text-sm font-mono font-bold text-blue-400">{{ characterStore.defense }}</span>
      </div>
      <div class="flex flex-col items-center p-2 rounded-lg bg-yellow-950/10 border border-yellow-900/10">
        <span class="text-[8px] font-black uppercase text-yellow-500/80 tracking-widest">Speed</span>
        <span class="text-sm font-mono font-bold text-yellow-400">{{ characterStore.speed }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCharacterStore } from '../store/useCharacterStore';
const characterStore = useCharacterStore();
</script>
