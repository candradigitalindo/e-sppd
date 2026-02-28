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
import { useTable } from '@/composables/useTable.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ sppds: Object, filters: Object })
const { filters, loading, apply, reset } = useTable(props.filters ?? {})
const statusColor = { draft: 'gray', aktif: 'blue', berlangsung: 'green', selesai: 'purple', ditolak: 'red' }
const columns = [{ key: 'nomor', label: 'Nomor SPPD' }, { key: 'pegawai', label: 'Pegawai' }, { key: 'tujuan', label: 'Tujuan' }, { key: 'periode', label: 'Periode' }, { key: 'status', label: 'Status' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-24' }]

function destroy(id) {
  if (confirm('Hapus SPPD ini?')) router.delete(route('sppd.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Data SPPD" :breadcrumbs="[{ label: 'Dokumen' }, { label: 'SPPD' }]">
      <Link v-if="can('create_sppd')" :href="route('sppd.create')"><AppButton>+ Buat SPPD</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <div class="flex flex-wrap items-center gap-3 border-b px-5 py-4">
        <select v-model="filters.status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" @change="apply()">
          <option value="">Semua Status</option>
          <option value="draft">Draft</option>
          <option value="aktif">Aktif</option>
          <option value="berlangsung">Berlangsung</option>
          <option value="selesai">Selesai</option>
        </select>
        <AppButton variant="secondary" @click="reset">Reset</AppButton>
      </div>
      <AppTable :columns="columns" :rows="sppds?.data ?? []" :loading="loading">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">{{ row.nomor }}</td>
          <td class="px-4 py-3 text-sm text-gray-700">{{ row.pegawai?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.kota_tujuan?.nama ?? row.kota_tujuan_lainnya ?? '-' }}</td>
          <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(row.tanggal_berangkat) }} – {{ formatDate(row.tanggal_kembali) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link :href="route('sppd.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
              <Link v-if="can('edit_sppd')" :href="route('sppd.edit', row.id)"><AppButton variant="secondary" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_sppd')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="sppds?.links ?? []" :from="sppds?.from" :to="sppds?.to" :total="sppds?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
