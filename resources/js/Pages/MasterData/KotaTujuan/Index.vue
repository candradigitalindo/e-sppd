<script setup>
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

const props = defineProps({ kotaTujuans: Object, filters: Object })
const { filters, loading, apply, reset } = useTable(props.filters ?? {})
const columns = [{ key: 'kode', label: 'Kode' }, { key: 'nama', label: 'Nama Kota' }, { key: 'provinsi', label: 'Provinsi' }, { key: 'kategori', label: 'Kategori' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function destroy(id) {
  if (confirm('Hapus kota tujuan ini?')) router.delete(route('master-data.kota-tujuan.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Kota Tujuan" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Kota Tujuan' }]">
      <Link v-if="can('create_kota_tujuan')" :href="route('master-data.kota-tujuan.create')"><AppButton>+ Tambah</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <div class="flex gap-3 border-b px-5 py-4">
        <input v-model="filters.q" type="search" placeholder="Cari kota..." class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" @keyup.enter="apply()" />
        <AppButton @click="apply()">Cari</AppButton>
      </div>
      <AppTable :columns="columns" :rows="kotaTujuans?.data ?? []" :loading="loading">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono">{{ row.kode }}</td>
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.nama }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ row.provinsi }}</td>
          <td class="px-4 py-3"><AppBadge color="purple">Kat. {{ row.kategori_biaya }}</AppBadge></td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_kota_tujuan')" :href="route('master-data.kota-tujuan.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_kota_tujuan')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="kotaTujuans?.links ?? []" :from="kotaTujuans?.from" :to="kotaTujuans?.to" :total="kotaTujuans?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
