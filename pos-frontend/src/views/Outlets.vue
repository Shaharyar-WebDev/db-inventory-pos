<template>
  <div
    class="outlet-selector-layout w-full min-h-screen flex flex-col items-center justify-center overflow-auto px-4"
  >
    <div class="page-fade-in w-full flex justify-center">
      <!-- Card -->
      <div
        class="w-full max-w-sm bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-7"
      >
        <!-- Header -->
        <div class="text-center mb-6">
          <h1 class="text-lg font-semibold text-gray-900">Select Outlet</h1>
          <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Choose outlet to continue
          </p>
        </div>

        <!-- Outlets List -->
        <div class="space-y-2">
          <div
            v-for="outlet in session.outlets"
            :key="outlet.id"
            class="cursor-pointer px-4 py-3 border border-gray-200 rounded-full text-sm text-gray-700 transition-all duration-200 bg-white hover:border-gray-400 hover:bg-gray-50 active:scale-[0.98]"
            @click="setOutlet(outlet)"
          >
            {{ outlet.name }}
          </div>
        </div>

        <!-- Loading Overlay -->
        <!-- <Transition name="fade">
          <div v-if="loading" class="loading-overlay">
            <div class="loading-spinner"></div>
          </div>
        </Transition> -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useSessionStore } from "@/stores/session";

const session = useSessionStore();
const loading = ref(false);

async function setOutlet(outlet) {
  loading.value = true;

  try {
    await session.selectOutlet(outlet);
    window.location.href = "/terminal/";
  } catch (error) {
    loading.value = false;
    console.error("Failed to select outlet:", error);
  }
}
</script>

<style scoped>
.page-fade-in {
  animation: pageFadeIn 0.35s ease-out;
}

@keyframes pageFadeIn {
  0% {
    opacity: 0;
    transform: translateX(-12px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Loading overlay - minimal */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.loading-spinner {
  width: 32px;
  height: 32px;
  border: 2px solid #e5e7eb;
  border-top-color: #1f2937;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Space between items */
.space-y-2 > * + * {
  margin-top: 0.5rem;
}
</style>
