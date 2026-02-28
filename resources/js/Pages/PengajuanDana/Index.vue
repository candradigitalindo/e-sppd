<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ pengajuans: Object, filters: Object })
const statusColor = { menunggu: 'yellow', disetujui: 'green', ditolak: 'red', dicairkan: 'blue' }
const columns = [{ key: 'pegawai', label: 'Pegawai' }, { key: 'sppd', label: 'No. SPPD' }, { key: 'jumlah', label: 'Jumlah Pengajuan' }, { key: 'tanggal', label: 'Tanggal' }, { key: 'status', label: 'Status' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-24' }]
</script>

<template>
  <AppLayout>
    <PageHeader title="Pengajuan Dana" :breadcrumbs="[{ label: 'Keuangan' }, { label: 'Pengajuan Dana' }]">
      <Link v-if="can('create_pengajuan_dana')" :href="route('pengajuan-dana.create')"><AppButton>+ Ajukan</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="pengajuans?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.pegawai?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ row.sppd?.nomor ?? '-' }}</td>
          <td class="px-4 py-3 text-sm font-semibold text-green-700">{{ formatRupiah(row.jumlah_pengajuan) }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ formatDate(row.tanggal_pengajuan) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <Link :href="route('pengajuan-dana.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="pengajuans?.links ?? []" :from="pengajuans?.from" :to="pengajuans?.to" :total="pengajuans?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
