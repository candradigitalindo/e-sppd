<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ sppd: Object })
const statusColor = { draft: 'gray', aktif: 'blue', berlangsung: 'green', selesai: 'purple', ditolak: 'red' }
</script>

<template>
  <AppLayout>
    <PageHeader :title="`SPPD ${sppd.nomor}`" :breadcrumbs="[{ label: 'SPPD', href: route('sppd.index') }, { label: sppd.nomor }]">
      <AppBadge :color="statusColor[sppd.status] ?? 'gray'" size="md">{{ sppd.status }}</AppBadge>
      <Link :href="route('sppd.cetak', sppd.id)" target="_blank"><AppButton variant="secondary">Cetak SPPD</AppButton></Link>
      <Link :href="route('sppd.edit', sppd.id)"><AppButton variant="secondary">Edit</AppButton></Link>
    </PageHeader>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
      <div class="lg:col-span-2 space-y-5">
        <AppCard title="Informasi SPPD">
          <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Pegawai</dt><dd class="font-medium text-gray-800 mt-0.5">{{ sppd.pegawai?.nama ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Jabatan</dt><dd class="text-gray-700 mt-0.5">{{ sppd.pegawai?.jabatan?.nama ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Kota Asal</dt><dd class="text-gray-700 mt-0.5">{{ sppd.kota_asal }}</dd></div>
            <div><dt class="text-gray-500">Kota Tujuan</dt><dd class="text-gray-700 mt-0.5">{{ sppd.kota_tujuan?.nama ?? sppd.kota_tujuan_lainnya }}</dd></div>
            <div><dt class="text-gray-500">Tanggal Berangkat</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(sppd.tanggal_berangkat) }}</dd></div>
            <div><dt class="text-gray-500">Tanggal Kembali</dt><dd class="text-gray-700 mt-0.5">{{ formatDate(sppd.tanggal_kembali) }}</dd></div>
            <div><dt class="text-gray-500">Lama Perjalanan</dt><dd class="font-semibold text-green-700 mt-0.5">{{ sppd.lama_perjalanan }} hari</dd></div>
            <div><dt class="text-gray-500">Transportasi</dt><dd class="text-gray-700 mt-0.5 capitalize">{{ sppd.transportasi?.replace('_', ' ') }}</dd></div>
            <div class="col-span-2"><dt class="text-gray-500">Keperluan</dt><dd class="text-gray-700 mt-0.5">{{ sppd.keperluan }}</dd></div>
          </dl>
        </AppCard>
      </div>

      <div class="space-y-4">
        <AppCard title="Aksi Cepat">
          <div class="space-y-2">
            <Link :href="route('sppd.perhitungan.index', sppd.id)" class="block">
              <AppButton variant="secondary" class="w-full">📊 Perhitungan Biaya</AppButton>
            </Link>
            <Link :href="route('sppd.bukti.index', sppd.id)" class="block">
              <AppButton variant="secondary" class="w-full">📎 Bukti Perjalanan</AppButton>
            </Link>
          </div>
        </AppCard>

        <AppCard title="Total Biaya">
          <p class="text-2xl font-bold text-green-700">{{ formatRupiah(sppd.total_biaya) }}</p>
        </AppCard>
      </div>
    </div>
  </AppLayout>
</template>
