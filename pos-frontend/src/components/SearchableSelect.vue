<template>
  <div class="searchable-select" :class="{ open: isOpen }">
    <div class="select-trigger" @click="toggleOpen">
      <span class="select-value">{{ displayValue || placeholder }}</span>
      <svg
        class="select-arrow"
        :class="{ rotated: isOpen }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </div>

    <Transition name="select-dropdown">
      <div v-if="isOpen" class="select-dropdown">
        <input
          ref="searchInput"
          v-model="search"
          type="text"
          placeholder="Search..."
          class="dropdown-search"
          @keydown.esc="close"
        />

        <div class="dropdown-options">
          <div
            v-for="option in filteredOptions"
            :key="option[valueKey]"
            class="dropdown-option"
            :class="{ selected: option[valueKey] === modelValue }"
            @click="selectOption(option)"
          >
            {{ option[labelKey] }}
          </div>

          <div v-if="filteredOptions.length === 0" class="dropdown-empty">
            No options found
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from "vue";

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
  options: {
    type: Array,
    default: () => [],
  },
  placeholder: {
    type: String,
    default: "Select...",
  },
  labelKey: {
    type: String,
    default: "name",
  },
  valueKey: {
    type: String,
    default: "id",
  },
});

const emit = defineEmits(["update:modelValue"]);

const isOpen = ref(false);
const search = ref("");
const searchInput = ref(null);

const displayValue = computed(() => {
  const selected = props.options.find((opt) => opt[props.valueKey] === props.modelValue);
  return selected ? selected[props.labelKey] : "";
});

const filteredOptions = computed(() => {
  if (!search.value) return props.options;
  const searchLower = search.value.toLowerCase();
  return props.options.filter((opt) =>
    opt[props.labelKey].toLowerCase().includes(searchLower)
  );
});

function toggleOpen() {
  isOpen.value = !isOpen.value;
  if (isOpen.value) {
    nextTick(() => {
      searchInput.value?.focus();
    });
  }
}

function selectOption(option) {
  emit("update:modelValue", option[props.valueKey]);
  search.value = "";
  isOpen.value = false;
}

function close() {
  isOpen.value = false;
  search.value = "";
}

// Close dropdown when clicking outside
function handleClickOutside(event) {
  if (isOpen.value && !event.target.closest(".searchable-select")) {
    close();
  }
}

watch(isOpen, (newVal) => {
  if (newVal) {
    document.addEventListener("click", handleClickOutside);
  } else {
    document.removeEventListener("click", handleClickOutside);
  }
});
</script>

<style scoped>
.searchable-select {
  position: relative;
  user-select: none;
}

.select-trigger {
  min-height: 42px;
  padding: 0.5rem 2rem 0.5rem 1rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 0.75rem;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 0.875rem;
  color: #1f2937;
  transition: all 0.2s ease;
}

.select-trigger:hover {
  border-color: #d1d5db;
  background: #f9fafb;
}

.select-value {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.select-arrow {
  width: 1rem;
  height: 1rem;
  transition: transform 0.2s ease;
}

.select-arrow.rotated {
  transform: rotate(180deg);
}

.select-dropdown {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  right: 0;
  background: white;
  border: 1.5px solid #e5e7eb;
  border-radius: 0.75rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 100;
  overflow: hidden;
}

.dropdown-search {
  width: 100%;
  padding: 0.625rem;
  border: none;
  border-bottom: 1px solid #e5e7eb;
  outline: none;
  font-size: 0.875rem;
}

.dropdown-search:focus {
  background: #f9fafb;
}

.dropdown-options {
  max-height: 200px;
  overflow-y: auto;
}

.dropdown-option {
  padding: 0.625rem 1rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: #4b5563;
  transition: all 0.15s ease;
}

.dropdown-option:hover {
  background: #f9fafb;
  color: #1f2937;
}

.dropdown-option.selected {
  background: #1f2937;
  color: white;
}

.dropdown-empty {
  padding: 0.75rem;
  text-align: center;
  color: #9ca3af;
  font-size: 0.813rem;
}

/* Dropdown animation */
.select-dropdown-enter-active,
.select-dropdown-leave-active {
  transition: all 0.15s ease;
}

.select-dropdown-enter-from,
.select-dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
