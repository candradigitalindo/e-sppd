<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ pegawais: Array, kotaTujuans: Array, programs: Array })
const form = useForm({ pegawai_id: '', kota_tujuan_id: '', program_kegiatan_id: '', keperluan: '', tanggal_rencana: '', estimasi_hari: 1 })
function submit() { form.post(route('perencanaan.rencana-perjalanan.store')) }
</script>

<template>
  <AppLayout>
    <PageHeader title="Tambah Rencana Perjalanan" :breadcrumbs="[{ label: 'Perencanaan' }, { label: 'Rencana Perjalanan', href: route('perencanaan.rencana-perjalanan.index') }, { label: 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Rencana Perjalanan" class="max-w-2xl">
        <div class="space-y-4">
          <AppSelect v-model="form.pegawai_id" label="Pegawai" required :options="pegawais" option-label="nama" :error="form.errors.pegawai_id" />
          <AppSelect v-model="form.kota_tujuan_id" label="Kota Tujuan" :options="kotaTujuans" option-label="nama" :error="form.errors.kota_tujuan_id" />
          <AppSelect v-model="form.program_kegiatan_id" label="Program Kegiatan" :options="programs" option-label="nama" :error="form.errors.program_kegiatan_id" />
          <AppInput v-model="form.keperluan" label="Keperluan" required :error="form.errors.keperluan" />
          <div class="grid grid-cols-2 gap-4">
            <AppInput v-model="form.tanggal_rencana" label="Tanggal Rencana" type="date" required :error="form.errors.tanggal_rencana" />
            <AppInput v-model="form.estimasi_hari" label="Estimasi Hari" type="number" required :error="form.errors.estimasi_hari" />
          </div>
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">Simpan</AppButton>
          <Link :href="route('perencanaan.rencana-perjalanan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
