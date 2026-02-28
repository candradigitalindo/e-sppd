<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDateTime } from '@/utils/date.js'

const props = defineProps({ backups: Object })
const form = useForm({})
const columns = [{ key: 'tanggal', label: 'Tanggal' }, { key: 'status', label: 'Status' }, { key: 'size', label: 'Ukuran File' }, { key: 'path', label: 'File Path' }]

function runBackup() {
  if (confirm('Jalankan backup database sekarang?')) form.post(route('sistem.backup.store'))
}

function formatSize(bytes) {
  if (!bytes) return '-'
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / 1024 / 1024).toFixed(2) + ' MB'
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Backup Database" description="Kelola backup data sistem" :breadcrumbs="[{ label: 'Sistem' }, { label: 'Backup' }]">
      <AppButton @click="runBackup()" :loading="form.processing">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
        </svg>
        Backup Sekarang
      </AppButton>
    </PageHeader>

    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="backups?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm text-gray-600">{{ formatDateTime(row.created_at) }}</td>
          <td class="px-4 py-3"><AppBadge :color="row.status === 'sukses' ? 'green' : 'red'">{{ row.status }}</AppBadge></td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ formatSize(row.file_size) }}</td>
          <td class="px-4 py-3 text-xs font-mono text-gray-400 truncate max-w-64">{{ row.file_path ?? '-' }}</td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="backups?.links ?? []" :from="backups?.from" :to="backups?.to" :total="backups?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
