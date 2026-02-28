<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ pengajuan: Object })
const statusColor = { menunggu: 'yellow', disetujui: 'green', ditolak: 'red', dicairkan: 'blue' }

function approve() {
  if (confirm('Setujui pengajuan dana ini?')) router.post(route('pengajuan-dana.approve', props.pengajuan.id))
}
function reject() {
  const catatan = prompt('Alasan penolakan:')
  if (catatan !== null) router.post(route('pengajuan-dana.reject', props.pengajuan.id), { catatan })
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Detail Pengajuan Dana" :breadcrumbs="[{ label: 'Pengajuan Dana', href: route('pengajuan-dana.index') }, { label: 'Detail' }]">
      <template v-if="pengajuan.status === 'menunggu'">
        <AppButton @click="approve()">Setujui</AppButton>
        <AppButton variant="danger" @click="reject()">Tolak</AppButton>
      </template>
    </PageHeader>

    <AppCard title="Detail Pengajuan" class="max-w-2xl">
      <dl class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
        <div><dt class="text-gray-500">Pegawai</dt><dd class="font-medium text-gray-800 mt-0.5">{{ pengajuan.pegawai?.nama }}</dd></div>
        <div><dt class="text-gray-500">No. SPPD</dt><dd class="font-mono text-gray-700 mt-0.5">{{ pengajuan.sppd?.nomor }}</dd></div>
        <div><dt class="text-gray-500">Tanggal Pengajuan</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(pengajuan.tanggal_pengajuan) }}</dd></div>
        <div>
          <dt class="text-gray-500">Status</dt>
          <dd class="mt-0.5"><AppBadge :color="statusColor[pengajuan.status] ?? 'gray'">{{ pengajuan.status }}</AppBadge></dd>
        </div>
        <div class="col-span-2">
          <dt class="text-gray-500">Jumlah Pengajuan</dt>
          <dd class="text-2xl font-bold text-green-700 mt-0.5">{{ formatRupiah(pengajuan.jumlah_pengajuan) }}</dd>
        </div>
        <div v-if="pengajuan.keterangan" class="col-span-2"><dt class="text-gray-500">Keterangan</dt><dd class="text-gray-700 mt-0.5">{{ pengajuan.keterangan }}</dd></div>
        <div v-if="pengajuan.catatan_approval" class="col-span-2"><dt class="text-gray-500">Catatan Approval</dt><dd class="text-gray-700 mt-0.5">{{ pengajuan.catatan_approval }}</dd></div>
      </dl>
    </AppCard>
  </AppLayout>
</template>
