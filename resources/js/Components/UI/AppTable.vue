<script setup>
defineProps({
  columns: { type: Array, required: true },
  rows: { type: Array, default: () => [] },
  loading: Boolean,
  emptyText: { type: String, default: 'Tidak ada data.' },
})
</script>

<template>
  <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead>
          <tr class="bg-gray-50">
            <th
              v-for="col in columns"
              :key="col.key"
              :class="['px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500', col.class]"
            >
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <!-- Loading skeleton -->
          <tr v-if="loading" v-for="n in 5" :key="'skeleton-' + n">
            <td v-for="col in columns" :key="col.key" class="px-4 py-3">
              <div class="h-4 rounded bg-gray-200 animate-pulse" />
            </td>
          </tr>
          <!-- Empty state -->
          <tr v-else-if="!rows.length">
            <td :colspan="columns.length" class="px-4 py-10 text-center text-gray-400">
              <div class="flex flex-col items-center gap-2">
                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p>{{ emptyText }}</p>
              </div>
            </td>
          </tr>
          <!-- Rows -->
          <tr
            v-else
            v-for="(row, idx) in rows"
            :key="row.id ?? idx"
            class="hover:bg-gray-50 transition-colors"
          >
            <slot :row="row" :index="idx" />
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
