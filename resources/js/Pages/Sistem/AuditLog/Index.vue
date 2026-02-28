<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDateTime } from '@/utils/date.js'

const props = defineProps({ logs: Object, filters: Object })
const actionColor = { create: 'green', update: 'blue', delete: 'red', login: 'purple' }
const columns = [{ key: 'waktu', label: 'Waktu' }, { key: 'user', label: 'User' }, { key: 'action', label: 'Aksi' }, { key: 'module', label: 'Modul' }, { key: 'deskripsi', label: 'Deskripsi' }]
</script>

<template>
  <AppLayout>
    <PageHeader title="Audit Log" description="Riwayat aktivitas sistem" :breadcrumbs="[{ label: 'Sistem' }, { label: 'Audit Log' }]" />
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="logs?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">{{ formatDateTime(row.created_at) }}</td>
          <td class="px-4 py-3 text-sm text-gray-700">{{ row.user?.name ?? 'System' }}</td>
          <td class="px-4 py-3"><AppBadge :color="actionColor[row.action] ?? 'gray'">{{ row.action }}</AppBadge></td>
          <td class="px-4 py-3 text-xs text-gray-500">{{ row.module }}</td>
          <td class="px-4 py-3 text-sm text-gray-600 max-w-64 truncate">{{ row.deskripsi }}</td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="logs?.links ?? []" :from="logs?.from" :to="logs?.to" :total="logs?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
