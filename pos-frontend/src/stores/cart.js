import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { useSessionStore } from "./session";
import { db } from "@/lib/db";
import api from "@/lib/api";
import { BProgress } from '@bprogress/core'
import '@bprogress/core/css'
import { sounds } from '@/lib/sounds'

export const useCartStore = defineStore("cart", () => {

    const items = ref([])

    const discountType = ref('fixed') // 'fixed' | 'percent'
    const discountValue = ref(0)
    const deliveryCharges = ref(0)
    const taxCharges = ref(0)
    const description = ref(null)

    const customerId = ref(null)
    const accountId = ref(null)

    const amountPaid = ref(0)

    function withProgress(fn) {
        return async (...args) => {
            BProgress.start()
            try {
                return await fn(...args)
            } finally {
                BProgress.done()
            }
        }
    }

    function getEffectiveStock(item) {
        return item.selected_unit_id === item.sub_unit_id
            ? item.current_outlet_stock_sub_unit
            : item.current_outlet_stock
    }


    function addItem(product) {
        if (product.current_outlet_stock <= 0) return

        const existing = items.value.find(i => i.id === product.id)

        if (existing) {
            if (existing.qty >= getEffectiveStock(existing)) return  // ← changed
            existing.qty++
            existing.total = existing.qty * existing.rate
        } else {
            const rate = parseFloat(product.selling_price) || 0
            items.value.push({
                ...product,
                qty: 1,
                selected_unit_id: product.unit_id,
                rate,
                total: rate,
            })
        }
        sounds.addItem()
    }

    function removeItem(product) {
        items.value = items.value.filter(i => i.id !== product.id)
        sounds.removeItem()
    }

    function updateQty(product, qty) {
        const item = items.value.find(i => i.id === product.id)
        if (!item) return
        item.qty = qty
        item.total = item.qty * item.rate
        sounds.updateItem()
    }

    function updateRate(product, rate) {
        const item = items.value.find(i => i.id === product.id)
        if (!item) return
        item.rate = rate
        item.total = item.qty * item.rate
        sounds.updateItem()
    }

    function updateUnit(product, unitId) {
        const item = items.value.find(i => i.id === product.id)
        if (!item) return
        item.selected_unit_id = unitId
        item.rate = unitId === item.sub_unit_id
            ? parseFloat(item.sub_unit_selling_price)
            : parseFloat(item.selling_price)
        item.total = item.qty * item.rate
        sounds.updateItem()
    }

    function clearCart() {
        const session = useSessionStore()

        const walkIn = session.customers.find(c => c.customer_type === 'walk_in')

        items.value = []
        customerId.value = walkIn?.id || null
        discountType.value = 'fixed'
        discountValue.value = 0
        deliveryCharges.value = 0
        taxCharges.value = 0
        description.value = null
        accountId.value = null
        amountPaid.value = 0
        sounds.clearCart()
    }

    const total = computed(() =>
        items.value.reduce((sum, i) => sum + i.total, 0)
    )

    const discountAmount = computed(() => {
        if (discountType.value === 'percent') {
            return (total.value * discountValue.value) / 100
        }
        return discountValue.value || 0
    })

    const grandTotal = computed(() =>
        total.value
        - discountAmount.value
        + (deliveryCharges.value || 0)
        + (taxCharges.value || 0)
    )

    function generateReceiptNumber() {
        const d = new Date()
        const date = `${d.getFullYear()}${String(d.getMonth() + 1).padStart(2, '0')}${String(d.getDate()).padStart(2, '0')}`
        const random = Math.floor(Math.random() * 9000) + 1000
        return `POS-${date}-${random}`
        // Output: POS-20260407-4521
    }

    async function submitSale() {
        const session = useSessionStore()

        const actualAmountPaid = Math.min(amountPaid.value, grandTotal.value)

        // ✅ Strip all Vue reactivity before doing anything with the payload
        const payload = JSON.parse(JSON.stringify({
            customer_id: customerId.value,
            customer: session.customers.find((c) => c.id === customerId.value),
            description: description.value,
            outlet_id: session.outlet?.id,
            pos_receipt_number: generateReceiptNumber(),
            total: total.value,
            discount_type: discountType.value,
            discount_value: discountValue.value,
            discount_amount: discountAmount.value,
            delivery_charges: deliveryCharges.value,
            tax_charges: taxCharges.value,
            grand_total: grandTotal.value,
            account_id: accountId.value,
            account_name: session.accounts.find((a) => a.id === accountId.value)?.name || null,
            change_amount: amountPaid.value > grandTotal.value ? amountPaid.value - grandTotal.value : 0,
            amount_paid: actualAmountPaid,
            items: items.value.map(i => ({
                product_id: i.id,
                full_name: i.full_name,
                unit_id: i.selected_unit_id,
                qty: i.qty,
                rate: i.rate,
                total: i.total,
            }))
        }))

        if (session.appIsOnline) {
            try {
                const response = await api.post('/sales', payload)
                const savedSale = { ...payload, ...response.data }
                clearCart()
                sounds.submitSuccess()
                return savedSale
            } catch (err) {
                console.error('Sale failed, queuing offline:', err)
                sounds.submitError()
                await queueSale(payload)
                return payload
            }
        } else {
            await queueSale(payload)
            sounds.submitOffline()
            return payload
        }
    }

    async function queueSale(payload) {
        await db.pending_sales.add({
            ...payload,
            status: 'pending',
            created_at: new Date().toISOString()
        })
        clearCart()
    }

    return {
        items,
        total,
        discountType,
        discountValue,
        discountAmount,
        deliveryCharges,
        taxCharges,
        description,
        grandTotal,
        customerId,
        accountId,
        amountPaid,
        updateQty,
        updateRate,
        addItem: withProgress(addItem),
        removeItem: withProgress(removeItem),
        // updateQty: withProgress(updateQty),
        // updateRate: withProgress(updateRate),
        updateUnit: withProgress(updateUnit),
        clearCart: withProgress(clearCart),
        submitSale: withProgress(submitSale),
        getEffectiveStock,
    }
})
