<template>
  <router-view v-slot="{ Component }">
    <transition name="fade" mode="out-in">
      <component :is="Component" />
    </transition>
  </router-view>

  <!-- Global loot notification toasts — teleported to <body> internally -->
  <NotificationOverlay />
</template>

<script setup>
import NotificationOverlay from './components/NotificationOverlay.vue';
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 1s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
