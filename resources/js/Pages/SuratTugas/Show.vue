<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'

const props = defineProps({ spt: Object })
</script>

<template>
  <AppLayout>
    <PageHeader :title="`SPT ${spt.nomor}`" :breadcrumbs="[{ label: 'Surat Tugas', href: route('surat-tugas.index') }, { label: spt.nomor }]">
      <Link :href="route('surat-tugas.cetak', spt.id)" target="_blank"><AppButton variant="secondary">Cetak SPT</AppButton></Link>
      <Link :href="route('surat-tugas.edit', spt.id)"><AppButton>Edit</AppButton></Link>
    </PageHeader>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      <div class="lg:col-span-2">
        <AppCard title="Informasi Surat Tugas">
          <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Nomor</dt><dd class="font-mono font-medium text-gray-800 mt-0.5">{{ spt.nomor }}</dd></div>
            <div><dt class="text-gray-500">Tanggal</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(spt.tanggal) }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-500">Keperluan</dt><dd class="text-gray-700 mt-0.5">{{ spt.keperluan }}</dd></div>
            <div><dt class="text-gray-500">Kota Asal</dt><dd class="text-gray-700 mt-0.5">{{ spt.kota_asal }}</dd></div>
            <div><dt class="text-gray-500">Kota Tujuan</dt><dd class="text-gray-700 mt-0.5">{{ spt.kota_tujuan?.nama ?? spt.kota_tujuan_lainnya }}</dd></div>
            <div><dt class="text-gray-500">Berangkat</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(spt.tanggal_berangkat) }}</dd></div>
            <div><dt class="text-gray-500">Kembali</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(spt.tanggal_kembali) }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-500">Dasar Hukum</dt><dd class="text-gray-700 mt-0.5">{{ spt.dasar_hukum ?? '-' }}</dd></div>
          </dl>
        </AppCard>
      </div>

      <div>
        <AppCard title="Pegawai yang Ditugaskan">
          <div v-if="spt.pegawais?.length" class="space-y-2">
            <div v-for="p in spt.pegawais" :key="p.id" class="flex items-center gap-2 text-sm">
              <div class="h-7 w-7 flex items-center justify-center rounded-full bg-green-100 text-green-700 text-xs font-semibold">{{ p.nama?.charAt(0) }}</div>
              <div>
                <p class="font-medium text-gray-800">{{ p.nama }}</p>
                <p class="text-xs text-gray-400">{{ p.jabatan?.nama }}</p>
              </div>
            </div>
          </div>
          <div v-else class="text-sm text-gray-400">Belum ada pegawai.</div>
        </AppCard>
      </div>
    </div>
  </AppLayout>
</template>
