import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useCartStore = defineStore("cart", () => {
    const items = ref([]);
    const selectedCustomer = ref(null);
    const selectedPaymentMethod = ref(null);

    // Add product to cart or increase qty if already exists
    function addItem(product) {
        const existing = items.value.find((i) => i.product_id === product.id);

        if (existing) {
            existing.qty++;
            existing.total = existing.qty * existing.rate;
        } else {
            items.value.push({
                product_id: product.id,
                name: product.name,
                unit: product.unit,
                rate: product.selling_price,
                qty: 1,
                total: product.selling_price,
            });
        }
    }

    function removeItem(product_id) {
        items.value = items.value.filter((i) => i.product_id !== product_id);
    }

    function updateQty(product_id, qty) {
        const item = items.value.find((i) => i.product_id === product_id);
        if (!item) return;
        if (qty <= 0) {
            removeItem(product_id);
            return;
        }
        item.qty = qty;
        item.total = qty * item.rate;
    }

    function clearCart() {
        items.value = [];
        selectedCustomer.value = null;
        selectedPaymentMethod.value = null;
    }

    const subtotal = computed(() =>
        items.value.reduce((sum, i) => sum + i.total, 0),
    );

    const itemCount = computed(() => items.value.length);

    // Builds the payload to send to Laravel
    function buildPayload(outlet_id) {
        return {
            outlet_id: outlet_id,
            customer_id: selectedCustomer.value?.id ?? null,
            payment_method_id: selectedPaymentMethod.value?.id ?? null,
            total: subtotal.value,
            grand_total: subtotal.value,
            items: items.value.map((i) => ({
                product_id: i.product_id,
                qty: i.qty,
                rate: i.rate,
                total: i.total,
            })),
        };
    }

    return {
        items,
        selectedCustomer,
        selectedPaymentMethod,
        addItem,
        removeItem,
        updateQty,
        clearCart,
        subtotal,
        itemCount,
        buildPayload,
    };
});
