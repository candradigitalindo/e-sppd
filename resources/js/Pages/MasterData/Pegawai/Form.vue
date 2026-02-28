<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({
  pegawai: Object,
  unitKerjas: Array,
  jabatans: Array,
  golongans: Array,
})

const isEdit = !!props.pegawai

const form = useForm({
  nip: props.pegawai?.nip ?? '',
  nama: props.pegawai?.nama ?? '',
  unit_kerja_id: props.pegawai?.unit_kerja_id ?? '',
  jabatan_id: props.pegawai?.jabatan_id ?? '',
  golongan_id: props.pegawai?.golongan_id ?? '',
  no_rekening: props.pegawai?.no_rekening ?? '',
  nama_bank: props.pegawai?.nama_bank ?? '',
  npwp: props.pegawai?.npwp ?? '',
  no_hp: props.pegawai?.no_hp ?? '',
  email: props.pegawai?.email ?? '',
})

function submit() {
  if (isEdit) {
    form.put(route('master-data.pegawai.update', props.pegawai.id))
  } else {
    form.post(route('master-data.pegawai.store'))
  }
}
</script>

<template>
  <AppLayout>
    <PageHeader
      :title="isEdit ? 'Edit Pegawai' : 'Tambah Pegawai'"
      :breadcrumbs="[{ label: 'Master Data' }, { label: 'Pegawai', href: route('master-data.pegawai.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]"
    />

    <form @submit.prevent="submit">
      <AppCard title="Data Pegawai" class="max-w-3xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <AppInput
            v-model="form.nip"
            label="NIP"
            placeholder="198001012005011001"
            :error="form.errors.nip"
          />
          <AppInput
            v-model="form.nama"
            label="Nama Lengkap"
            placeholder="Nama pegawai"
            required
            :error="form.errors.nama"
          />
          <AppSelect
            v-model="form.unit_kerja_id"
            label="Unit Kerja"
            :options="unitKerjas"
            option-label="nama"
            :error="form.errors.unit_kerja_id"
          />
          <AppSelect
            v-model="form.jabatan_id"
            label="Jabatan"
            :options="jabatans"
            option-label="nama"
            :error="form.errors.jabatan_id"
          />
          <AppSelect
            v-model="form.golongan_id"
            label="Golongan"
            :options="golongans"
            option-label="nama"
            :error="form.errors.golongan_id"
          />
          <AppInput
            v-model="form.no_hp"
            label="No. HP"
            placeholder="08xxxxxxxx"
            :error="form.errors.no_hp"
          />
          <AppInput
            v-model="form.email"
            label="Email"
            type="email"
            placeholder="email@pemda.go.id"
            :error="form.errors.email"
          />
          <AppInput
            v-model="form.npwp"
            label="NPWP"
            placeholder="xx.xxx.xxx.x-xxx.xxx"
            :error="form.errors.npwp"
          />
          <AppInput
            v-model="form.no_rekening"
            label="No. Rekening"
            :error="form.errors.no_rekening"
          />
          <AppInput
            v-model="form.nama_bank"
            label="Nama Bank"
            placeholder="BRI / BNI / Mandiri"
            :error="form.errors.nama_bank"
          />
        </div>

        <div class="mt-6 flex items-center gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">
            {{ isEdit ? 'Perbarui' : 'Simpan' }}
          </AppButton>
          <Link :href="route('master-data.pegawai.index')">
            <AppButton variant="secondary">Batal</AppButton>
          </Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
