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
import { formatRupiah } from '@/utils/currency.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ spjs: Object, filters: Object })
const statusColor = { draft: 'gray', diajukan: 'blue', disetujui: 'green', ditolak: 'red' }
const columns = [{ key: 'nomor', label: 'Nomor SPJ' }, { key: 'pegawai', label: 'Pegawai' }, { key: 'tanggal', label: 'Tanggal' }, { key: 'rencana', label: 'Rencana' }, { key: 'realisasi', label: 'Realisasi' }, { key: 'status', label: 'Status' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-24' }]
</script>

<template>
  <AppLayout>
    <PageHeader title="SPJ Perjalanan Dinas" :breadcrumbs="[{ label: 'Realisasi' }, { label: 'SPJ' }]">
      <Link v-if="can('create_spj')" :href="route('spj.create')"><AppButton>+ Buat SPJ</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="spjs?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">{{ row.nomor }}</td>
          <td class="px-4 py-3 text-sm text-gray-700">{{ row.sppd?.pegawai?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ formatDate(row.tanggal) }}</td>
          <td class="px-4 py-3 text-sm text-blue-700">{{ formatRupiah(row.total_rencana) }}</td>
          <td class="px-4 py-3 text-sm text-green-700 font-semibold">{{ formatRupiah(row.total_realisasi) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <Link :href="route('spj.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="spjs?.links ?? []" :from="spjs?.from" :to="spjs?.to" :total="spjs?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
