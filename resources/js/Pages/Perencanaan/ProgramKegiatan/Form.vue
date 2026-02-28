<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import AppCurrencyInput from '@/Components/UI/AppCurrencyInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ program: Object, unitKerjas: Array })
const isEdit = !!props.program
const form = useForm({ unit_kerja_id: props.program?.unit_kerja_id ?? '', kode: props.program?.kode ?? '', nama: props.program?.nama ?? '', tahun: props.program?.tahun ?? new Date().getFullYear(), pagu_anggaran: props.program?.pagu_anggaran ?? '' })
function submit() {
  if (isEdit) form.put(route('perencanaan.program-kegiatan.update', props.program.id))
  else form.post(route('perencanaan.program-kegiatan.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Program Kegiatan' : 'Tambah Program Kegiatan'" :breadcrumbs="[{ label: 'Perencanaan' }, { label: 'Program Kegiatan', href: route('perencanaan.program-kegiatan.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Program/Kegiatan" class="max-w-2xl">
        <div class="space-y-4">
          <AppSelect v-model="form.unit_kerja_id" label="Unit Kerja" :options="unitKerjas" option-label="nama" :error="form.errors.unit_kerja_id" />
          <AppInput v-model="form.kode" label="Kode Kegiatan" required :error="form.errors.kode" />
          <AppInput v-model="form.nama" label="Nama Program/Kegiatan" required :error="form.errors.nama" />
          <div class="grid grid-cols-2 gap-4">
            <AppInput v-if="!isEdit" v-model="form.tahun" label="Tahun Anggaran" type="number" required :error="form.errors.tahun" />
            <AppCurrencyInput v-model="form.pagu_anggaran" label="Pagu Anggaran" required :error="form.errors.pagu_anggaran" />
          </div>
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('perencanaan.program-kegiatan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
