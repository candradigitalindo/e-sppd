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
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ rencanas: Object, filters: Object })
const statusColor = { menunggu: 'yellow', disetujui: 'green', ditolak: 'red' }
const columns = [{ key: 'pegawai', label: 'Pegawai' }, { key: 'kota', label: 'Kota Tujuan' }, { key: 'keperluan', label: 'Keperluan' }, { key: 'tanggal', label: 'Tgl Rencana' }, { key: 'status', label: 'Status' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function destroy(id) {
  if (confirm('Hapus rencana perjalanan ini?')) router.delete(route('perencanaan.rencana-perjalanan.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Rencana Perjalanan" :breadcrumbs="[{ label: 'Perencanaan' }, { label: 'Rencana Perjalanan' }]">
      <Link v-if="can('create_rencana_perjalanan')" :href="route('perencanaan.rencana-perjalanan.create')"><AppButton>+ Tambah</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="rencanas?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.pegawai?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.kota_tujuan?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-gray-600 max-w-48 truncate">{{ row.keperluan }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(row.tanggal_rencana) }}</td>
          <td class="px-4 py-3"><AppBadge :color="statusColor[row.status] ?? 'gray'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link :href="route('perencanaan.rencana-perjalanan.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
              <AppButton v-if="can('delete_rencana_perjalanan')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="rencanas?.links ?? []" :from="rencanas?.from" :to="rencanas?.to" :total="rencanas?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
