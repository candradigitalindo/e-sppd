<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { Link } from '@inertiajs/vue3'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ spj: Object })
const statusColor = { draft: 'gray', diajukan: 'blue', disetujui: 'green', ditolak: 'red' }
</script>

<template>
  <AppLayout>
    <PageHeader :title="`SPJ ${spj.nomor}`" :breadcrumbs="[{ label: 'SPJ', href: route('spj.index') }, { label: spj.nomor }]">
      <AppBadge :color="statusColor[spj.status] ?? 'gray'">{{ spj.status }}</AppBadge>
      <Link :href="route('spj.cetak', spj.id)" target="_blank"><AppButton variant="secondary">🖨 Cetak SPJ</AppButton></Link>
    </PageHeader>

    <div class="space-y-5 max-w-4xl">
      <AppCard title="Informasi SPJ">
        <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
          <div><dt class="text-gray-500">Nomor SPJ</dt><dd class="font-mono font-medium text-gray-800 mt-0.5">{{ spj.nomor }}</dd></div>
          <div><dt class="text-gray-500">Tanggal</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(spj.tanggal) }}</dd></div>
          <div><dt class="text-gray-500">Pegawai</dt><dd class="text-gray-700 mt-0.5">{{ spj.sppd?.pegawai?.nama }}</dd></div>
          <div><dt class="text-gray-500">No. SPPD</dt><dd class="font-mono text-gray-700 mt-0.5">{{ spj.sppd?.nomor }}</dd></div>
          <div v-if="spj.catatan" class="col-span-2"><dt class="text-gray-500">Catatan</dt><dd class="text-gray-700 mt-0.5">{{ spj.catatan }}</dd></div>
        </dl>
      </AppCard>

      <AppCard title="Rekap Biaya" :padding="false">
        <table class="min-w-full text-sm divide-y divide-gray-100">
          <thead>
            <tr class="bg-gray-50 text-xs text-gray-500 uppercase">
              <th class="px-4 py-2.5 text-left">Jenis Biaya</th>
              <th class="px-4 py-2.5 text-right">Rencana</th>
              <th class="px-4 py-2.5 text-right">Realisasi</th>
              <th class="px-4 py-2.5 text-left">Keterangan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="r in spj.rekap_biaya" :key="r.id">
              <td class="px-4 py-2.5 capitalize">{{ r.jenis_biaya?.replace(/_/g, ' ') }}</td>
              <td class="px-4 py-2.5 text-right text-gray-500">{{ formatRupiah(r.rencana) }}</td>
              <td class="px-4 py-2.5 text-right font-semibold text-green-700">{{ formatRupiah(r.realisasi) }}</td>
              <td class="px-4 py-2.5 text-gray-500">{{ r.keterangan ?? '-' }}</td>
            </tr>
          </tbody>
          <tfoot class="bg-green-50">
            <tr>
              <td colspan="2" class="px-4 py-3 text-right font-semibold text-gray-700">Total:</td>
              <td class="px-4 py-3 text-right font-bold text-green-800">{{ formatRupiah(spj.total_realisasi) }}</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </AppCard>
    </div>
  </AppLayout>
</template>
