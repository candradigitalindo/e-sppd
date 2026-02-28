<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ kotaTujuan: Object })
const isEdit = !!props.kotaTujuan
const kategoriOptions = [{ id: 'A', nama: 'A' }, { id: 'B', nama: 'B' }, { id: 'C', nama: 'C' }, { id: 'D', nama: 'D' }]
const form = useForm({ kode: props.kotaTujuan?.kode ?? '', nama: props.kotaTujuan?.nama ?? '', provinsi: props.kotaTujuan?.provinsi ?? '', kategori_biaya: props.kotaTujuan?.kategori_biaya ?? '' })
function submit() {
  if (isEdit) form.put(route('master-data.kota-tujuan.update', props.kotaTujuan.id))
  else form.post(route('master-data.kota-tujuan.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Kota Tujuan' : 'Tambah Kota Tujuan'" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Kota Tujuan', href: route('master-data.kota-tujuan.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Kota Tujuan" class="max-w-md">
        <div class="space-y-4">
          <AppInput v-model="form.kode" label="Kode" required :error="form.errors.kode" />
          <AppInput v-model="form.nama" label="Nama Kota" required :error="form.errors.nama" />
          <AppInput v-model="form.provinsi" label="Provinsi" required :error="form.errors.provinsi" />
          <AppSelect v-model="form.kategori_biaya" label="Kategori Biaya" required :options="kategoriOptions" option-label="nama" :error="form.errors.kategori_biaya" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('master-data.kota-tujuan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
