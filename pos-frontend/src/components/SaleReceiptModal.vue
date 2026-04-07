<template>
  <Transition name="modal">
    <div
      v-if="visible"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-2000 p-4"
    >
      <div
        class="bg-white rounded-2xl w-full max-w-md shadow-2xl flex flex-col max-h-[90vh]"
      >
        <!-- Modal Header -->
        <div class="p-5 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <h3 class="text-xl font-semibold text-gray-900">Receipt Preview</h3>
            </div>
            <div class="flex gap-2">
              <button
                @click="handlePrint"
                @keydown.ctrl.p="handlePrint"
                class="px-5 py-2.5 cursor-pointer bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors touch-manipulation flex items-center gap-2"
              >
                <Printer class="w-4 h-4" />
                Print
              </button>
              <button
                @click="$emit('close')"
                class="w-9 h-9 flex items-center cursor-pointer justify-center rounded-full text-red-500 bg-red-50 transition-colors shrink-0 touch-manipulation hover:bg-red-100"
              >
                <X class="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>

        <!-- Scrollable Preview -->
        <div class="overflow-y-auto p-5 flex-1">
          <div ref="receiptRef" class="receipt">
            <!-- Shop Header with Logo -->
            <div class="receipt-header">
              <!-- ASCII Art Logo (optional - replace with your own) -->
              <div v-if="receiptLogo" class="receipt-logo" v-html="receiptLogo"></div>

              <!-- Or Image Logo (for thermal printers - use monochrome) -->
              <img
                v-else-if="outlet?.logo_url"
                :src="outlet.logo_url"
                class="receipt-logo-img"
                alt="Store Logo"
              />

              <div class="shop-name">{{ outlet?.name || "POS System" }}</div>
              <div v-if="outlet?.tagline" class="shop-tagline">{{ outlet.tagline }}</div>
              <div v-if="outlet?.address" class="shop-meta">{{ outlet.address }}</div>
              <div v-if="outlet?.phone" class="shop-meta">Tel: {{ outlet.phone }}</div>
              <div v-if="outlet?.email" class="shop-meta">{{ outlet.email }}</div>
              <div v-if="outlet?.tax_number" class="shop-meta">
                GST: {{ outlet.tax_number }}
              </div>
              <div class="divider">────────────────</div>
            </div>

            <!-- Sale Info -->
            <div class="receipt-meta">
              <div class="receipt-row">
                <span>Date:</span>
                <span>{{ formattedDate }}</span>
              </div>
              <div class="receipt-row">
                <span>Receipt #:</span>
                <span>{{ sale?.pos_receipt_number || sale?.receipt_number || sale?.id || "—" }}</span>
              </div>
              <div class="receipt-row">
                <span>Cashier:</span>
                <span>{{ cashierName }}</span>
              </div>
              <div class="receipt-row">
                <span>Customer:</span>
                <span>{{ customerName }}</span>
              </div>
              <div v-if="sale?.description" class="receipt-row">
                <span>Note:</span>
                <span>{{ sale.description }}</span>
              </div>
            </div>

            <div class="divider">────────────────</div>

            <!-- Items -->
            <div class="receipt-items">
              <div class="receipt-items-header">
                <span>Item</span>
                <span>Qty</span>
                <span>Rate</span>
                <span>Total</span>
              </div>
              <div class="divider">────────────────</div>
              <div
                v-for="item in sale?.items"
                :key="item.product_id"
                class="receipt-item"
              >
                <div class="item-name">{{ item.full_name || item.name }}</div>
                <div class="item-row">
                  <span>{{ item.qty }} {{ item.unit }}</span>
                  <span>₨ {{ formatAmount(item.rate) }}</span>
                  <span>₨ {{ formatAmount(item.total) }}</span>
                </div>
              </div>
            </div>

            <div class="divider">────────────────</div>

            <!-- Totals -->
            <div class="receipt-totals">
              <div class="receipt-row">
                <span>Subtotal</span>
                <span>₨ {{ formatAmount(sale?.total) }}</span>
              </div>
              <div v-if="sale?.discount_amount > 0" class="receipt-row">
                <span>Discount</span>
                <span>-₨ {{ formatAmount(sale?.discount_amount) }}</span>
              </div>
              <div v-if="sale?.delivery_charges > 0" class="receipt-row">
                <span>Delivery</span>
                <span>₨ {{ formatAmount(sale?.delivery_charges) }}</span>
              </div>
              <div v-if="sale?.tax_charges > 0" class="receipt-row">
                <span>Tax ({{ sale?.tax_rate || 0 }}%)</span>
                <span>₨ {{ formatAmount(sale?.tax_charges) }}</span>
              </div>
              <div class="divider">────────────────</div>
              <div class="receipt-row grand-total">
                <span>GRAND TOTAL</span>
                <span>₨ {{ formatAmount(sale?.grand_total) }}</span>
              </div>
              <div class="receipt-row">
                <span>Amount Paid</span>
                <span>₨ {{ formatAmount(sale?.amount_paid) }}</span>
              </div>
              <div v-if="change > 0" class="receipt-row change-row">
                <span>Change</span>
                <span>₨ {{ formatAmount(change) }}</span>
              </div>
              <div v-if="balance > 0" class="receipt-row balance-row">
                <span>Balance Due</span>
                <span>₨ {{ formatAmount(balance) }}</span>
              </div>
            </div>

            <div class="divider">────────────────</div>

            <!-- Payment Method -->
            <div v-if="sale?.account_name" class="payment-method">
              <div class="receipt-row">
                <span>Payment Method:</span>
                <span>{{ sale?.account_name }}</span>
              </div>
            </div>

            <!-- Footer -->
            <div class="receipt-footer">
              <div>{{ outlet?.footer_message || "Thank you for your purchase!" }}</div>
              <div v-if="outlet?.return_policy" class="footer-return">
                {{ outlet.return_policy }}
              </div>
              <div class="footer-date">{{ formattedDate }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed } from "vue";
