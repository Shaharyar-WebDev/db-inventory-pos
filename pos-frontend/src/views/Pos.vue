<template>
  <div class="pos-layout">
    <PosHeader @refresh="refresh" />

    <div
      class="flex flex-col lg:flex-row gap-2 p-2 w-full min-h-screen lg:h-screen animate-[pageFadeIn_0.35s_ease-out]"
    >
      <!-- Products Panel -->
      <div
        class="flex-1 min-w-0 md:w-2/3 min-h-[60vh] lg:min-h-0 rounded-2xl border bg-white border-gray-200 overflow-hidden shadow-sm"
      >
        <ProductList />
      </div>

      <!-- Cart Panel -->
      <div
        class="w-full lg:w-1/3 shrink-0 bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm"
      >
        <CartPanel
          @open-payment="openPaymentModal"
          @open-new-customer="openNewCustomerModal"
        />
      </div>
    </div>

    <NewCustomerModal
      :show="showNewCustomerModal"
      @close="showNewCustomerModal = false"
      @saved="onCustomerSaved"
    />

    <PaymentModal
      :show="showPaymentModal"
      :submitting="submitting"
      :is-walk-in="isWalkIn"
      @close="closePaymentModal"
      @submit="handleSubmit"
    />

    <SaleReceiptModal
      :visible="showReceiptModal"
      :sale="lastSale"
      :outlet="session.outlet"
      :cashier="session.user"
      @close="showReceiptModal = false"
    />
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useCartStore } from "@/stores/cart";
import { useSessionStore } from "@/stores/session";

import PosHeader from "@/components/PosHeader.vue";
import ProductList from "@/components/ProductList.vue";
import CartPanel from "@/components/CartPanel.vue";
import NewCustomerModal from "@/components/NewCustomerModal.vue";
import PaymentModal from "@/components/PaymentModal.vue";
import SaleReceiptModal from "@/components/SaleReceiptModal.vue";

const session = useSessionStore();
const cart = useCartStore();

const showNewCustomerModal = ref(false);
const showPaymentModal = ref(false);
const showReceiptModal = ref(false);
const submitting = ref(false);
const lastSale = ref(null);

const selectedCustomer = computed(() =>
  session.customers.find((c) => c.id === cart.customerId)
);

const isWalkIn = computed(() => selectedCustomer.value?.customer_type === "walk_in");

function openNewCustomerModal() {
  showNewCustomerModal.value = true;
}

function onCustomerSaved(customer) {
  cart.customerId = customer.id;
}

function openPaymentModal() {
  cart.amountPaid = cart.grandTotal;
  showPaymentModal.value = true;
}

function closePaymentModal() {
  showPaymentModal.value = false;
}

async function handleSubmit() {
  if (isWalkIn.value && cart.amountPaid < cart.grandTotal) return;
  submitting.value = true;
  try {
    const savedSale = await cart.submitSale();
    lastSale.value = savedSale;
    closePaymentModal();
    showReceiptModal.value = true;
  } finally {
    submitting.value = false;
  }
  await refresh();
}

async function refresh() {
  session.refreshing = true;
  await session.restore();
  session.refreshing = false;
}
</script>

<style scoped>
.pos-layout {
  min-height: 100vh;
  /* background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%); */
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

/* Responsive */
@media (max-width: 1024px) {
  .pos-cart {
    width: 340px;
  }

  .pos-main {
    gap: 1rem;
    padding: 1rem;
  }
}

@media (max-width: 768px) {
  .pos-main {
    flex-direction: column;
  }

  .pos-cart {
    width: 100%;
  }
}

/* Smooth transitions for modal openings */
:deep(.modal-enter-active),
:deep(.modal-leave-active) {
  transition: opacity 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

:deep(.modal-enter-from),
:deep(.modal-leave-to) {
  opacity: 0;
}
</style>
