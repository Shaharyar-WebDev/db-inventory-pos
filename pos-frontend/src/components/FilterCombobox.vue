<template>
  <Combobox :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" nullable>
    <div class="relative">
      <ComboboxButton class="w-full">
        <ComboboxInput
          class="w-full px-3 py-2 text-sm border border-gray-200 rounded-full bg-white cursor-pointer focus:outline-none focus:border-gray-400 min-h-9"
          :display-value="(id) => options.find((o) => o.id === id)?.name ?? ''"
          @change="query = $event.target.value"
          :placeholder="placeholder"
        />
      </ComboboxButton>
      <ComboboxOptions
        class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-auto max-h-48 text-sm focus:outline-none top-full mt-1"
      >
        <ComboboxOption :value="null" v-slot="{ active }">
          <div :class="['px-4 py-2 cursor-pointer text-gray-400 italic', active ? 'bg-gray-100' : '']">
            {{ placeholder }}
          </div>
        </ComboboxOption>
        <ComboboxOption
          v-for="opt in filteredOptions"
          :key="opt.id"
          :value="opt.id"
          v-slot="{ active, selected }"
        >
          <div :class="['px-4 py-2 cursor-pointer flex justify-between items-center', active ? 'bg-gray-100 text-gray-900' : 'text-gray-700']">
            <span>{{ opt.name }}</span>
            <Check v-if="selected" class="w-4 h-4 text-green-500 shrink-0" />
          </div>
        </ComboboxOption>
      </ComboboxOptions>
    </div>
  </Combobox>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Combobox, ComboboxInput, ComboboxOptions, ComboboxButton, ComboboxOption } from '@headlessui/vue'
import { Check } from '@lucide/vue'

const props = defineProps({
  modelValue: { default: null },
  options: { type: Array, default: () => [] },
  placeholder: { type: String, default: 'All' },
})
defineEmits(['update:modelValue'])

const query = ref('')
const filteredOptions = computed(() =>
  query.value === ''
    ? props.options
    : props.options.filter((o) => o.name.toLowerCase().includes(query.value.toLowerCase()))
)
</script>