import { Receipt, Printer, X } from "@lucide/vue";
import { onMounted } from "vue";

onMounted(() => {
    window.addEventListener('keydown', (e) => {
        if (e.ctrlKey && e.key.toLowerCase() === 'p') {
            e.preventDefault();
            handlePrint(e);
        }
    });
});

const props = defineProps({
  visible: Boolean,
  sale: Object,
  outlet: Object,
  cashier: Object,
});

defineEmits(["close"]);

const receiptRef = ref(null);

const formattedDate = computed(() => {
  return new Date().toLocaleString();
});

console.log(props);

const customerName = computed(() => {
  const c = props.sale?.customer || props.customer;
  return c.name;
});

const cashierName = computed(() => {
  return props.cashier?.name;
});

const change = computed(() => props.sale?.change_amount || 0);

const balance = computed(() => {
  const paid = props.sale?.amount_paid || 0;
  const total = props.sale?.grand_total || 0;
  return total > paid ? total - paid : 0;
});

// ASCII Art Logo Example - you can customize this or load from outlet settings
const receiptLogo = computed(() => {
  // Return your ASCII art logo here if needed
  // Example:
  // return `
  //   <pre style="font-size: 8px; line-height: 1.2; margin: 0;">
  //     ███████╗████████╗ ██████╗ ██████╗ ███████╗
  //     ██╔════╝╚══██╔══╝██╔═══██╗██╔══██╗██╔════╝
  //     ███████╗   ██║   ██║   ██║██████╔╝███████╗
  //     ╚════██║   ██║   ██║   ██║██╔══██╗╚════██║
  //     ███████║   ██║   ╚██████╔╝██║  ██║███████║
  //     ╚══════╝   ╚═╝    ╚═════╝ ╚═╝  ╚═╝╚══════╝
  //   </pre>
  // `;
  return null; // Set to null if no ASCII logo
});

function formatAmount(val) {
  return parseFloat(val || 0).toFixed(2);
}

