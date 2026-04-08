<template>
  <div class="product-list h-full flex flex-col">
    <!-- Search & Filters -->
    <div class="filters-section p-4 border-b border-gray-200">
      <input
        v-model="productSearch"
        type="text"
        placeholder="Search by name or code..."
        class="search-input w-full px-4 py-2 text-sm border border-gray-200 rounded-full outline-none transition-all mb-3 focus:border-gray-400 focus:ring-2 focus:ring-gray-100"
        @keydown.esc="productSearch = ''"
      />

      <div class="filter-buttons flex gap-2 mb-3">
        <button
          @click="stockFilter = 'all'"
          class="filter-btn px-4 py-2 text-sm font-medium rounded-full border transition-all min-h-9"
          :class="
            stockFilter === 'all'
              ? 'bg-gray-900 border-gray-900 text-white'
              : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300'
          "
        >
          All
        </button>
        <button
          @click="stockFilter = 'in_stock'"
          class="filter-btn px-4 py-2 text-sm font-medium rounded-full border transition-all min-h-9"
          :class="
            stockFilter === 'in_stock'
              ? 'bg-gray-900 border-gray-900 text-white'
              : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300'
          "
        >
          In Stock
        </button>
        <button
          @click="stockFilter = 'out_of_stock'"
          class="filter-btn px-4 py-2 text-sm font-medium rounded-full border transition-all min-h-9"
          :class="
            stockFilter === 'out_of_stock'
              ? 'bg-gray-900 border-gray-900 text-white'
              : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300'
          "
        >
          Out of Stock
        </button>
      </div>

      <div class="filter-selects flex gap-2 mb-3 flex-wrap">
        <!-- Category Filter -->

        <Combobox v-model="categoryFilter" class="flex-1 min-w-0" nullable>
          <div class="relative">
            <ComboboxButton class="w-full">
              <ComboboxInput
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full bg-white cursor-pointer focus:outline-none focus:border-gray-400 min-h-9"
                :display-value="
                  (id) => categoryOptions.find((c) => c.id === id)?.name ?? ''
                "
                @change="categoryQuery = $event.target.value"
                placeholder="All Categories"
              />
            </ComboboxButton>
            <ComboboxOptions
              class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-auto max-h-48 text-sm focus:outline-none top-full mt-1"
            >
              <ComboboxOption :value="null" v-slot="{ active }">
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer text-gray-400 italic',
                    active ? 'bg-gray-100' : '',
                  ]"
                >
                  All Categories
                </div>
              </ComboboxOption>
              <ComboboxOption
                v-for="cat in filteredCategories"
                :key="cat.id"
                :value="cat.id"
                v-slot="{ active, selected }"
              >
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer flex justify-between items-center',
                    active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                  ]"
                >
                  <span>{{ cat.name }}</span>
                  <Check v-if="selected" class="w-4 h-4 text-green-500 shrink-0" />
                </div>
              </ComboboxOption>
            </ComboboxOptions>
          </div>
        </Combobox>

        <!-- Brand Filter -->
        <Combobox v-model="brandFilter" class="flex-1 min-w-0" nullable>
          <div class="relative">
            <ComboboxButton class="w-full">
              <ComboboxInput
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full bg-white cursor-pointer focus:outline-none focus:border-gray-400 min-h-9"
                :display-value="(id) => brandOptions.find((b) => b.id === id)?.name ?? ''"
                @change="brandQuery = $event.target.value"
                placeholder="All Brands"
              />
            </ComboboxButton>
            <ComboboxOptions
              class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-auto max-h-48 text-sm focus:outline-none top-full mt-1"
            >
              <ComboboxOption :value="null" v-slot="{ active }">
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer text-gray-400 italic',
                    active ? 'bg-gray-100' : '',
                  ]"
                >
                  All Brands
                </div>
              </ComboboxOption>
              <ComboboxOption
                v-for="brand in filteredBrands"
                :key="brand.id"
                :value="brand.id"
                v-slot="{ active, selected }"
              >
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer flex justify-between items-center',
                    active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                  ]"
                >
                  <span>{{ brand.name }}</span>
                  <Check v-if="selected" class="w-4 h-4 text-green-500 shrink-0" />
                </div>
              </ComboboxOption>
            </ComboboxOptions>
          </div>
        </Combobox>

        <!-- Parent Product Filter -->
        <Combobox v-model="parentFilter" class="flex-1 min-w-0" nullable>
          <div class="relative">
            <ComboboxButton class="w-full">
              <ComboboxInput
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full bg-white cursor-pointer focus:outline-none focus:border-gray-400 min-h-9"
                :display-value="
                  (id) => parentOptions.find((p) => p.id === id)?.name ?? ''
                "
                @change="parentQuery = $event.target.value"
                placeholder="All Products"
              />
            </ComboboxButton>
            <ComboboxOptions
              class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-auto max-h-48 text-sm focus:outline-none top-full mt-1"
            >
              <ComboboxOption :value="null" v-slot="{ active }">
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer text-gray-400 italic',
                    active ? 'bg-gray-100' : '',
                  ]"
                >
                  All Products
                </div>
              </ComboboxOption>
              <ComboboxOption
                v-for="parent in filteredParents"
                :key="parent.id"
                :value="parent.id"
                v-slot="{ active, selected }"
              >
                <div
                  :class="[
                    'px-4 py-2 cursor-pointer flex justify-between items-center',
                    active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                  ]"
                >
                  <span>{{ parent.name }}</span>
                  <Check v-if="selected" class="w-4 h-4 text-green-500 shrink-0" />
                </div>
              </ComboboxOption>
            </ComboboxOptions>
          </div>
        </Combobox>

        <button
          @click="clearFilters"
          class="clear-btn px-4 py-2 text-sm border border-red-500 rounded-full bg-white text-red-500 cursor-pointer transition-all min-h-9 font-medium hover:bg-red-50"
        >
          Clear
        </button>
      </div>

      <div class="results-count text-xs text-gray-400">
        {{ filteredProducts.length }} product{{
          filteredProducts.length !== 1 ? "s" : ""
        }}
        found
      </div>
    </div>

    <!-- Products Grid with TransitionGroup -->
    <div
      v-if="filteredProducts.length === 0"
      class="empty-state text-center py-12 text-gray-400 text-sm"
    >
      No products found.
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto p-4">
      <TransitionGroup
        name="product-fade"
        tag="div"
        class="grid grid-cols-[repeat(auto-fill,minmax(200px,1fr))] items-start gap-3.5"
      >
        <div
          v-for="product in paginatedProducts"
          :key="product.id"
          @click="product.current_outlet_stock > 0 && cart.addItem(product)"
          class="product-card relative bg-white border border-gray-200 rounded-xl overflow-hidden transition-all"
          :class="
            product.current_outlet_stock <= 0
              ? 'opacity-50 cursor-not-allowed'
              : 'hover:border-gray-300 hover:shadow-md'
          "
        >
          <!-- Product Image -->
          <div
            class="product-image aspect-[1.2] bg-gray-50 flex items-center justify-center overflow-hidden"
          >
            <img
              v-if="product.thumbnail"
              :src="product.thumbnail"
              :alt="product.full_name"
              loading="lazy"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="image-placeholder w-full h-full flex items-center justify-center bg-linear-to-br from-gray-100 to-gray-200"
            >
              <svg
                class="placeholder-icon w-8 h-8 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.5"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>

          <!-- Product Info -->
          <div class="product-info p-2.5">
            <h3
              class="product-title text-sm font-medium text-gray-900 mb-1.5 line-clamp-2 min-h-[2.1rem] leading-tight"
            >
              {{ product.full_name }}
            </h3>

            <div class="product-meta flex gap-2 mb-2 text-xs text-gray-500">
              <span v-if="product.category_name" class="product-category">
                {{ product.category_name }}
              </span>
              <span v-if="product.brand_name" class="product-brand">
                {{ product.brand_name }}
              </span>
            </div>

            <div
              class="product-footer flex justify-between items-center mt-2 pt-2 border-t border-gray-100"
            >
              <div
                class="product-stock inline-flex items-center gap-1 text-xs"
                :class="
                  product.current_outlet_stock <= 0 ? 'text-red-500' : 'text-green-500'
                "
              >
                <svg
                  class="stock-icon w-3 h-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                  />
                </svg>
                <span>
                  {{
                    product.current_outlet_stock > 0
                      ? parseFloat(product.current_outlet_stock) + " " + product.unit
                      : "Out of Stock"
                  }}
                </span>
              </div>

              <div class="product-price" v-if="product.price">
                <span class="price-value text-sm font-semibold text-gray-900"
                  >₨ {{ formatPrice(product.price) }}</span
                >
              </div>
            </div>
          </div>

          <!-- Add to cart indicator -->
          <div
            v-if="product.current_outlet_stock > 0"
            class="add-indicator absolute bottom-2.5 right-2.5 w-8 h-8 bg-gray-900 rounded-full flex items-center justify-center opacity-0 scale-90 transition-all cursor-pointer"
          >
            <svg
              class="add-icon w-4 h-4 text-white"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4"
              />
            </svg>
          </div>
        </div>
      </TransitionGroup>

      <div v-if="hasMore" class="flex justify-center pt-4 pb-2">
        <button
          @click="currentPage++"
          class="px-6 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-full hover:bg-gray-800 transition-colors"
        >
          Load more ({{ filteredProducts.length - paginatedProducts.length }} remaining)
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useSessionStore } from "@/stores/session";
import { useCartStore } from "@/stores/cart";

