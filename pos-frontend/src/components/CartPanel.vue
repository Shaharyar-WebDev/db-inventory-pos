<template>
  <div style="height: 100%; display: flex; flex-direction: column; border-left: 1px solid #ddd;">

    <!-- Header -->
    <div style="padding: 10px; border-bottom: 1px solid #ddd;">
      <h3 style="margin: 0;">Cart ({{ cart.itemCount }})</h3>
    </div>

    <!-- Cart Items -->
    <div style="flex: 1; overflow-y: auto; padding: 10px;">
      <div v-if="cart.items.length === 0" style="text-align: center; color: #999; padding: 40px;">
        No items in cart
      </div>

      <div
        v-for="item in cart.items"
        :key="item.product_id"
        style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee;"
      >
        <!-- Name -->
        <div style="flex: 1;">
          <div style="font-weight: bold;">{{ item.name }}</div>
          <div style="color: #666; font-size: 13px;">Rs. {{ item.rate }} / {{ item.unit }}</div>
        </div>

        <!-- Qty controls -->
        <div style="display: flex; align-items: center; gap: 4px;">
          <button @click="cart.updateQty(item.product_id, item.qty - 1)" style="width: 28px; height: 28px;">-</button>
          <span style="min-width: 30px; text-align: center;">{{ item.qty }}</span>
          <button @click="cart.updateQty(item.product_id, item.qty + 1)" style="width: 28px; height: 28px;">+</button>
        </div>

        <!-- Total -->
        <div style="min-width: 70px; text-align: right; font-weight: bold;">
          Rs. {{ item.total }}
        </div>

        <!-- Remove -->
        <button @click="cart.removeItem(item.product_id)" style="color: red; background: none; border: none; cursor: pointer; font-size: 18px;">×</button>
      </div>
    </div>

    <!-- Customer selector -->
    <div style="padding: 10px; border-top: 1px solid #ddd;">
      <select
        v-model="cart.selectedCustomer"
        style="width: 100%; padding: 8px; margin-bottom: 8px;"
      >
        <option :value="null">-- Walk-in Customer --</option>
        <option v-for="c in customers" :key="c.id" :value="c">{{ c.name }}</option>
      </select>

      <select
        v-model="cart.selectedPaymentMethod"
        style="width: 100%; padding: 8px; margin-bottom: 8px;"
      >
        <option :value="null">-- Payment Method --</option>
        <option v-for="pm in paymentMethods" :key="pm.id" :value="pm">{{ pm.name }}</option>
      </select>
    </div>

    <!-- Footer -->
    <div style="padding: 10px; border-top: 1px solid #ddd;">
      <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
        <span>Total</span>
        <span>Rs. {{ cart.subtotal }}</span>
      </div>

      <button
        @click="$emit('checkout')"
        :disabled="cart.items.length === 0"
        style="width: 100%; padding: 14px; background: #16a34a; color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer;"
      >
        Charge Rs. {{ cart.subtotal }}
      </button>

      <button
        @click="cart.clearCart()"
        style="width: 100%; padding: 8px; margin-top: 8px; background: none; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;"
      >
        Clear Cart
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import { db } from '@/lib/db'

defineEmits(['checkout'])

const cart           = useCartStore()
const customers      = ref([])
const paymentMethods = ref([])

onMounted(async () => {
  customers.value      = await db.customers.toArray()
  paymentMethods.value = await db.payment_methods.toArray()
})
</script>
