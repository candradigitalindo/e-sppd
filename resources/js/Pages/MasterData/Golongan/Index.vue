<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ golongans: Object })
const columns = [{ key: 'kode', label: 'Kode' }, { key: 'nama', label: 'Nama' }, { key: 'ruang', label: 'Ruang' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function destroy(id) {
  if (confirm('Hapus golongan ini?')) router.delete(route('master-data.golongan.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Golongan" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Golongan' }]">
      <Link v-if="can('create_golongan')" :href="route('master-data.golongan.create')"><AppButton>+ Tambah</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="golongans?.data ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-semibold text-green-700">{{ row.kode }}</td>
          <td class="px-4 py-3 text-sm text-gray-800">{{ row.nama }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ row.ruang ?? '-' }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_golongan')" :href="route('master-data.golongan.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_golongan')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="golongans?.links ?? []" :from="golongans?.from" :to="golongans?.to" :total="golongans?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
