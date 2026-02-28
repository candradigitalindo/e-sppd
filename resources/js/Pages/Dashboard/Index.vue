<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah, formatCompact } from '@/utils/currency.js'
import { formatDate } from '@/utils/date.js'

const props = defineProps({
  stats: Object,
  berlangsung: Array,
  totalPagu: Number,
  tahun: Number,
})

const statCards = [
  {
    label: 'Total SPPD',
    value: props.stats?.total_sppd ?? 0,
    color: 'text-blue-600',
    bg: 'bg-blue-50',
    iconColor: 'text-blue-500',
    icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`,
  },
  {
    label: 'Sedang Berlangsung',
    value: props.stats?.berlangsung ?? 0,
    color: 'text-green-600',
    bg: 'bg-green-50',
    iconColor: 'text-green-500',
    icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>`,
  },
  {
    label: 'Total Realisasi',
    value: formatCompact(props.stats?.total_realisasi ?? 0),
    color: 'text-emerald-600',
    bg: 'bg-emerald-50',
    iconColor: 'text-emerald-500',
    icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
  },
  {
    label: 'Pagu Anggaran',
    value: formatCompact(props.totalPagu ?? 0),
    color: 'text-purple-600',
    bg: 'bg-purple-50',
    iconColor: 'text-purple-500',
    icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`,
  },
]
</script>

<template>
  <AppLayout>
    <PageHeader title="Dashboard" :description="`Ringkasan perjalanan dinas tahun ${tahun}`">
      <span class="text-sm text-gray-500 font-medium">Tahun {{ tahun }}</span>
    </PageHeader>

    <!-- Stat cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <AppCard v-for="stat in statCards" :key="stat.label" :padding="false">
        <div class="p-5">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm text-gray-500">{{ stat.label }}</p>
              <p :class="['mt-1 text-2xl font-bold', stat.color]">{{ stat.value }}</p>
            </div>
            <div :class="['flex h-10 w-10 items-center justify-center rounded-xl', stat.bg, stat.iconColor]" v-html="stat.icon" />
          </div>
        </div>
      </AppCard>
    </div>

    <!-- Active SPPD -->
    <AppCard title="SPPD Sedang Berlangsung">
      <div v-if="berlangsung?.length" class="divide-y divide-gray-50">
        <div
          v-for="sppd in berlangsung"
          :key="sppd.id"
          class="flex items-center justify-between py-3"
        >
          <div>
            <p class="text-sm font-medium text-gray-800">{{ sppd.pegawai?.nama ?? '-' }}</p>
            <p class="text-xs text-gray-500">{{ sppd.nomor }} · {{ sppd.kota_tujuan?.nama ?? sppd.kota_tujuan_lainnya }}</p>
          </div>
          <div class="text-right">
            <AppBadge color="green">Berlangsung</AppBadge>
            <p class="mt-0.5 text-xs text-gray-400">s.d. {{ formatDate(sppd.tanggal_kembali) }}</p>
          </div>
        </div>
      </div>
      <div v-else class="py-8 text-center text-sm text-gray-400">
        Tidak ada perjalanan dinas yang sedang berlangsung.
      </div>
    </AppCard>
  </AppLayout>
</template>
