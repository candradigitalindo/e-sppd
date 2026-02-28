<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { useTable } from '@/composables/useTable.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({
  pegawais: Object,
  filters: Object,
})

const { filters, loading, apply, reset } = useTable(props.filters ?? {})

const columns = [
  { key: 'no', label: '#', class: 'w-10' },
  { key: 'nip', label: 'NIP' },
  { key: 'nama', label: 'Nama' },
  { key: 'jabatan', label: 'Jabatan' },
  { key: 'golongan', label: 'Golongan' },
  { key: 'unit_kerja', label: 'Unit Kerja' },
  { key: 'aksi', label: 'Aksi', class: 'text-right w-32' },
]

function destroy(id) {
  if (confirm('Hapus pegawai ini?')) {
    router.delete(route('master-data.pegawai.destroy', id))
  }
}
</script>

<template>
  <AppLayout>
    <PageHeader
      title="Data Pegawai"
      description="Kelola data pegawai"
      :breadcrumbs="[{ label: 'Master Data' }, { label: 'Pegawai' }]"
    >
      <Link v-if="can('create_pegawai')" :href="route('master-data.pegawai.create')">
        <AppButton>
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Pegawai
        </AppButton>
      </Link>
    </PageHeader>

    <AppCard :padding="false">
      <!-- Search bar -->
      <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4">
        <input
          v-model="filters.q"
          type="search"
          placeholder="Cari nama atau NIP..."
          class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
          @keyup.enter="apply()"
        />
        <AppButton @click="apply()">Cari</AppButton>
        <AppButton variant="secondary" @click="reset">Reset</AppButton>
      </div>

      <AppTable :columns="columns" :rows="pegawais?.data ?? []" :loading="loading">
        <template #default="{ row, index }">
          <td class="px-4 py-3 text-gray-500 text-xs">
            {{ (pegawais?.current_page - 1) * pegawais?.per_page + index + 1 }}
          </td>
          <td class="px-4 py-3 text-sm font-mono">{{ row.nip ?? '-' }}</td>
          <td class="px-4 py-3">
            <p class="text-sm font-medium text-gray-800">{{ row.nama }}</p>
            <p class="text-xs text-gray-400">{{ row.email ?? '' }}</p>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.jabatan?.nama ?? '-' }}</td>
          <td class="px-4 py-3">
            <AppBadge color="blue">{{ row.golongan?.nama ?? '-' }}</AppBadge>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.unit_kerja?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_pegawai')" :href="route('master-data.pegawai.edit', row.id)">
                <AppButton variant="ghost" size="sm">Edit</AppButton>
              </Link>
              <AppButton v-if="can('delete_pegawai')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>

      <div class="px-5">
        <AppPagination
          :links="pegawais?.links ?? []"
          :from="pegawais?.from"
          :to="pegawais?.to"
          :total="pegawais?.total"
        />
      </div>
    </AppCard>
  </AppLayout>
</template>
