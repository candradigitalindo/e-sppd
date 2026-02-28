<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ laporans: Object, filters: Object })
const statusColor = { draft: 'gray', diajukan: 'blue', disetujui: 'green', ditolak: 'red' }
const columns = [
  { key: 'pegawai', label: 'Pegawai' },
  { key: 'sppd', label: 'No. SPPD' },
  { key: 'tanggal', label: 'Tanggal Lapor' },
  { key: 'status', label: 'Status' },
  { key: 'aksi', label: 'Aksi', class: 'text-right w-24' },
]
</script>

<template>
  <AppLayout>
    <PageHeader title="Laporan Perjalanan" :breadcrumbs="[{ label: 'Realisasi' }, { label: 'Laporan Perjalanan' }]">
      <Link v-if="can('create_laporan_perjalanan')" :href="route('realisasi.laporan.create')"><AppButton>+ Buat Laporan</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="laporans?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.sppd?.pegawai?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ row.sppd?.nomor ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ formatDate(row.tanggal_laporan) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link :href="route('realisasi.laporan.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
              <Link v-if="can('edit_laporan_perjalanan')" :href="route('realisasi.laporan.edit', row.id)"><AppButton variant="secondary" size="sm">Edit</AppButton></Link>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="laporans?.links ?? []" :from="laporans?.from" :to="laporans?.to" :total="laporans?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
