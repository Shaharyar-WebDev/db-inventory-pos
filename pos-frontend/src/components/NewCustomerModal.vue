<template>
  <Transition name="modal">
    <div
      v-if="show"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-[1000] p-4"
    >
      <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <h3 class="text-lg font-semibold text-gray-900">New Customer</h3>
            </div>
            <button
              @click="handleClose"
              :disabled="saving"
              class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full text-red-500 bg-red-50 transition-colors shrink-0 touch-manipulation hover:bg-red-100"
            >
              <X class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-5">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Name <span class="text-red-500">*</span>
            </label>
            <div
              class="relative border rounded-full overflow-hidden transition-all focus-within:ring-2 focus-within:ring-gray-200"
              :class="errors.name ? 'border-red-300' : 'border-gray-200'"
            >
              <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <User class="w-4 h-4" />
              </div>
              <input
                v-model="form.name"
                type="text"
                placeholder="Customer name"
                class="w-full pl-11 pr-4 py-3 text-sm bg-white focus:outline-none"
                :class="errors.name ? 'text-red-500' : 'text-gray-900'"
                @keydown.enter="handleSave"
              />
            </div>
            <Transition name="slide-down">
              <p v-if="errors.name" class="text-red-500 text-xs mt-1">
                {{ errors.name }}
              </p>
            </Transition>
          </div>

          <!-- Contact -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Contact <span class="text-gray-400 text-xs font-normal">(optional)</span>
            </label>
            <div
              class="relative border border-gray-200 rounded-full overflow-hidden focus-within:ring-2 focus-within:ring-gray-200 transition-all"
            >
              <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <Phone class="w-4 h-4" />
              </div>
              <input
                v-model="form.contact"
                type="tel"
                placeholder="Phone number"
                class="w-full pl-11 pr-4 py-3 text-sm bg-white focus:outline-none"
                @keydown.enter="handleSave"
              />
            </div>
          </div>

          <!-- Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Address <span class="text-gray-400 text-xs font-normal">(optional)</span>
            </label>
            <div
              class="relative border border-gray-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-gray-200 transition-all"
            >
              <div class="absolute left-4 top-4 text-gray-400">
                <MapPin class="w-4 h-4" />
              </div>
              <textarea
                v-model="form.address"
                placeholder="Customer address"
                rows="3"
                class="w-full pl-11 pr-4 py-3 text-sm bg-white focus:outline-none resize-none"
              />
            </div>
          </div>

          <!-- General Error -->
          <Transition name="slide-down">
            <div
              v-if="errors.general"
              class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-full px-4 py-3"
            >
              <AlertCircle class="w-4 h-4 text-red-500 shrink-0" />
              <p class="text-red-700 text-xs font-medium">{{ errors.general }}</p>
            </div>
          </Transition>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 border-t border-gray-200 flex gap-3">
          <button
            @click="handleClose"
            :disabled="saving"
            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-full font-medium hover:bg-gray-50 transition-colors touch-manipulation disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Cancel
          </button>
          <button
            @click="handleSave"
            :disabled="saving || !session.appIsOnline || !form.name.trim()"
            class="flex-1 px-4 py-3 bg-gray-900 text-white rounded-full font-semibold hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed touch-manipulation flex items-center justify-center gap-2"
          >
            <Loader v-if="saving" class="w-4 h-4 animate-spin" />
            <Save v-else class="w-4 h-4" />
            {{ saving ? "Saving..." : "Save Customer" }}
          </button>
        </div>

        <!-- Offline Warning -->
        <Transition name="slide-down">
          <div
            v-if="!session.appIsOnline"
            class="mx-6 mb-4 flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-full px-4 py-3"
          >
            <WifiOff class="w-4 h-4 text-amber-500 shrink-0" />
            <p class="text-amber-700 text-xs font-medium">
              You're offline. Customer creation requires internet.
            </p>
          </div>
        </Transition>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, watch } from "vue";
import { useSessionStore } from "@/stores/session";
import { UserPlus, X, User, Phone, MapPin, AlertCircle, Loader, Save, WifiOff } from "@lucide/vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["close", "saved"]);

const session = useSessionStore();

const form = ref({ name: "", contact: "", address: "" });
const errors = ref({});
const saving = ref(false);

// Reset form when modal opens
watch(
  () => props.show,
  (val) => {
    if (val) {
      form.value = { name: "", contact: "", address: "" };
      errors.value = {};
    }
  }
);

function handleClose() {
  if (!saving.value) {
    emit("close");
  }
}

async function handleSave() {
  errors.value = {};

  if (!form.value.name?.trim()) {
    errors.value.name = "Name is required";
    return;
  }

  saving.value = true;
  try {
    const customer = await session.createCustomer(form.value);
    emit("saved", customer);
    emit("close");
  } catch (err) {
    errors.value.general =
      err.response?.data?.message || err || "Failed to save customer";
  } finally {
    saving.value = false;
  }
}
</script>

<style scoped>
/* Modal animations */
.modal-enter-active {
  transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.modal-leave-active {
  transition: all 0.15s cubic-bezier(0.4, 0, 1, 1);
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from > div,
.modal-leave-to > div {
  transform: scale(0.96) translateY(-8px);
}

.modal-enter-active > div {
  transition: transform 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.modal-leave-active > div {
  transition: transform 0.15s cubic-bezier(0.4, 0, 1, 1);
}

/* Slide down animation for errors */
.slide-down-enter-active {
  transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.slide-down-leave-active {
  transition: all 0.15s cubic-bezier(0.4, 0, 1, 1);
}

.slide-down-enter-from {
  opacity: 0;
  transform: translateY(-6px) scale(0.96);
}

.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-4px) scale(0.96);
}

/* Touch optimizations */
@media (max-width: 768px) {
  button,
  input,
  textarea {
    min-height: 48px;
  }
}

/* Focus ring for accessibility */
*:focus-visible {
  outline: 2px solid #1f2937;
  outline-offset: 2px;
}

/* Remove number input arrows */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  opacity: 0.5;
}

/* Textarea resize handle */
textarea {
  resize: vertical;
}
</style>
