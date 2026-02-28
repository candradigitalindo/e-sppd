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

const props = defineProps({ suratTugas: Object, filters: Object })
const columns = [{ key: 'nomor', label: 'Nomor' }, { key: 'tanggal', label: 'Tanggal' }, { key: 'keperluan', label: 'Keperluan' }, { key: 'tujuan', label: 'Kota Tujuan' }, { key: 'periode', label: 'Periode' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-32' }]

function destroy(id) {
  if (confirm('Hapus surat tugas ini?')) router.delete(route('surat-tugas.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Surat Tugas" :breadcrumbs="[{ label: 'Dokumen' }, { label: 'Surat Tugas' }]">
      <Link v-if="can('create_surat_tugas')" :href="route('surat-tugas.create')"><AppButton>+ Buat SPT</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="suratTugas?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono font-medium text-gray-800">{{ row.nomor }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(row.tanggal) }}</td>
          <td class="px-4 py-3 text-sm text-gray-600 max-w-48 truncate">{{ row.keperluan }}</td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.kota_tujuan?.nama ?? row.kota_tujuan_lainnya ?? '-' }}</td>
          <td class="px-4 py-3 text-xs text-gray-500">{{ formatDate(row.tanggal_berangkat) }} – {{ formatDate(row.tanggal_kembali) }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link :href="route('surat-tugas.show', row.id)"><AppButton variant="ghost" size="sm">Detail</AppButton></Link>
              <Link v-if="can('edit_surat_tugas')" :href="route('surat-tugas.edit', row.id)"><AppButton variant="secondary" size="sm">Edit</AppButton></Link>
              <Link :href="route('surat-tugas.cetak', row.id)" target="_blank"><AppButton variant="secondary" size="sm">Cetak</AppButton></Link>
              <AppButton v-if="can('delete_surat_tugas')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="suratTugas?.links ?? []" :from="suratTugas?.from" :to="suratTugas?.to" :total="suratTugas?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
