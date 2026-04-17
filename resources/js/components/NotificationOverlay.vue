<template>
  <!-- Teleport to body so the overlay sits above all route views -->
  <Teleport to="body">
    <div class="notification-overlay" aria-live="polite" aria-label="Global loot notifications">
      <TransitionGroup name="toast" tag="div" class="toast-stack">
        <div
          v-for="notif in notifications"
          :key="notif.id"
          class="toast"
          role="alert"
        >
          <div class="toast-icon">⚔️</div>
          <div class="toast-body">
            <span class="toast-player">{{ notif.characterName }}</span>
            <span class="toast-verb"> just found </span>
            <span class="toast-item">{{ notif.itemName }}</span>
            <span class="toast-verb">!</span>
          </div>
          <button
            class="toast-close"
            aria-label="Dismiss"
            @click="dismiss(notif.id)"
          >×</button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

/** Maximum number of simultaneous toasts visible. */
const MAX_TOASTS = 3;
/** Auto-dismiss delay in milliseconds. */
const DISMISS_DELAY = 4500;

const notifications = ref([]);
const timers = new Map();

function addNotification(characterName, itemName) {
  const id = `${Date.now()}-${Math.random()}`;

  // Drop oldest if at capacity
  if (notifications.value.length >= MAX_TOASTS) {
    const oldest = notifications.value[0];
    dismiss(oldest.id);
  }

  notifications.value.push({ id, characterName, itemName });

  timers.set(id, setTimeout(() => dismiss(id), DISMISS_DELAY));
}

function dismiss(id) {
  notifications.value = notifications.value.filter(n => n.id !== id);
  if (timers.has(id)) {
    clearTimeout(timers.get(id));
    timers.delete(id);
  }
}

onMounted(() => {
  if (!window.Echo) {
    console.warn('[Echo] NotificationOverlay: window.Echo not ready.');
    return;
  }

  window.Echo.channel('global-loot').listen('.GlobalLootEvent', (e) => {
    addNotification(e.character_name, e.item_name);
  });
});

onUnmounted(() => {
  // Clear all pending dismiss timers
  timers.forEach(clearTimeout);
  timers.clear();

  // Leave the public channel cleanly
  if (window.Echo) {
    window.Echo.leave('global-loot');
  }
});
</script>

<style scoped>
.notification-overlay {
  position: fixed;
  top: 1.25rem;
  right: 1.25rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  pointer-events: none;
  max-width: 22rem;
}

.toast-stack {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.toast {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.65rem 0.9rem;
  border-radius: 0.75rem;
  background: rgba(20, 15, 8, 0.92);
  border: 1px solid rgba(202, 138, 4, 0.35);
  backdrop-filter: blur(12px);
  box-shadow:
    0 0 0 1px rgba(202, 138, 4, 0.12),
    0 4px 24px rgba(0, 0, 0, 0.6),
    0 0 18px rgba(202, 138, 4, 0.08);
  pointer-events: all;
  cursor: default;
  animation: glow-pulse 2.5s ease-in-out infinite alternate;
}

@keyframes glow-pulse {
  from { box-shadow: 0 0 0 1px rgba(202, 138, 4, 0.12), 0 4px 24px rgba(0,0,0,0.6), 0 0 10px rgba(202,138,4,0.06); }
  to   { box-shadow: 0 0 0 1px rgba(202, 138, 4, 0.30), 0 4px 24px rgba(0,0,0,0.6), 0 0 24px rgba(202,138,4,0.18); }
}

.toast-icon {
  font-size: 1rem;
  flex-shrink: 0;
  filter: drop-shadow(0 0 4px rgba(202, 138, 4, 0.6));
}

.toast-body {
  flex: 1;
  font-size: 0.78rem;
  line-height: 1.4;
  color: #d4d4d8;
  font-family: 'Inter', sans-serif;
}

.toast-player {
  font-weight: 700;
  color: #e4e4e7;
}

.toast-verb {
  color: #a1a1aa;
}

.toast-item {
  font-weight: 700;
  color: #fbbf24;
  text-shadow:
    0 0 8px rgba(251, 191, 36, 0.7),
    0 0 16px rgba(251, 191, 36, 0.35);
}

.toast-close {
  background: none;
  border: none;
  color: #52525b;
  font-size: 1rem;
  line-height: 1;
  cursor: pointer;
  padding: 0 0.2rem;
  flex-shrink: 0;
  transition: color 0.15s ease;
}

.toast-close:hover {
  color: #a1a1aa;
}

/* TransitionGroup animations */
.toast-enter-active {
  transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}
.toast-leave-active {
  transition: all 0.25s ease-in;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(calc(100% + 1.5rem));
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(calc(100% + 1.5rem));
}
.toast-move {
  transition: transform 0.3s ease;
}
</style>
