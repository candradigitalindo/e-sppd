<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ programs: Array, filters: Object })
const f = ref({ tahun: props.filters?.tahun ?? new Date().getFullYear() })

function filter() {
  router.get(route('laporan.penggunaan-anggaran'), f.value, { preserveState: true })
}

const columns = [{ key: 'kode', label: 'Kode' }, { key: 'nama', label: 'Nama Program' }, { key: 'pagu', label: 'Pagu Anggaran', class: 'text-right' }, { key: 'realisasi', label: 'Realisasi', class: 'text-right' }, { key: 'sisa', label: 'Sisa', class: 'text-right' }, { key: 'persen', label: '%', class: 'text-right' }]

const totalPagu = (props.programs ?? []).reduce((s, p) => s + (p.pagu_anggaran ?? 0), 0)
const totalRealisasi = (props.programs ?? []).reduce((s, p) => s + (p.total_realisasi ?? 0), 0)
</script>

<template>
  <AppLayout>
    <PageHeader title="Laporan Penggunaan Anggaran" :breadcrumbs="[{ label: 'Laporan' }, { label: 'Penggunaan Anggaran' }]" />

    <AppCard title="Filter" class="mb-5">
      <div class="flex items-center gap-3">
        <input v-model="f.tahun" type="number" placeholder="Tahun" class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        <AppButton @click="filter()">Filter</AppButton>
      </div>
    </AppCard>

    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="programs ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ row.kode }}</td>
          <td class="px-4 py-3 text-sm text-gray-800">{{ row.nama }}</td>
          <td class="px-4 py-3 text-sm text-right text-gray-600">{{ formatRupiah(row.pagu_anggaran) }}</td>
          <td class="px-4 py-3 text-sm text-right text-blue-700 font-medium">{{ formatRupiah(row.total_realisasi ?? 0) }}</td>
          <td class="px-4 py-3 text-sm text-right" :class="(row.pagu_anggaran - (row.total_realisasi ?? 0)) < 0 ? 'text-red-700 font-semibold' : 'text-green-700'">
            {{ formatRupiah(row.pagu_anggaran - (row.total_realisasi ?? 0)) }}
          </td>
          <td class="px-4 py-3 text-sm text-right text-gray-600">
            {{ row.pagu_anggaran > 0 ? ((row.total_realisasi ?? 0) / row.pagu_anggaran * 100).toFixed(1) : '0.0' }}%
          </td>
        </template>
      </AppTable>
      <div class="flex justify-between bg-green-50 px-5 py-3 text-sm font-semibold text-gray-700 border-t">
        <span>Total Seluruh Program</span>
        <div class="flex gap-8">
          <span>Pagu: {{ formatRupiah(totalPagu) }}</span>
          <span>Realisasi: {{ formatRupiah(totalRealisasi) }}</span>
          <span>Sisa: {{ formatRupiah(totalPagu - totalRealisasi) }}</span>
        </div>
      </div>
    </AppCard>
  </AppLayout>
</template>
