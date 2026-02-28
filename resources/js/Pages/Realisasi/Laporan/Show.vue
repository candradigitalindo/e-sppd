<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'

const props = defineProps({ laporan: Object })
const statusColor = { draft: 'gray', diajukan: 'blue', disetujui: 'green', ditolak: 'red' }
</script>

<template>
  <AppLayout>
    <PageHeader title="Detail Laporan Perjalanan" :breadcrumbs="[{ label: 'Realisasi' }, { label: 'Laporan', href: route('realisasi.laporan.index') }, { label: 'Detail' }]">
      <AppBadge :color="statusColor[laporan.status] ?? 'gray'">{{ laporan.status }}</AppBadge>
    </PageHeader>

    <AppCard title="Informasi Laporan" class="max-w-2xl">
      <dl class="space-y-4 text-sm">
        <div class="grid grid-cols-2 gap-x-8">
          <div><dt class="text-gray-500">Pegawai</dt><dd class="font-medium text-gray-800 mt-0.5">{{ laporan.sppd?.pegawai?.nama }}</dd></div>
          <div><dt class="text-gray-500">No. SPPD</dt><dd class="font-mono text-gray-700 mt-0.5">{{ laporan.sppd?.nomor }}</dd></div>
        </div>
        <div><dt class="text-gray-500">Tanggal Laporan</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(laporan.tanggal_laporan) }}</dd></div>
        <div><dt class="text-gray-500">Uraian Kegiatan</dt><dd class="text-gray-700 mt-0.5 leading-relaxed whitespace-pre-line">{{ laporan.uraian_kegiatan }}</dd></div>
        <div v-if="laporan.hasil"><dt class="text-gray-500">Hasil</dt><dd class="text-gray-700 mt-0.5 whitespace-pre-line">{{ laporan.hasil }}</dd></div>
        <div v-if="laporan.kendala"><dt class="text-gray-500">Kendala</dt><dd class="text-gray-700 mt-0.5">{{ laporan.kendala }}</dd></div>
        <div v-if="laporan.rekomendasi"><dt class="text-gray-500">Rekomendasi</dt><dd class="text-gray-700 mt-0.5">{{ laporan.rekomendasi }}</dd></div>
      </dl>
    </AppCard>
  </AppLayout>
</template>
