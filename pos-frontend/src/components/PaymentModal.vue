<template>
  <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50;">
    <div style="background: white; border-radius: 12px; padding: 24px; width: 400px;">

      <h3 style="margin: 0 0 16px;">Confirm Payment</h3>

      <!-- Order Summary -->
      <div style="margin-bottom: 16px;">
        <div v-for="item in cart.items" :key="item.product_id" style="display: flex; justify-content: space-between; margin-bottom: 6px; font-size: 14px;">
          <span>{{ item.name }} x{{ item.qty }}</span>
          <span>Rs. {{ item.total }}</span>
        </div>
      </div>

      <div style="border-top: 1px solid #ddd; padding-top: 12px; margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
          <span>Total</span>
          <span>Rs. {{ cart.subtotal }}</span>
        </div>
      </div>

      <!-- Error -->
      <div v-if="error" style="color: red; margin-bottom: 10px; font-size: 14px;">
        {{ error }}
      </div>

      <!-- Buttons -->
      <div style="display: flex; gap: 10px;">
        <button
          @click="$emit('close')"
          style="flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;"
        >
          Cancel
        </button>
        <button
          @click="confirmSale"
          :disabled="loading"
          style="flex: 1; padding: 12px; background: #16a34a; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;"
        >
          {{ loading ? 'Processing...' : 'Confirm Sale' }}
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCartStore } from '@/stores/cart'
import { useSessionStore } from '@/stores/session'
import { queueSale } from '@/lib/sync'

const emit    = defineEmits(['close', 'success'])
const cart    = useCartStore()
const session = useSessionStore()
const loading = ref(false)
const error   = ref('')

async function confirmSale() {
  loading.value = true
  error.value   = ''

  try {
    const payload = cart.buildPayload(session.outlet.id)
    await queueSale(payload)
    emit('success')
  } catch (e) {
    error.value = 'Something went wrong. Sale saved offline.'
  } finally {
    loading.value = false
  }
}
</script>
