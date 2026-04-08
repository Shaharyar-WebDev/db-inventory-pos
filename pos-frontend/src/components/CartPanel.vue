<template>
  <div class="cart-panel h-full flex flex-col bg-white transition-all">
    <!-- Empty State -->
    <div
      v-if="cart.items.length === 0"
      class="flex-1 flex items-center justify-center flex-col p-8 text-center"
    >
      <ShoppingCart class="w-16 h-16 text-gray-200 mb-4" />
      <p class="text-gray-400 text-md font-medium">No items in cart</p>
      <p class="text-gray-300 text-sm mt-1">Tap a product to add</p>
    </div>

    <!-- Cart Items — scrollable -->
    <div
      v-if="cart.items.length > 0"
      class="flex-1 min-h-0 overflow-y-auto p-4 space-y-3"
      style="scrollbar-gutter: stable"
    >
      <div
        v-for="item in cart.items"
        :key="item.id"
        class="bg-white border-b border-gray-200 p-2 hover:border-gray-300 transition-colors"
      >
        <!-- Item Header -->
        <div class="flex justify-between items-start mb-3">
          <h3 class="font-medium text-sm text-gray-900 flex-1 leading-snug pr-2">
            {{ item.full_name }}
          </h3>
          <button
            @click="cart.removeItem(item)"
            class="w-8 h-8 flex items-center cursor-pointer justify-center rounded-full text-red-500 bg-red-50 transition-colors shrink-0 touch-manipulation"
            aria-label="Remove item"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- Row: Unit + Qty + Rate -->
        <div class="grid grid-cols-3 gap-2 mb-4">
          <div>
            <label
              class="text-[12px] font-medium text-gray-400 block mb-1 uppercase tracking-wide"
              >Unit</label
            >
            <select
              v-if="item.sub_unit_id"
              :value="item.selected_unit_id"
              @change="cart.updateUnit(item, Number($event.target.value))"
              class="w-full px-2 py-2 text-sm border border-gray-200 rounded-full bg-white focus:border-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-200 touch-manipulation"
            >
              <option :value="item.unit_id">{{ item.unit }}</option>
              <option :value="item.sub_unit_id">{{ item.sub_unit }}</option>
            </select>
            <div
              v-else
              class="px-2 py-2 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-full"
            >
              {{ item.unit }}
            </div>
          </div>

          <div>
            <label
              class="text-[12px] font-medium text-gray-400 block mb-1 uppercase tracking-wide"
            >
              Qty
              <span class="text-gray-300 font-normal"
                >/{{ parseFloat(cart.getEffectiveStock(item)) }}</span
              >
            </label>
            <!-- Custom Qty Stepper Input  -->
            <div
              class="flex items-center border rounded-full overflow-hidden touch-manipulation"
              :class="
                item.qty > cart.getEffectiveStock(item) || !item.qty || item.qty <= 0
                  ? 'border-red-300 bg-red-50'
                  : 'border-gray-200'
              "
            >
              <button
                type="button"
                @click="cart.updateQty(item, Math.max(1, item.qty - 1))"
                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 active:bg-gray-200 active:scale-95 transition-[background-color,transform] shrink-0"
              >
                <svg
                  class="w-3.5 h-3.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M20 12H4"
                  />
                </svg>
              </button>

              <input
                type="number"
                min="1"
                :max="cart.getEffectiveStock(item)"
                :value="item.qty"
                @input="cart.updateQty(item, Number($event.target.value))"
                class="w-full py-2 text-sm text-center bg-transparent focus:outline-none"
                :class="
                  item.qty > cart.getEffectiveStock(item) || !item.qty || item.qty <= 0
                    ? 'border-red-300 bg-red-50'
                    : 'border-gray-200'
                "
              />

              <button
                type="button"
                @click="cart.updateQty(item, item.qty + 1)"
                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 active:bg-gray-200 active:scale-95 transition-[background-color,transform] shrink-0"
              >
                <svg
                  class="w-3.5 h-3.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M12 4v16m8-8H4"
                  />
                </svg>
              </button>
            </div>
            <!-- End Custom Qty Stepper Input  -->
          </div>

          <div>
            <label
              class="text-[12px] font-medium text-gray-400 block mb-1 uppercase tracking-wide"
              >Rate (₨)</label
            >
            <!-- Custom Rate Stepper Input  -->
            <div
              class="flex items-center border rounded-full overflow-hidden touch-manipulation"
              :class="
                !item.rate || item.rate <= 0
                  ? 'border-red-300 bg-red-50'
                  : 'border-gray-200'
              "
            >
              <button
                type="button"
                @click="cart.updateRate(item, Math.max(0.01, item.rate - 1))"
                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 active:bg-gray-200 active:scale-95 transition-[background-color,transform] shrink-0"
              >
                <svg
                  class="w-3.5 h-3.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M20 12H4"
                  />
                </svg>
              </button>

              <input
                type="number"
                min="0.01"
                step="1"
                :value="item.rate"
                @input="cart.updateRate(item, Number($event.target.value))"
                class="w-full py-2 text-sm text-center bg-transparent focus:outline-none"
                :class="!item.rate || item.rate <= 0 ? 'text-red-500' : 'text-gray-900'"
              />

              <button
                type="button"
                @click="cart.updateRate(item, item.rate + 1)"
                class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 active:bg-gray-200 active:scale-95 transition-[background-color,transform] shrink-0"
              >
                <svg
                  class="w-3.5 h-3.5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M12 4v16m8-8H4"
                  />
                </svg>
              </button>
            </div>
            <!-- End Custom Rate Stepper Input  -->
          </div>
        </div>

        <!-- Validation hints -->
        <div>
          <Transition name="alert">
            <p
              v-if="item.qty > cart.getEffectiveStock(item)"
              class="text-red-500 text-[12px] flex items-center gap-1 my-2"
            >
              <span><TriangleAlert class="w-4 h-4" /></span> Exceeds available stock
            </p>
          </Transition>
          <Transition name="alert">
            <p
              v-if="!item.qty || item.qty <= 0"
              class="text-red-500 text-[12px] flex items-center gap-1 my-2"
            >
              <span><TriangleAlert class="w-4 h-4" /></span> Quantity is required
            </p>
          </Transition>
          <Transition name="alert">
            <p
              v-if="!item.rate || item.rate <= 0"
              class="text-red-500 text-[12px] flex items-center gap-1 my-2"
            >
              <span><TriangleAlert class="w-4 h-4" /></span> Rate is required
            </p>
          </Transition>
        </div>

        <!-- Item Total -->
        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
          <span class="text-[12px] font-medium text-gray-400 uppercase tracking-wide"
            >Item Total</span
          >
          <span class="text-base font-bold text-gray-900"
            >₨ {{ formatPrice(item.total) }}</span
          >
        </div>
      </div>
    </div>

    <!-- Footer — ALWAYS VISIBLE -->
    <div class="cart-footer shrink-0 border-t border-gray-200 bg-white">
      <div class="p-4 space-y-3">
        <!-- Subtotal -->
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">Subtotal</span>
          <span class="text-base font-semibold text-gray-900"
            >₨ {{ formatPrice(Math.round(animatedCartTotal)) }}</span
          >
        </div>

        <!-- Discount + Delivery + Tax -->
        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 w-16">Discount</span>

            <!-- Fused select + input -->
            <div
              class="flex-1 flex items-center border border-gray-200 rounded-full overflow-hidden focus-within:border-gray-400 focus-within:ring-1 focus-within:ring-gray-200 transition-all"
            >
              <select
                style="appearance: none"
                :value="cart.discountType"
                @change="cart.discountType = $event.target.value"
                class="px-4 py-2 text-sm bg-gray-50 border-r border-gray-200 text-gray-600 focus:outline-none cursor-pointer touch-manipulation shrink-0"
              >
                <option value="fixed">₨</option>
                <option value="percent">%</option>
              </select>
              <input
                type="number"
                min="0"
                :value="cart.discountValue"
                @input="cart.discountValue = Number($event.target.value)"
                class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none touch-manipulation min-w-0"
              />
            </div>

            <span
              v-if="cart.discountAmount > 0"
              class="text-xs font-medium text-green-600 shrink-0"
            >
              -₨ {{ formatPrice(cart.discountAmount) }}
            </span>
          </div>
          <Transition name="alert">
            <p
              v-if="cart.discountAmount >= cart.total && cart.total > 0"
              class="text-red-500 text-xs ml-20"
            >
              Discount exceeds subtotal
            </p>
          </Transition>
          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 w-16">Delivery</span>
            <div
              class="flex-1 flex items-center border border-gray-200 rounded-full overflow-hidden focus-within:border-gray-400 focus-within:ring-1 focus-within:ring-gray-200 transition-all"
            >
              <span
                class="px-4 py-2 text-sm text-gray-400 bg-gray-50 border-r border-gray-200 shrink-0"
                >₨</span
              >
              <input
                type="number"
                min="0"
                :value="cart.deliveryCharges"
                @input="cart.deliveryCharges = Number($event.target.value)"
                class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none touch-manipulation min-w-0"
              />
            </div>
          </div>

          <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 w-16">Tax</span>
            <div
              class="flex-1 flex items-center border border-gray-200 rounded-full overflow-hidden focus-within:border-gray-400 focus-within:ring-1 focus-within:ring-gray-200 transition-all"
            >
              <span
                class="px-4 py-2 text-sm text-gray-400 bg-gray-50 border-r border-gray-200 shrink-0"
                >₨</span
              >
              <input
                type="number"
                min="0"
                :value="cart.taxCharges"
                @input="cart.taxCharges = Number($event.target.value)"
                class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none touch-manipulation min-w-0"
              />
            </div>
          </div>
        </div>

        <!-- Customer -->
        <div
          class="flex items-center border rounded-full transition-all focus-within:ring-2"
          :class="
            !cart.customerId
              ? 'border-red-300 focus-within:border-red-400 focus-within:ring-red-100'
              : 'border-gray-200 focus-within:border-gray-400 focus-within:ring-gray-200'
          "
        >
          <Combobox
            :model-value="cart.customerId"
            @update:model-value="cart.customerId = $event"
            class="flex-1 min-w-0"
          >
            <div class="relative">
              <ComboboxButton class="w-full">
                <ComboboxInput
                  class="w-full px-3 py-2 text-sm bg-white focus:outline-none touch-manipulation cursor-pointer rounded-full"
                  :display-value="
                    (id) => session.customers.find((c) => c.id === id)?.name ?? ''
                  "
                  @change="query = $event.target.value"
                  placeholder="-- Select Customer --"
                />
              </ComboboxButton>
              <ComboboxOptions
                class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-auto max-h-48 text-sm focus:outline-none bottom-full mb-1"
              >
                <ComboboxOption
                  v-for="customer in filteredCustomers"
                  :key="customer.id"
                  :value="customer.id"
                  v-slot="{ active, selected }"
                >
                  <div
                    :class="[
                      'px-4 py-2 cursor-pointer flex justify-between items-center',
                      active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                    ]"
                  >
                    <span>
                      {{ customer.name
                      }}{{ customer.contact ? ` (${customer.contact})` : "" }}
                    </span>
                    <span class="text-[11px] text-gray-400 shrink-0 ml-2">
                      {{
                        customer.customer_type === "walk_in" ? "Walk-in" : "Registered"
                      }}
                    </span>
                  </div>
                </ComboboxOption>
              </ComboboxOptions>
            </div>
          </Combobox>

          <button
            @click="$emit('open-new-customer')"
            :disabled="!session.appIsOnline"
            :title="
              !session.appIsOnline
                ? 'Cannot create customers while offline'
                : 'Create Customer'
            "
            class="h-full px-4 py-2 border-l border-gray-200 bg-gray-50 text-green-600 hover:bg-green-50 transition-colors disabled:opacity-40 disabled:cursor-not-allowed touch-manipulation cursor-pointer shrink-0 rounded-r-full"
          >
            <UserRoundPlus class="w-5 h-5" />
          </button>
        </div>

        <Transition name="alert">
          <p v-if="!cart.customerId" class="text-red-500 text-xs mt-1">
            Customer is required
          </p>
        </Transition>

        <!-- Description -->
        <input
          type="text"
          :value="cart.description"
          @input="cart.description = $event.target.value"
          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full focus:border-gray-400 focus:outline-none focus:ring-1 focus:ring-gray-200 touch-manipulation"
          placeholder="Add notes (optional)"
        />

        <Transition name="alert">
          <div
            v-if="hasCartIssue"
            class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-full px-3 py-2"
          >
            <span class="text-red-500 text-sm">⚠</span>
            <span class="text-red-700 text-xs font-medium"
              >Some items have stock or rate issues</span
            >
          </div>
        </Transition>

        <Transition name="alert">
          <div
            v-if="!session.appIsOnline"
            class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-full px-3 py-2"
          >
            <span class="text-yellow-600 text-sm">⚠</span>
            <span class="text-yellow-800 text-xs font-medium"
              >Offline — sale will sync when reconnected</span
            >
          </div>
        </Transition>

        <!-- Grand Total + Actions -->
        <div class="flex items-center gap-3 pt-2 border-t border-gray-200">
          <div class="flex-1">
            <p class="text-[12px] font-medium text-gray-400 uppercase tracking-wide">
              Grand Total
            </p>
            <p class="text-2xl font-bold text-gray-900 leading-tight">
              ₨ {{ formatPrice(Math.round(animatedGrandTotal)) }}
            </p>
          </div>
          <button
            @click="cart.clearCart"
            :disabled="cart.items.length === 0"
            class="h-12 px-4 border border-gray-300 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors touch-manipulation disabled:opacity-40 disabled:cursor-not-allowed"
          >
            Clear
          </button>
          <button
            @click="$emit('open-payment')"
            :disabled="!cart.customerId || cart.items.length === 0 || hasCartIssue"
            class="h-12 px-6 bg-gray-900 text-white rounded-full text-base font-semibold hover:bg-gray-800 transition-colors disabled:opacity-40 disabled:cursor-not-allowed touch-manipulation cursor-pointer flex justify-center items-center gap-2"
          >
            <CircleDollarSign class="w-5" /> Pay Now
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useCartStore } from "@/stores/cart";
import { useSessionStore } from "@/stores/session";
import { UserRoundPlus } from "@lucide/vue";
import { CircleDollarSign } from "@lucide/vue";
import { ShoppingCart, TriangleAlert } from "@lucide/vue";
import { useAnimatedNumber } from "@/composables/useAnimatedNumber";

