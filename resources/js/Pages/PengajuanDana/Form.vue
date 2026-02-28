<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import AppCurrencyInput from '@/Components/UI/AppCurrencyInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ sppds: Array, pegawais: Array })

const form = useForm({
  sppd_id: '',
  pegawai_id: '',
  jumlah_pengajuan: '',
  keterangan: '',
  tanggal_pengajuan: new Date().toISOString().split('T')[0],
})

function submit() {
  form.post(route('pengajuan-dana.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Ajukan Uang Muka" :breadcrumbs="[{ label: 'Keuangan' }, { label: 'Pengajuan Dana', href: route('pengajuan-dana.index') }, { label: 'Ajukan' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Pengajuan" class="max-w-lg">
        <div class="space-y-4">
          <AppSelect v-model="form.sppd_id" label="SPPD" required :options="sppds" option-label="nomor" :error="form.errors.sppd_id" />
          <AppSelect v-model="form.pegawai_id" label="Pegawai" required :options="pegawais" option-label="nama" :error="form.errors.pegawai_id" />
          <AppInput v-model="form.tanggal_pengajuan" label="Tanggal Pengajuan" type="date" required :error="form.errors.tanggal_pengajuan" />
          <AppCurrencyInput v-model="form.jumlah_pengajuan" label="Jumlah Pengajuan" required :error="form.errors.jumlah_pengajuan" />
          <AppInput v-model="form.keterangan" label="Keterangan" :error="form.errors.keterangan" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">Ajukan</AppButton>
          <Link :href="route('pengajuan-dana.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
