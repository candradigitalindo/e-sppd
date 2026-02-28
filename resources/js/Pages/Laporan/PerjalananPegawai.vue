<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'

const props = defineProps({ sppds: Array, filters: Object })

const f = ref({ ...props.filters, tahun: props.filters?.tahun ?? new Date().getFullYear() })
const statusColor = { draft: 'gray', aktif: 'blue', berlangsung: 'green', selesai: 'purple' }

function filter() {
  router.get(route('laporan.perjalanan-pegawai'), Object.fromEntries(Object.entries(f.value).filter(([, v]) => !!v)), { preserveState: true })
}

const columns = [{ key: 'nomor', label: 'No. SPPD' }, { key: 'pegawai', label: 'Pegawai/Jabatan' }, { key: 'tujuan', label: 'Tujuan' }, { key: 'periode', label: 'Periode' }, { key: 'status', label: 'Status' }]
</script>

<template>
  <AppLayout>
    <PageHeader title="Laporan Perjalanan Pegawai" :breadcrumbs="[{ label: 'Laporan' }, { label: 'Perjalanan Pegawai' }]" />

    <AppCard title="Filter" class="mb-5">
      <div class="flex flex-wrap gap-3">
        <input v-model="f.tahun" type="number" placeholder="Tahun" class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        <select v-model="f.bulan" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          <option value="">Semua Bulan</option>
          <option v-for="n in 12" :key="n" :value="n">{{ new Date(2000, n - 1, 1).toLocaleString('id-ID', { month: 'long' }) }}</option>
        </select>
        <select v-model="f.status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="berlangsung">Berlangsung</option>
          <option value="selesai">Selesai</option>
        </select>
        <AppButton @click="filter()">Filter</AppButton>
      </div>
    </AppCard>

    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="sppds ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono">{{ row.nomor }}</td>
          <td class="px-4 py-3">
            <p class="text-sm font-medium text-gray-800">{{ row.pegawai?.nama }}</p>
            <p class="text-xs text-gray-400">{{ row.pegawai?.jabatan?.nama }}</p>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.kota_tujuan?.nama ?? row.kota_tujuan_lainnya }}</td>
          <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(row.tanggal_berangkat) }} – {{ formatDate(row.tanggal_kembali) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
        </template>
      </AppTable>
    </AppCard>
  </AppLayout>
</template>
