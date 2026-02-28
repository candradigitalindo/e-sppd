<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah, formatCompact } from '@/utils/currency.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ programs: Array, totalPagu: Number, tahun: Number, filters: Object })
const tahunFilter = ref(props.tahun)
const columns = [{ key: 'kode', label: 'Kode' }, { key: 'nama', label: 'Nama Program/Kegiatan' }, { key: 'unit', label: 'Unit Kerja' }, { key: 'pagu', label: 'Pagu Anggaran' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function filterByTahun() {
  router.get(route('perencanaan.program-kegiatan.index'), { tahun: tahunFilter.value }, { preserveState: true })
}
function destroy(id) {
  if (confirm('Hapus program kegiatan ini?')) router.delete(route('perencanaan.program-kegiatan.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Program & Kegiatan" :breadcrumbs="[{ label: 'Perencanaan' }, { label: 'Program Kegiatan' }]">
      <Link v-if="can('create_program_kegiatan')" :href="route('perencanaan.program-kegiatan.create')"><AppButton>+ Tambah</AppButton></Link>
    </PageHeader>

    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-5 py-3 flex items-center justify-between">
      <span class="text-sm text-green-700">Total Pagu Anggaran {{ tahun }}</span>
      <span class="text-lg font-bold text-green-800">{{ formatRupiah(totalPagu) }}</span>
    </div>

    <AppCard :padding="false">
      <div class="flex items-center gap-3 border-b px-5 py-4">
        <label class="text-sm font-medium">Tahun:</label>
        <input v-model="tahunFilter" type="number" class="w-28 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
        <AppButton @click="filterByTahun()">Filter</AppButton>
      </div>
      <AppTable :columns="columns" :rows="programs ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ row.kode }}</td>
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.nama }}</td>
          <td class="px-4 py-3 text-sm text-gray-500">{{ row.unit_kerja?.nama ?? '-' }}</td>
          <td class="px-4 py-3 text-sm font-semibold text-green-700">{{ formatRupiah(row.pagu_anggaran) }}</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_program_kegiatan')" :href="route('perencanaan.program-kegiatan.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_program_kegiatan')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
    </AppCard>
  </AppLayout>
</template>
