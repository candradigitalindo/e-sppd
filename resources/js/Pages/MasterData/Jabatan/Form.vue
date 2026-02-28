<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ jabatan: Object })
const isEdit = !!props.jabatan

const levelOptions = [
  { id: 'pimpinan_tinggi_madya', nama: 'Pimpinan Tinggi Madya' },
  { id: 'pimpinan_tinggi_pratama', nama: 'Pimpinan Tinggi Pratama' },
  { id: 'administrator', nama: 'Administrator' },
  { id: 'pengawas', nama: 'Pengawas' },
  { id: 'pelaksana', nama: 'Pelaksana' },
  { id: 'fungsional', nama: 'Fungsional' },
]

const form = useForm({
  kode: props.jabatan?.kode ?? '',
  nama: props.jabatan?.nama ?? '',
  level: props.jabatan?.level ?? '',
})

function submit() {
  if (isEdit) form.put(route('master-data.jabatan.update', props.jabatan.id))
  else form.post(route('master-data.jabatan.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Jabatan' : 'Tambah Jabatan'" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Jabatan', href: route('master-data.jabatan.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Jabatan" class="max-w-md">
        <div class="space-y-4">
          <AppInput v-model="form.kode" label="Kode" required :error="form.errors.kode" />
          <AppInput v-model="form.nama" label="Nama Jabatan" required :error="form.errors.nama" />
          <AppSelect v-model="form.level" label="Level" required :options="levelOptions" option-label="nama" :error="form.errors.level" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('master-data.jabatan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
