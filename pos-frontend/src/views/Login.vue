<template>
  <div
    class="dashboard-auth-layout w-full min-h-screen flex flex-col items-center justify-center overflow-auto px-4"
  >
    <div class="page-fade-in w-full flex justify-center">
      <!-- Card -->
      <div
        class="w-full max-w-sm bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-7"
      >
        <!-- Header -->
        <div class="text-center mb-6">
          <h1 class="text-lg font-semibold text-gray-900">Log in</h1>
          <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Welcome back. Enter your credentials to continue.
          </p>
        </div>

        <!-- Form -->
        <form @submit.prevent="login" class="space-y-4">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"> Email </label>
            <input
              v-model="email"
              type="email"
              placeholder="name@company.com"
              autofocus
              class="w-full rounded-full h-10 px-3 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition"
              :class="{
                'border-red-500 focus:border-red-500 focus:ring-red-500/10': error,
              }"
            />
          </div>

          <!-- Password with toggle -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1"> Password </label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="••••••••"
                @keydown.enter="login"
                class="w-full rounded-full h-10 px-3 text-sm border border-gray-300 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-900 transition pr-10"
                :class="{
                  'border-red-500 focus:border-red-500 focus:ring-red-500/10': error,
                }"
              />
              <button
                type="button"
                @click="togglePasswordVisibility"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
                tabindex="-1"
              >
                <svg
                  v-if="!showPassword"
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>
                <svg
                  v-else
                  class="w-4 h-4"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                  />
                </svg>
              </button>
            </div>
          </div>

          <!-- Animated Error with height transition -->
          <Transition name="error-expand">
            <div v-if="error" class="overflow-hidden">
              <div class="text-sm text-red-600 px-3 py-2">
                {{ error }}
              </div>
            </div>
          </Transition>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full h-10 rounded-full cursor-pointer bg-gray-900 text-white text-sm font-medium hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2"
          >
            <Loader v-if="loading" class="animate-spin w-4 h-4" />
            {{ loading ? "Signing in..." : "Sign in" }}
          </button>

          <!-- Divider -->
          <div class="text-center gap-3 my-4">
            <span class="text-xs text-gray-400">Copyright by Shaharyar Ahmed</span>
          </div>
        </form>
        
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useSessionStore } from "@/stores/session";
import { Loader } from "@lucide/vue";

const email = ref("");
const password = ref("");
const error = ref(null);
const loading = ref(false);
const showPassword = ref(false);

const session = useSessionStore();

function togglePasswordVisibility() {
  showPassword.value = !showPassword.value;
}

async function login() {
  error.value = null;
  loading.value = true;

  try {
    await session.login(email.value, password.value);
    window.location.href = "/terminal/outlets";
  } catch (err) {
    error.value = err.response?.data?.message || "Invalid email or password";
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.page-fade-in {
  animation: pageFadeIn 0.35s ease-out;
}

.gradient-body .dashboard-auth-layout:before {
  content: "";
  position: absolute;
  z-index: 0;
  left: 0;
  right: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  background: repeating-linear-gradient(
    45deg,
    rgba(0, 0, 0, 0.01),
    rgba(0, 0, 0, 0.01) 3px,
    transparent 0,
    transparent 6px
  );
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

/* Smooth height animation - no layout shift jank! */
.error-expand-enter-active,
.error-expand-leave-active {
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  max-height: 100px;
  overflow: hidden;
}

.error-expand-enter-from,
.error-expand-leave-to {
  opacity: 0;
  max-height: 0;
  margin-bottom: 0;
}

.error-expand-enter-to,
.error-expand-leave-from {
  opacity: 1;
  max-height: 100px;
}

.error-expand-enter-active .text-sm,
.error-expand-leave-active .text-sm {
  transition: transform 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.error-expand-enter-from .text-sm {
  transform: translateY(-8px) scale(0.96);
}

.error-expand-enter-to .text-sm {
  transform: translateY(0) scale(1);
}

.error-expand-leave-from .text-sm {
  transform: translateY(0) scale(1);
}

.error-expand-leave-to .text-sm {
  transform: translateY(-6px) scale(0.96);
}
</style>
<style>
.gradient-body {
  animation: movement 4s linear infinite;
}
</style>