import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  ComboboxButton,
} from "@headlessui/vue";

const query = ref("");

const filteredCustomers = computed(() =>
  query.value === ""
    ? session.customers
    : session.customers.filter((c) =>
        c.name.toLowerCase().includes(query.value.toLowerCase())
      )
);

defineEmits(["open-payment", "open-new-customer"]);
const cart = useCartStore();
const session = useSessionStore();

const hasCartIssue = computed(() => {
  const itemIssue = cart.items.some(
    (i) =>
      i.qty > cart.getEffectiveStock(i) || !i.qty || i.qty <= 0 || !i.rate || i.rate <= 0
  );
  return itemIssue || (cart.discountAmount >= cart.total && cart.total > 0);
});

const animatedGrandTotal = useAnimatedNumber(computed(() => cart.grandTotal));

const animatedCartTotal = useAnimatedNumber(computed(() => cart.total));

function formatPrice(price) {
  return price?.toLocaleString() || "0";
}
</script>

<style scoped>
cart-footer::-webkit-scrollbar {
  width: 10px;
}

cart-footer::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 4px;
}

cart-footer::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Number input spinner styling */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  opacity: 0.5;
}

/* Touch optimizations */
@media (max-width: 768px) {
  button,
  select,
  input[type="number"],
  input[type="text"] {
    min-height: 44px;
  }

  .cart-panel .p-4 {
    padding: 0.75rem;
  }
}

/* Focus ring for accessibility */
*:focus-visible {
  outline: 2px solid #1f2937;
  outline-offset: 2px;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  display: none;
}
input[type="number"] {
  -moz-appearance: textfield;
}

/* select {
  appearance: none;
  -webkit-appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  padding-right: 28px;
} */
</style>
