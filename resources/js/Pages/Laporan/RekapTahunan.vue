<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ rekap: Array, filters: Object })
const f = ref({ tahun: props.filters?.tahun ?? new Date().getFullYear() })

function filter() {
  router.get(route('laporan.rekap-tahunan'), f.value, { preserveState: true })
}

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']
</script>

<template>
  <AppLayout>
    <PageHeader title="Rekap Tahunan Perjalanan Dinas" :breadcrumbs="[{ label: 'Laporan' }, { label: 'Rekap Tahunan' }]" />

    <AppCard title="Filter" class="mb-5">
      <div class="flex items-center gap-3">
        <input v-model="f.tahun" type="number" class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        <AppButton @click="filter()">Lihat Rekap</AppButton>
      </div>
    </AppCard>

    <AppCard title="Rekap Per Bulan" :padding="false">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bulan</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah SPPD</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Hari</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Realisasi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="(item, idx) in (rekap ?? Array.from({ length: 12 }, (_, i) => ({ bulan: i + 1, jumlah: 0, total_hari: 0, total_realisasi: 0 })))" :key="idx" class="hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-700">{{ months[item.bulan - 1] }}</td>
              <td class="px-4 py-3 text-right text-gray-600">{{ item.jumlah ?? 0 }}</td>
              <td class="px-4 py-3 text-right text-gray-600">{{ item.total_hari ?? 0 }}</td>
              <td class="px-4 py-3 text-right font-semibold text-green-700">{{ formatRupiah(item.total_realisasi ?? 0) }}</td>
            </tr>
          </tbody>
          <tfoot class="bg-green-50 font-semibold">
            <tr>
              <td class="px-4 py-3 text-gray-700">Total Tahun {{ f.tahun }}</td>
              <td class="px-4 py-3 text-right text-gray-700">{{ (rekap ?? []).reduce((s, r) => s + (r.jumlah ?? 0), 0) }}</td>
              <td class="px-4 py-3 text-right text-gray-700">{{ (rekap ?? []).reduce((s, r) => s + (r.total_hari ?? 0), 0) }}</td>
              <td class="px-4 py-3 text-right text-green-800">{{ formatRupiah((rekap ?? []).reduce((s, r) => s + (r.total_realisasi ?? 0), 0)) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </AppCard>
  </AppLayout>
</template>
