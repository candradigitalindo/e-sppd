<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah } from '@/utils/currency.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ standarBiayas: Array, tahun: Number })
const tahunFilter = ref(props.tahun)
const columns = [{ key: 'golongan', label: 'Golongan' }, { key: 'kota', label: 'Kota Tujuan' }, { key: 'uang_harian', label: 'Uang Harian' }, { key: 'penginapan', label: 'Penginapan' }, { key: 'transport', label: 'Transportasi' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function filter() {
  router.get(route('master-data.standar-biaya.index'), { tahun: tahunFilter.value }, { preserveState: true })
}

function destroy(id) {
  if (confirm('Hapus standar biaya ini?')) router.delete(route('master-data.standar-biaya.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Standar Biaya" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Standar Biaya' }]">
      <Link v-if="can('create_standar_biaya')" :href="route('master-data.standar-biaya.create')"><AppButton>+ Tambah</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <div class="flex items-center gap-3 border-b px-5 py-4">
        <label class="text-sm font-medium text-gray-600">Tahun:</label>
        <input v-model="tahunFilter" type="number" class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        <AppButton @click="filter()">Filter</AppButton>
      </div>
      <AppTable :columns="columns" :rows="standarBiayas ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm">{{ row.golongan?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm">{{ row.kota_tujuan?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm text-green-700 font-medium">{{ formatRupiah(row.uang_harian) }}</td>
          <td class="px-4 py-3 text-sm">{{ formatRupiah(row.penginapan) }}</td>
          <td class="px-4 py-3 text-sm">{{ formatRupiah(row.transportasi) }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_standar_biaya')" :href="route('master-data.standar-biaya.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_standar_biaya')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
    </AppCard>
  </AppLayout>
</template>
