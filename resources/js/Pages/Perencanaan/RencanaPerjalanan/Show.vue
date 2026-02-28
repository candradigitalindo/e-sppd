<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'

const props = defineProps({ rencana: Object })
const statusColor = { menunggu: 'yellow', disetujui: 'green', ditolak: 'red' }

function approve() {
  if (confirm('Setujui rencana perjalanan ini?')) {
    router.post(route('perencanaan.rencana-perjalanan.update-status', props.rencana.id), { status: 'disetujui' })
  }
}

function reject() {
  const catatan = prompt('Masukkan alasan penolakan:')
  if (catatan !== null) {
    router.post(route('perencanaan.rencana-perjalanan.update-status', props.rencana.id), { status: 'ditolak', catatan })
  }
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Detail Rencana Perjalanan" :breadcrumbs="[{ label: 'Perencanaan' }, { label: 'Rencana Perjalanan', href: route('perencanaan.rencana-perjalanan.index') }, { label: 'Detail' }]">
      <template v-if="rencana.status === 'menunggu'">
        <AppButton @click="approve()">Setujui</AppButton>
        <AppButton variant="danger" @click="reject()">Tolak</AppButton>
      </template>
    </PageHeader>

    <AppCard title="Informasi Rencana Perjalanan" class="max-w-2xl">
      <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
        <div><dt class="text-gray-500">Pegawai</dt><dd class="font-medium text-gray-800 mt-0.5">{{ rencana.pegawai?.nama }}</dd></div>
        <div><dt class="text-gray-500">NIP</dt><dd class="text-gray-700 mt-0.5">{{ rencana.pegawai?.nip }}</dd></div>
        <div><dt class="text-gray-500">Kota Tujuan</dt><dd class="text-gray-700 mt-0.5">{{ rencana.kota_tujuan?.nama }}</dd></div>
        <div><dt class="text-gray-500">Tanggal Rencana</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(rencana.tanggal_rencana) }}</dd></div>
        <div><dt class="text-gray-500">Estimasi Hari</dt><dd class="font-semibold text-green-700 mt-0.5">{{ rencana.estimasi_hari }} hari</dd></div>
        <div>
          <dt class="text-gray-500">Status</dt>
          <dd class="mt-0.5"><AppBadge :color="statusColor[rencana.status] ?? 'gray'">{{ rencana.status }}</AppBadge></dd>
        </div>
        <div class="col-span-2"><dt class="text-gray-500">Keperluan</dt><dd class="text-gray-700 mt-0.5">{{ rencana.keperluan }}</dd></div>
        <div v-if="rencana.catatan" class="col-span-2"><dt class="text-gray-500">Catatan Approval</dt><dd class="text-gray-700 mt-0.5">{{ rencana.catatan }}</dd></div>
        <div v-if="rencana.program_kegiatan" class="col-span-2"><dt class="text-gray-500">Program Kegiatan</dt><dd class="text-gray-700 mt-0.5">{{ rencana.program_kegiatan?.nama }}</dd></div>
      </dl>
    </AppCard>
  </AppLayout>
</template>