import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxButton,
  ComboboxOption,
} from "@headlessui/vue";
import { Check } from "@lucide/vue";

const categoryQuery = ref("");
const brandQuery = ref("");
const parentQuery = ref("");

const filteredCategories = computed(() =>
  categoryQuery.value === ""
    ? categoryOptions.value
    : categoryOptions.value.filter((c) =>
        c.name.toLowerCase().includes(categoryQuery.value.toLowerCase())
      )
);

const filteredBrands = computed(() =>
  brandQuery.value === ""
    ? brandOptions.value
    : brandOptions.value.filter((b) =>
        b.name.toLowerCase().includes(brandQuery.value.toLowerCase())
      )
);

const filteredParents = computed(() =>
  parentQuery.value === ""
    ? parentOptions.value
    : parentOptions.value.filter((p) =>
        p.name.toLowerCase().includes(parentQuery.value.toLowerCase())
      )
);

const session = useSessionStore();
const cart = useCartStore();

const productSearch = ref("");
const stockFilter = ref("in_stock");
const categoryFilter = ref("");
const brandFilter = ref("");
const parentFilter = ref("");

function clearFilters() {
  productSearch.value = "";
  stockFilter.value = "in_stock";
  categoryFilter.value = "";
  brandFilter.value = "";
  parentFilter.value = "";
}

