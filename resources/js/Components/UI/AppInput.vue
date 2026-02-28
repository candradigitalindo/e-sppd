<script setup>
defineProps({
  modelValue: [String, Number],
  label: String,
  type: { type: String, default: 'text' },
  placeholder: String,
  error: String,
  required: Boolean,
  disabled: Boolean,
})
defineEmits(['update:modelValue'])
</script>

<template>
  <div class="space-y-1.5">
    <label v-if="label" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="ml-0.5 text-red-500">*</span>
    </label>
    <input
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :disabled="disabled"
      :class="[
        'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition',
        'focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500',
        'disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed',
        error
          ? 'border-red-400 focus:border-red-500 focus:ring-red-500'
          : 'border-gray-300 bg-white',
      ]"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
  </div>
</template>
