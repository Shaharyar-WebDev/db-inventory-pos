<template>
    <div style="height: 100%; display: flex; flex-direction: column;">

        <!-- Search -->
        <input v-model="search" type="text" placeholder="Search product or scan barcode..."
            style="width: 100%; padding: 10px; margin-bottom: 10px; font-size: 16px; box-sizing: border-box;" />

        <!-- Product Grid -->
        <div
            style="flex: 1; overflow-y: auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px;">
            <div v-for="product in filteredProducts" :key="product.id" @click="cart.addItem(product)"
                style="border: 1px solid #ddd; border-radius: 8px; padding: 12px; cursor: pointer; text-align: center;">
                <div style="font-weight: bold; margin-bottom: 4px;">{{ product.name }}</div>
                <div style="color: #666; font-size: 13px;">{{ product.category }}</div>
                <div style="color: green; font-weight: bold; margin-top: 6px;">Rs. {{ product.selling_price }}</div>
                <div style="color: #999; font-size: 12px;">Stock: {{ product.current_stock }}</div>
            </div>

            <div v-if="filteredProducts.length === 0"
                style="grid-column: 1/-1; text-align: center; color: #999; padding: 40px;">
                No products found
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCartStore } from '@/stores/cart'
import { db } from '@/lib/db'

const cart = useCartStore()
const search = ref('')
const products = ref([])

onMounted(async () => {
    products.value = await db.products.toArray()
})

const filteredProducts = computed(() => {
    if (!search.value) return products.value

    const q = search.value.toLowerCase()
    return products.value.filter(p =>
        p.name.toLowerCase().includes(q) ||
        p.code?.toLowerCase().includes(q) ||
        p.category?.toLowerCase().includes(q)
    )
})
</script>
