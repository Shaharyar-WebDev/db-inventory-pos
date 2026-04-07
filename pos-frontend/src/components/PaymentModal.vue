<template>
  <Transition name="modal">
    <div
      v-if="show"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-1000 p-4"
    >
      <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Payment</h3>
            <button
              @click="$emit('close')"
              class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full text-red-500 bg-red-50 transition-colors shrink-0 touch-manipulation"
            >
              <X class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-5">
          <!-- Grand Total -->
          <div class="bg-gray-50 rounded-full p-4 text-center">
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wide mb-1">Grand Total</p>
            <p class="text-3xl font-bold text-gray-900">₨ {{ formatPrice(cart.grandTotal) }}</p>
          </div>

          <!-- Account Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Account <span class="text-red-500">*</span>
            </label>
            <div
              class="relative border rounded-full overflow-hidden transition-all focus-within:ring-2 focus-within:ring-gray-200"
              :class="!cart.accountId ? 'border-red-300' : 'border-gray-200'"
            >
              <select
                :value="cart.accountId"
                @change="cart.accountId = Number($event.target.value) || null"
                class="w-full px-4 py-3 text-sm bg-white focus:outline-none appearance-none cursor-pointer"
              >
                <option :value="null">-- Select Account --</option>
                <option v-for="account in session.accounts" :key="account.id" :value="account.id">
                  {{ account.name }}{{ account.account_number ? ` (${account.account_number})` : '' }}
                </option>
              </select>
              <ChevronDown class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
            </div>
            <p v-if="!cart.accountId" class="text-red-500 text-sm mt-1">Account is required</p>
          </div>

          <!-- Amount Paid -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid (₨)</label>
            <div
              class="flex items-center border rounded-full overflow-hidden transition-all focus-within:ring-2 focus-within:ring-gray-200"
              :class="isWalkIn && cart.amountPaid < cart.grandTotal ? 'border-red-300' : 'border-gray-200'"
            >
              <span class="px-4 py-3 text-gray-500 bg-gray-50 border-r border-gray-200">₨</span>
              <input
                type="number"
                min="0"
                step="1"
                :value="cart.amountPaid"
                @input="cart.amountPaid = Number($event.target.value)"
                class="flex-1 px-4 py-3 text-sm bg-white focus:outline-none"
                placeholder="0.00"
              />
            </div>
          </div>

          <!-- Payment Status Banner -->
            <div
              v-if="paymentBanner"
              :key="paymentBanner.key"
              class="flex items-center gap-2 rounded-full px-4 py-3"
              :class="paymentBanner.classes"
            >
              <component :is="paymentBanner.icon" class="w-4 h-4 shrink-0" :class="paymentBanner.iconClass" />
              <p class="text-sm font-medium" :class="paymentBanner.textClass">{{ paymentBanner.message }}</p>
            </div>

          <!-- Balance Due -->
          <div
            v-if="cart.amountPaid <= cart.grandTotal"
            class="flex justify-between items-center pt-2 border-t border-gray-100"
          >
            <span class="text-sm text-gray-500">Balance Due</span>
            <span class="text-xl font-bold text-gray-900">₨ {{ formatPrice(cart.grandTotal - cart.amountPaid) }}</span>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 border-t border-gray-200 flex gap-3">
          <button
            @click="$emit('close')"
            class="flex-1 cursor-pointer px-4 py-3 border border-gray-300 text-gray-700 rounded-full font-medium hover:bg-gray-50 transition-colors touch-manipulation"
          >
            Cancel
          </button>
          <button
            @click="$emit('submit')"
            :disabled="submitting || !cart.accountId || (isWalkIn && cart.amountPaid < cart.grandTotal)"
            class="flex-1 cursor-pointer px-4 py-3 bg-gray-900 text-white rounded-full font-semibold hover:bg-gray-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed touch-manipulation flex items-center justify-center gap-2"
          >
            <Loader v-if="submitting" class="w-4 h-4 animate-spin" />
            <Save v-else class="w-4 h-4" />
            {{ submitting ? 'Processing...' : session.appIsOnline ? 'Save Sale' : 'Queue Offline' }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useSessionStore } from '@/stores/session'
import { X, ChevronDown, AlertCircle, Info, Wallet, Loader, Save } from '@lucide/vue'

const props = defineProps({
  show: Boolean,
  submitting: Boolean,
  isWalkIn: Boolean,
})

defineEmits(['close', 'submit'])

const cart = useCartStore()
const session = useSessionStore()

const paymentBanner = computed(() => {
  const { amountPaid, grandTotal } = cart
  if (props.isWalkIn && amountPaid < grandTotal) {
    return {
      key: 'walkin',
      classes: 'bg-red-50 border border-red-200',
      icon: AlertCircle,
      iconClass: 'text-red-500',
      textClass: 'text-red-700',
      message: `Walk-in customers must pay in full (₨ ${formatPrice(grandTotal)})`,
    }
  }
  if (!props.isWalkIn && amountPaid < grandTotal) {
    return {
      key: 'credit',
      classes: 'bg-yellow-50 border border-yellow-200',
      icon: Info,
      iconClass: 'text-yellow-600',
      textClass: 'text-yellow-800',
      message: `Balance ₨ ${formatPrice(grandTotal - amountPaid)} will be added to customer's credit`,
    }
  }
  if (amountPaid > grandTotal) {
    return {
      key: 'change',
      classes: 'bg-green-50 border border-green-200',
      icon: Wallet,
      iconClass: 'text-green-600',
      textClass: 'text-green-700',
      message: `Change to return: ₨ ${formatPrice(amountPaid - grandTotal)}`,
    }
  }
  return null
})

function formatPrice(price) {
  return price?.toLocaleString() || '0'
}
</script>

<style scoped>
.modal-enter-active { transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1); }
.modal-leave-active { transition: all 0.15s cubic-bezier(0.4, 0, 1, 1); }
.modal-enter-from,
.modal-leave-to { opacity: 0; }
.modal-enter-from > div,
.modal-leave-to > div { transform: scale(0.96) translateY(-8px); }
.modal-enter-active > div { transition: transform 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1); }
.modal-leave-active > div { transition: transform 0.15s cubic-bezier(0.4, 0, 1, 1); }

input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button { opacity: 0.5; }
input[type='number'] { -moz-appearance: textfield; }

@media (max-width: 768px) {
  button, select, input { min-height: 48px; }
}

*:focus-visible { outline: 2px solid #1f2937; outline-offset: 2px; }
select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
</style>