<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-400 mx-auto px-4 sm:px-6 py-3 flex justify-between items-center gap-3">

      <!-- Outlet Name -->
      <span class="text-sm font-medium text-gray-800 bg-gray-50 py-1.5 px-3.5 rounded-full border border-gray-200 truncate max-w-45 sm:max-w-xs">
        {{ session.outlet?.name || "Unknown Outlet" }}
      </span>

      <!-- Actions -->
      <div class="flex items-center gap-2 shrink-0">

        <!-- Online/Offline Indicator -->
        <span
          :class="[
            'inline-flex items-center gap-1.5 py-1.5 px-3 text-[0.813rem] font-medium rounded-full border transition-all duration-300',
            session.appIsOnline
              ? 'bg-green-50 border-green-200 text-green-700'
              : 'bg-red-50 border-red-200 text-red-700'
          ]"
        >
          <component
            :is="session.appIsOnline ? Wifi : WifiOff"
            class="w-3.5 h-3.5 shrink-0"
          />
          <span class="hidden sm:inline">{{ session.appIsOnline ? 'Online' : 'Offline' }}</span>
        </span>

        <!-- Refresh -->
        <button
          @click="$emit('refresh')"
          :disabled="session.refreshing"
          class="inline-flex items-center gap-2 py-1.5 px-3 sm:px-4 text-[0.813rem] font-medium rounded-full transition-all duration-200 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed touch-manipulation"
        >
          <RefreshCw class="w-4 h-4 shrink-0" />
          <span class="hidden sm:inline">Refresh</span>
        </button>

        <!-- Logout -->
        <button
          @click="session.logout"
          :disabled="session.refreshing"
          class="inline-flex items-center gap-2 py-1.5 px-3 sm:px-4 text-[0.813rem] font-medium rounded-full transition-all duration-200 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 active:scale-[0.98]"
        >
          <LogOut class="w-4 h-4 shrink-0" />
          <span class="hidden sm:inline">Logout</span>
        </button>

        <!-- Switch Outlet -->
        <button
          @click="router.push('/outlets')"
          :disabled="session.refreshing || !session.appIsOnline"
          class="inline-flex items-center gap-2 py-1.5 px-3 sm:px-4 text-[0.813rem] font-medium rounded-full transition-all duration-200 bg-gray-800 border border-gray-800 disabled:cursor-not-allowed text-white hover:bg-gray-900 hover:border-gray-900 active:scale-[0.98]"
        >
          <ArrowLeftRight class="w-4 h-4 shrink-0" />
          <span class="hidden sm:inline">Switch Outlet</span>
        </button>

      </div>
    </div>
  </header>
</template>

<script setup>
import { Wifi, WifiOff, RefreshCw, LogOut, ArrowLeftRight } from "@lucide/vue";
import { useSessionStore } from "@/stores/session";
import router from "@/router";

defineEmits(["refresh"]);
const session = useSessionStore();
</script>