function handlePrint() {
  const content = receiptRef.value?.innerHTML;
  if (!content) return;

  const win = window.open("", "_blank", "width=450,height=650");
  win.document.write(`
    <!DOCTYPE html>
    <html>
      <head>
        <title>Receipt</title>
        <meta charset="UTF-8">
        <style>
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }
          body {
            font-family: 'Courier New', 'SF Mono', monospace;
            font-size: 13px;
            width: 80mm;
            padding: 12px;
            color: #000;
            background: white;
          }
          .receipt {
            width: 100%;
          }
          .receipt-header {
            text-align: center;
            margin-bottom: 12px;
          }
          .receipt-logo {
            margin-bottom: 8px;
          }
          .receipt-logo pre {
            font-family: 'Courier New', monospace;
            font-size: 7px;
            line-height: 1.2;
            margin: 0;
          }
          .receipt-logo-img {
            max-width: 120px;
            height: auto;
            margin-bottom: 8px;
          }
          .shop-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
          }
          .shop-tagline {
            font-size: 10px;
            color: #666;
            margin-bottom: 6px;
          }
          .shop-meta {
            font-size: 10px;
            color: #444;
            margin-bottom: 2px;
          }
          .divider {
            text-align: center;
            margin: 8px 0;
            color: #999;
            letter-spacing: 1px;
          }
          .receipt-meta,
          .receipt-totals {
            margin: 8px 0;
          }
          .receipt-row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            font-size: 11px;
          }
          .grand-total {
            font-weight: bold;
            font-size: 14px;
            margin: 6px 0;
          }
          .receipt-items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 4px;
          }
          .item-name {
            font-size: 11px;
            margin-top: 6px;
            font-weight: 600;
          }
          .item-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #444;
            padding-left: 8px;
            margin-top: 2px;
          }
          .receipt-footer {
            text-align: center;
            margin-top: 12px;
          }
          .receipt-footer > div:first-child {
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 4px;
          }
          .footer-return {
            font-size: 9px;
            color: #666;
            margin-top: 4px;
          }
          .footer-date {
            font-size: 9px;
            color: #666;
            margin-top: 4px;
          }
          .payment-method {
            margin: 6px 0;
          }
          .change-row {
            color: #16a34a;
            font-weight: 500;
          }
          .balance-row {
            color: #dc2626;
            font-weight: 500;
          }
        </style>
      </head>
      <body>
        <div class="receipt">${content}</div>
      </body>
    </html>
  `);
  win.document.close();
  win.focus();
  win.print();
  win.close();
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

/* Receipt styles */
.receipt {
  font-family: "Courier New", "SF Mono", monospace;
  font-size: 13px;
  width: 100%;
  color: #000;
}

.receipt-header {
  text-align: center;
  margin-bottom: 12px;
}

.receipt-logo {
  margin-bottom: 8px;
}

.receipt-logo-img {
  max-width: 100px;
  height: auto;
  margin-bottom: 8px;
}

.shop-name {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 4px;
}

.shop-tagline {
  font-size: 10px;
  color: #666;
  margin-bottom: 6px;
}

.shop-meta {
  font-size: 10px;
  color: #555;
  margin-bottom: 2px;
}

.divider {
  text-align: center;
  margin: 8px 0;
  color: #bbb;
  letter-spacing: 1px;
}

.receipt-meta,
.receipt-totals {
  margin: 8px 0;
}

.receipt-row {
  display: flex;
  justify-content: space-between;
  margin: 4px 0;
  font-size: 11px;
}

.grand-total {
  font-weight: bold;
  font-size: 14px;
  margin: 6px 0;
}

.receipt-items-header {
  display: flex;
  justify-content: space-between;
  font-weight: bold;
  font-size: 10px;
  margin-bottom: 4px;
}

.item-name {
  font-size: 11px;
  margin-top: 6px;
  font-weight: 600;
}

.item-row {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  color: #444;
  padding-left: 8px;
  margin-top: 2px;
}

.receipt-footer {
  text-align: center;
  margin-top: 12px;
}

.receipt-footer > div:first-child {
  font-size: 11px;
  font-weight: 500;
  margin-bottom: 4px;
}

.footer-return {
  font-size: 9px;
  color: #666;
  margin-top: 4px;
}

.footer-date {
  font-size: 9px;
  color: #666;
  margin-top: 4px;
}

.payment-method {
  margin: 6px 0;
}

.change-row {
  color: #16a34a;
  font-weight: 500;
}

.balance-row {
  color: #dc2626;
  font-weight: 500;
}

/* Touch optimizations */
@media (max-width: 768px) {
  button {
    min-height: 44px;
  }
}

/* Focus ring for accessibility */
*:focus-visible {
  outline: 2px solid #1f2937;
  outline-offset: 2px;
}
</style>
