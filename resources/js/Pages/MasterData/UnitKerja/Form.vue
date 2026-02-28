<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ unitKerja: Object, parents: Array })

const isEdit = !!props.unitKerja

const form = useForm({
  parent_id: props.unitKerja?.parent_id ?? '',
  kode: props.unitKerja?.kode ?? '',
  nama: props.unitKerja?.nama ?? '',
  tingkat: props.unitKerja?.tingkat ?? '',
  keterangan: props.unitKerja?.keterangan ?? '',
})

function submit() {
  if (isEdit) form.put(route('master-data.unit-kerja.update', props.unitKerja.id))
  else form.post(route('master-data.unit-kerja.store'))
}

const tingkatOptions = [
  { id: 'dinas', nama: 'Dinas' },
  { id: 'bidang', nama: 'Bidang' },
  { id: 'seksi', nama: 'Seksi' },
  { id: 'sub_bagian', nama: 'Sub Bagian' },
]
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Unit Kerja' : 'Tambah Unit Kerja'" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Unit Kerja', href: route('master-data.unit-kerja.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />

    <form @submit.prevent="submit">
      <AppCard title="Data Unit Kerja" class="max-w-xl">
        <div class="space-y-4">
          <AppSelect v-model="form.parent_id" label="Unit Kerja Induk" :options="parents" option-label="nama" placeholder="-- Tidak ada induk --" :error="form.errors.parent_id" />
          <AppInput v-model="form.kode" label="Kode" required placeholder="01.001" :error="form.errors.kode" />
          <AppInput v-model="form.nama" label="Nama Unit Kerja" required :error="form.errors.nama" />
          <AppSelect v-model="form.tingkat" label="Tingkat" required :options="tingkatOptions" option-label="nama" :error="form.errors.tingkat" />
          <AppInput v-model="form.keterangan" label="Keterangan" :error="form.errors.keterangan" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('master-data.unit-kerja.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
