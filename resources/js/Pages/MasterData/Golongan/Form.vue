<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ golongan: Object })
const isEdit = !!props.golongan
const form = useForm({ kode: props.golongan?.kode ?? '', nama: props.golongan?.nama ?? '', ruang: props.golongan?.ruang ?? '', keterangan: props.golongan?.keterangan ?? '' })
function submit() {
  if (isEdit) form.put(route('master-data.golongan.update', props.golongan.id))
  else form.post(route('master-data.golongan.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Golongan' : 'Tambah Golongan'" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Golongan', href: route('master-data.golongan.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Golongan" class="max-w-md">
        <div class="space-y-4">
          <AppInput v-model="form.kode" label="Kode" required placeholder="IV/a" :error="form.errors.kode" />
          <AppInput v-model="form.nama" label="Nama Golongan" required :error="form.errors.nama" />
          <AppInput v-model="form.ruang" label="Ruang" placeholder="a" :error="form.errors.ruang" />
          <AppInput v-model="form.keterangan" label="Keterangan" :error="form.errors.keterangan" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('master-data.golongan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
