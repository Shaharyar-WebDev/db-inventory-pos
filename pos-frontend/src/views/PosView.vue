<template>
    <div style="height: 100vh; display: flex; flex-direction: column;">

        <!-- Top bar -->
        <div
            style="padding: 10px 16px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <strong>{{ session.outlet?.name }} — POS</strong>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 13px; color: #666;">{{ session.user?.name }}</span>
                <span :style="{ color: isOnline ? 'green' : 'red', fontSize: '13px' }">
                    {{ isOnline ? '🟢 Online' : '🔴 Offline' }}
                </span>
                <span v-if="pendingCount > 0" style="color: orange; font-size: 13px;">
                    ⏳ {{ pendingCount }} pending
                </span>
                <button @click="handleLogout" style="padding: 4px 10px;">Logout</button>
            </div>
        </div>

        <!-- Main area -->
        <div style="flex: 1; display: grid; grid-template-columns: 1fr 340px; overflow: hidden;">
            <div style="padding: 10px; overflow: hidden; display: flex; flex-direction: column;">
                <ProductGrid />
            </div>
            <div style="overflow: hidden; display: flex; flex-direction: column;">
                <CartPanel @checkout="showPayment = true" />
            </div>
        </div>

        <!-- Payment Modal -->
        <PaymentModal v-if="showPayment" @close="showPayment = false" @success="onSaleSuccess" />

    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useSessionStore } from '@/stores/session'
import { useCartStore } from '@/stores/cart'
import { syncPendingSales } from '@/lib/sync'
import { db } from '@/lib/db'

import ProductGrid from '@/components/ProductGrid.vue'
import CartPanel from '@/components/CartPanel.vue'
import PaymentModal from '@/components/PaymentModal.vue'

const session = useSessionStore()
const cart = useCartStore()
const router = useRouter()

const showPayment = ref(false)
const isOnline = ref(navigator.onLine)
const pendingCount = ref(0)

// Track online/offline
function onOnline() { isOnline.value = true; syncPendingSales() }
function onOffline() { isOnline.value = false }

onMounted(async () => {
    window.addEventListener('online', onOnline)
    window.addEventListener('offline', onOffline)
    pendingCount.value = await db.pending_sales.where('status').equals('pending').count()
})

onUnmounted(() => {
    window.removeEventListener('online', onOnline)
    window.removeEventListener('offline', onOffline)
})

async function onSaleSuccess() {
    showPayment.value = false
    cart.clearCart()
    pendingCount.value = await db.pending_sales.where('status').equals('pending').count()
}

async function handleLogout() {
    await session.logout()
    router.push('/login')
}
</script>