function formatPrice(price) {
  return price?.toLocaleString() || price;
}

const categoryOptions = computed(() => {
  const seen = new Set();
  return session.products
    .filter(
      (p) =>
        p.category_id &&
        p.category_name &&
        !seen.has(p.category_id) &&
        seen.add(p.category_id)
    )
    .map((p) => ({ id: p.category_id, name: p.category_name }))
    .sort((a, b) => a.name.localeCompare(b.name));
});

const brandOptions = computed(() => {
  const seen = new Set();
  return session.products
    .filter(
      (p) => p.brand_id && p.brand_name && !seen.has(p.brand_id) && seen.add(p.brand_id)
    )
    .map((p) => ({ id: p.brand_id, name: p.brand_name }))
    .sort((a, b) => a.name.localeCompare(b.name));
});

const parentOptions = computed(() => {
  const seen = new Set();
  return session.products
    .filter(
      (p) =>
        p.parent_product_id &&
        p.parent_product_name &&
        !seen.has(p.parent_product_id) &&
        seen.add(p.parent_product_id)
    )
    .map((p) => ({ id: p.parent_product_id, name: p.parent_product_name }))
    .sort((a, b) => a.name.localeCompare(b.name));
});

const filteredProducts = computed(() => {
  const query = productSearch.value.trim().toLowerCase();
  return session.products.filter((product) => {
    const matchesSearch =
      !query ||
      product.full_name?.toLowerCase().includes(query) ||
      product.name?.toLowerCase().includes(query) ||
      product.code?.toLowerCase().includes(query);
    const matchesStock =
      stockFilter.value === "all" ||
      (stockFilter.value === "in_stock" && product.current_outlet_stock > 0) ||
      (stockFilter.value === "out_of_stock" && product.current_outlet_stock <= 0);
    const matchesCategory =
      !categoryFilter.value || product.category_id == categoryFilter.value;
    const matchesBrand = !brandFilter.value || product.brand_id == brandFilter.value;
    const matchesParent =
      !parentFilter.value || product.parent_product_id == parentFilter.value;
    return (
      matchesSearch && matchesStock && matchesCategory && matchesBrand && matchesParent
    );
  });
});

const PAGE_SIZE = 20;
const currentPage = ref(1);

watch(filteredProducts, () => {
  currentPage.value = 1;
});

const paginatedProducts = computed(() => {
  const end = currentPage.value * PAGE_SIZE;
  return filteredProducts.value.slice(0, end);
});

const hasMore = computed(
  () => paginatedProducts.value.length < filteredProducts.value.length
);
</script>

<style scoped>
/* Custom select dropdown arrow */
.filter-select {
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1rem;
}

.products-grid {
  scrollbar-gutter: stable;
}

/* Line clamp for title */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth product animations - 120fps buttery goodness */
.product-fade-move {
  transition: transform 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.product-fade-enter-active {
  transition: all 0.2s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}

.product-fade-leave-active {
  transition: all 0.15s cubic-bezier(0.4, 0, 1, 1);
  position: absolute;
  display: hidden;
  width: 0%;
  opacity: 0;
}

.product-fade-enter-from {
  opacity: 0;
  transform: scale(0.92) translateY(8px);
}

.product-fade-leave-to {
  opacity: 0;
  transform: scale(0.96) translateY(-4px);
}

/* Hover effect for add indicator */
.product-card:hover .add-indicator {
  opacity: 1 !important;
  transform: scale(1) !important;
}

/* Touch optimizations */
@media (max-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.75rem;
    padding: 0.75rem;
  }

  .filter-btn,
  .filter-select,
  .clear-btn,
  .search-input {
    min-height: 44px;
  }

  .filter-btn {
    cursor: pointer;
  }

  .product-title {
    font-size: 0.75rem;
  }

  .price-value {
    font-size: 0.75rem;
  }
}
</style>
