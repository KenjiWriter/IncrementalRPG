<template>
  <div class="min-h-screen bg-zinc-950 flex flex-col items-center justify-center relative overflow-hidden font-sans">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-900/10 via-zinc-950/80 to-zinc-950 pointer-events-none"></div>

    <div class="z-10 flex flex-col items-center max-w-[90%] md:max-w-md w-full px-4">
      <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-rose-600 to-indigo-600 flex items-center justify-center shadow-2xl shadow-rose-500/20 mb-8 border border-white/10">
        <span class="font-bold text-white tracking-widest text-3xl font-serif">ES</span>
      </div>
      
      <h1 class="text-4xl md:text-5xl font-bold text-center tracking-widest text-transparent bg-clip-text bg-gradient-to-b from-zinc-100 to-zinc-500 font-serif mb-2">
        ETERNAL<br/>SHARD
      </h1>
      <p class="text-zinc-500 text-sm tracking-[0.3em] uppercase mb-12 text-center font-semibold">Awaken your soul</p>

      <!-- Auth Card -->
      <div class="w-full bg-zinc-900/60 backdrop-blur-xl border border-zinc-800/80 rounded-2xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        
        <!-- Toggle -->
        <div class="flex bg-zinc-950/50 p-1.5 rounded-xl mb-8 border border-zinc-800/50 shadow-inner">
          <button 
            @click="isLogin = true"
            :class="isLogin ? 'bg-zinc-800/80 text-zinc-100 shadow border-zinc-700' : 'text-zinc-500 hover:text-zinc-300 border-transparent'"
            class="flex-1 py-2.5 text-xs font-bold uppercase tracking-widest rounded-lg transition-all border duration-300">
            Enter Domain
          </button>
          <button 
            @click="isLogin = false"
            :class="!isLogin ? 'bg-zinc-800/80 text-zinc-100 shadow border-zinc-700' : 'text-zinc-500 hover:text-zinc-300 border-transparent'"
            class="flex-1 py-2.5 text-xs font-bold uppercase tracking-widest rounded-lg transition-all border duration-300">
            Awaken Soul
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-5">
          <div v-show="!isLogin" class="space-y-1">
            <label class="text-[10px] uppercase tracking-widest text-zinc-400 font-bold ml-1">True Name</label>
            <input 
              v-model="form.name" 
              type="text" 
              :required="!isLogin"
              class="w-full bg-zinc-950/50 border border-zinc-800 rounded-xl px-4 py-3.5 text-zinc-100 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/50 transition-all placeholder-zinc-700"
              placeholder="e.g. Ashen One"
            />
          </div>

          <div class="space-y-1">
            <label class="text-[10px] uppercase tracking-widest text-zinc-400 font-bold ml-1">Soul Signature (Email)</label>
            <input 
              v-model="form.email" 
              type="email" 
              required
              class="w-full bg-zinc-950/50 border border-zinc-800 rounded-xl px-4 py-3.5 text-zinc-100 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/50 transition-all placeholder-zinc-700 font-mono text-sm"
              placeholder="Enter signature"
            />
          </div>

          <div class="space-y-1">
            <label class="text-[10px] uppercase tracking-widest text-zinc-400 font-bold ml-1">Resonance (Password)</label>
            <input 
              v-model="form.password" 
              type="password" 
              required
              class="w-full bg-zinc-950/50 border border-zinc-800 rounded-xl px-4 py-3.5 text-zinc-100 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/50 transition-all font-mono"
            />
          </div>

          <div v-show="!isLogin" class="space-y-1">
            <label class="text-[10px] uppercase tracking-widest text-zinc-400 font-bold ml-1">Confirm Resonance</label>
            <input 
              v-model="form.password_confirmation" 
              type="password" 
              :required="!isLogin"
              class="w-full bg-zinc-950/50 border border-zinc-800 rounded-xl px-4 py-3.5 text-zinc-100 focus:outline-none focus:border-indigo-500/50 focus:ring-1 focus:ring-indigo-500/50 transition-all font-mono"
            />
          </div>
          
          <div v-if="errorMessage" class="text-rose-400 text-xs font-medium text-center mt-2 px-3 py-2.5 bg-rose-950/30 rounded-lg border border-rose-900/50">
            {{ errorMessage }}
          </div>

          <button 
            type="submit" 
            :disabled="isLoading"
            class="w-full bg-gradient-to-r from-zinc-800 to-zinc-700 hover:from-indigo-700 hover:to-indigo-600 text-white font-bold uppercase tracking-widest text-sm py-4 rounded-xl shadow-lg border border-zinc-600 hover:border-indigo-400 transition-all duration-500 mt-6 disabled:opacity-50 disabled:grayscale flex justify-center items-center group relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white relative z-10" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="relative z-10 font-serif mt-0.5">{{ isLogin ? 'Manifest' : 'Ignite Soul' }}</span>
          </button>
        </form>
      </div>
      
      <div class="mt-12 text-[10px] text-zinc-600 tracking-[0.2em] uppercase font-mono">
        v0.2.0 • Genesis
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../store/useAuthStore';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const isLogin = ref(true);
const isLoading = ref(false);
const errorMessage = ref('');

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const handleSubmit = async () => {
  isLoading.value = true;
  errorMessage.value = '';
  
  try {
    if (isLogin.value) {
      await authStore.login({
        email: form.value.email,
        password: form.value.password
      });
    } else {
      await authStore.register({
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation
      });
    }
    
    // Redirect on success
    router.push('/dashboard');
  } catch (error) {
    if (error.response?.data?.errors) {
        const errors = error.response.data.errors;
        const firstKey = Object.keys(errors)[0];
        errorMessage.value = errors[firstKey][0];
    } else if (error.response?.data?.message) {
        errorMessage.value = error.response.data.message;
    } else {
        errorMessage.value = 'An ethereal disturbance occurred. Try again.';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap');

.font-serif {
  font-family: 'Cinzel', serif;
}
.font-sans {
  font-family: 'Inter', sans-serif;
}
.font-mono {
  font-family: 'Roboto Mono', monospace;
}
</style>
