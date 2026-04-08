<template>
  <div
    class="product-card relative bg-white border border-gray-200 rounded-xl overflow-hidden transition-all"
    :class="product.current_outlet_stock <= 0
      ? 'opacity-50 cursor-not-allowed'
      : 'cursor-pointer hover:border-gray-300 hover:shadow-md'"
    role="button"
    :tabindex="product.current_outlet_stock > 0 ? 0 : -1"
    :aria-disabled="product.current_outlet_stock <= 0"
    :aria-label="`Add ${product.full_name} to cart`"
    @keydown.enter="$emit('click')"
  >
    <!-- Image -->
    <div class="aspect-[1.2] bg-gray-50 flex items-center justify-center overflow-hidden">
      <img
        v-if="product.thumbnail"
        :src="product.thumbnail"
        :alt="product.full_name"
        loading="lazy"
        decoding="async"
        class="w-full h-full object-cover"
      />
      <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
        <ImageOff class="w-7 h-7 text-gray-300" />
      </div>
    </div>

    <!-- Info -->
    <div class="p-2.5">
      <h3 class="text-sm font-medium text-gray-900 mb-1.5 line-clamp-2 min-h-[2.1rem] leading-tight">
        {{ product.full_name }}
      </h3>

      <div class="flex gap-2 mb-2 text-xs text-gray-500 truncate">
        <span v-if="product.category_name" class="truncate">{{ product.category_name }}</span>
        <span v-if="product.brand_name" class="truncate">{{ product.brand_name }}</span>
      </div>

      <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
        <div
          class="inline-flex items-center gap-1 text-xs"
          :class="product.current_outlet_stock <= 0 ? 'text-red-500' : 'text-green-500'"
        >
          <Package class="w-3 h-3" />
          <span>
            {{ product.current_outlet_stock > 0
              ? `${parseFloat(product.current_outlet_stock)} ${product.unit}`
              : 'Out of Stock' }}
          </span>
        </div>
        <span v-if="product.price" class="text-sm font-semibold text-gray-900">
          ₨ {{ formatPrice(product.price) }}
        </span>
      </div>
    </div>

    <!-- Add indicator -->
    <div
      v-if="product.current_outlet_stock > 0"
      class="add-indicator absolute bottom-2.5 right-2.5 w-7 h-7 bg-gray-900 rounded-full flex items-center justify-center opacity-0 scale-90 transition-all pointer-events-none"
      aria-hidden="true"
    >
      <Plus class="w-3.5 h-3.5 text-white" />
    </div>
  </div>
</template>

<script setup>
import { ImageOff, Package, Plus } from '@lucide/vue'

defineProps({ product: { type: Object, required: true } })
defineEmits(['click'])

function formatPrice(price) {
  return price?.toLocaleString() || price
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-card:hover .add-indicator {
  opacity: 1;
  transform: scale(1);
}

*:focus-visible { outline: 2px solid #1f2937; outline-offset: 2px; }
</style>