<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ sppd: Object, perhitungans: Array })

const columns = [
  { key: 'jenis', label: 'Jenis Biaya' },
  { key: 'tipe', label: 'Tipe Hari' },
  { key: 'keterangan', label: 'Keterangan' },
  { key: 'jumlah', label: 'Qty' },
  { key: 'satuan', label: 'Satuan Biaya' },
  { key: 'total', label: 'Total', class: 'text-right' },
]

const total = (props.perhitungans ?? []).reduce((s, r) => s + parseFloat(r.total ?? 0), 0)

function hitungOtomatis() {
  if (confirm('Hitung biaya otomatis berdasarkan standar PMK 113/2012?\n\nIni akan menghapus perhitungan yang ada dan menghitung ulang dengan aturan:\n• Hari berangkat & kembali = 40% uang harian\n• Penginapan at-cost = 30% standar')) {
    router.post(route('sppd.perhitungan.hitung-otomatis', props.sppd.id))
  }
}

function tipeBadgeColor(tipe) {
  if (tipe === 'parsial') return 'yellow'
  if (tipe === 'full') return 'green'
  return 'gray'
}

function tipeLabel(row) {
  if (!row.tipe_hari) return null
  const pct = row.persentase ? `${parseFloat(row.persentase).toFixed(0)}%` : ''
  return row.tipe_hari === 'parsial' ? `PARSIAL ${pct}` : 'FULL 100%'
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="`Perhitungan Biaya — ${sppd.nomor}`" :breadcrumbs="[{ label: 'SPPD', href: route('sppd.index') }, { label: sppd.nomor, href: route('sppd.show', sppd.id) }, { label: 'Perhitungan Biaya' }]">
      <AppButton variant="secondary" @click="hitungOtomatis()">⚡ Hitung Otomatis PMK</AppButton>
    </PageHeader>

    <!-- Info PMK -->
    <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-700">
      <strong>PMK 113/2012:</strong> Hari berangkat &amp; hari kembali = <strong>40%</strong> uang harian.
      Penginapan tanpa bukti (at-cost) = <strong>30%</strong> dari standar biaya masukan.
    </div>

    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="perhitungans ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.jenis?.replace(/_/g, ' ') }}</td>
          <td class="px-4 py-3 text-sm">
            <AppBadge v-if="tipeLabel(row)" :color="tipeBadgeColor(row.tipe_hari)" class="text-xs">
              {{ tipeLabel(row) }}
            </AppBadge>
            <template v-else>—</template>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.keterangan ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.jumlah_hari }}×</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ formatRupiah(row.satuan_biaya) }}</td>
          <td class="px-4 py-3 text-sm font-semibold text-right text-green-700">{{ formatRupiah(row.total) }}</td>
        </template>
      </AppTable>
      <div class="flex items-center justify-between border-t bg-green-50 px-5 py-3">
        <span class="text-sm font-semibold text-gray-700">Total Keseluruhan</span>
        <span class="text-lg font-bold text-green-800">{{ formatRupiah(total) }}</span>
      </div>
    </AppCard>
  </AppLayout>
</template>

