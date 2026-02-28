<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ unitKerjas: Array })

const columns = [
  { key: 'kode', label: 'Kode' },
  { key: 'nama', label: 'Nama' },
  { key: 'tingkat', label: 'Tingkat' },
  { key: 'parent', label: 'Induk' },
  { key: 'aksi', label: 'Aksi', class: 'text-right w-28' },
]

const tingkatColor = { dinas: 'green', bidang: 'blue', seksi: 'yellow', sub_bagian: 'orange' }

function destroy(id) {
  if (confirm('Hapus unit kerja ini?')) {
    router.delete(route('master-data.unit-kerja.destroy', id))
  }
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Unit Kerja" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Unit Kerja' }]">
      <Link v-if="can('create_unit_kerja')" :href="route('master-data.unit-kerja.create')">
        <AppButton>+ Tambah</AppButton>
      </Link>
    </PageHeader>

    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="unitKerjas ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ row.kode }}</td>
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.nama }}</td>
          <td class="px-4 py-3">
            <AppBadge :color="tingkatColor[row.tingkat] ?? 'gray'">
              {{ row.tingkat?.replace('_', ' ') }}
            </AppBadge>
          </td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ row.parent?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_unit_kerja')" :href="route('master-data.unit-kerja.edit', row.id)">
                <AppButton variant="ghost" size="sm">Edit</AppButton>
              </Link>
              <AppButton v-if="can('delete_unit_kerja')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
    </AppCard>
  </AppLayout>
</template>
