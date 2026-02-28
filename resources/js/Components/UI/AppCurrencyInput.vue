<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  placeholder: { type: String, default: '0' },
  error: String,
  required: Boolean,
  disabled: Boolean,
  class: String,
})

const emit = defineEmits(['update:modelValue'])

const inputRef = ref(null)

/** Format angka menjadi string dengan pemisah ribuan (titik) */
function formatDisplay(num) {
  if (num === '' || num === null || num === undefined) return ''
  const n = Number(String(num).replace(/[^0-9]/g, ''))
  if (isNaN(n) || n === 0) return ''
  return new Intl.NumberFormat('id-ID').format(n)
}

/** Nilai awal yang ditampilkan */
function initialDisplay() {
  return formatDisplay(props.modelValue)
}

function onInput(e) {
  const el = e.target
  // Simpan posisi kursor sebelum format
  const selStart = el.selectionStart
  const oldLen = el.value.length

  // Ambil hanya angka
  const digits = el.value.replace(/[^0-9]/g, '')
  const numeric = digits === '' ? '' : Number(digits)

  // Format dengan titik ribuan
  const formatted = digits === '' ? '' : new Intl.NumberFormat('id-ID').format(Number(digits))

  // Set nilai yang terformat
  el.value = formatted

  // Sesuaikan posisi kursor: geser sesuai perubahan panjang string
  const newLen = formatted.length
  const diff = newLen - oldLen
  const newPos = Math.max(0, selStart + diff)
  el.setSelectionRange(newPos, newPos)

  emit('update:modelValue', numeric)
}

function onFocus(e) {
  // Pilih semua teks saat fokus untuk kemudahan edit
  setTimeout(() => e.target.select(), 0)
}

function onBlur(e) {
  // Pastikan format sudah benar saat blur
  const digits = e.target.value.replace(/[^0-9]/g, '')
  e.target.value = formatDisplay(digits)
  const numeric = digits === '' ? '' : Number(digits)
  emit('update:modelValue', numeric)
}
</script>

<template>
  <div :class="['space-y-1.5', props.class]">
    <label v-if="label" class="block text-sm font-medium text-gray-700">
      {{ label }}
      <span v-if="required" class="ml-0.5 text-red-500">*</span>
    </label>

    <div class="relative">
      <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-gray-500 select-none">
        Rp
      </span>
      <input
        ref="inputRef"
        inputmode="numeric"
        :value="initialDisplay()"
        :placeholder="placeholder"
        :disabled="disabled"
        :class="[
          'block w-full rounded-lg border py-2 pl-9 pr-3 text-sm shadow-sm transition text-right tabular-nums',
          'focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500',
          'disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed',
          error
            ? 'border-red-400 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 bg-white',
        ]"
        @input="onInput"
        @focus="onFocus"
        @blur="onBlur"
      />
    </div>

    <p v-if="error" class="text-xs text-red-600">{{ error }}</p>
  </div>
</template>
