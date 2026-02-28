<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import AppCurrencyInput from '@/Components/UI/AppCurrencyInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ standarBiaya: Object, golongans: Array, kotaTujuans: Array })
const isEdit = !!props.standarBiaya
const form = useForm({ golongan_id: props.standarBiaya?.golongan_id ?? '', kota_tujuan_id: props.standarBiaya?.kota_tujuan_id ?? '', tahun: props.standarBiaya?.tahun ?? new Date().getFullYear(), uang_harian: props.standarBiaya?.uang_harian ?? '', penginapan: props.standarBiaya?.penginapan ?? '', transportasi: props.standarBiaya?.transportasi ?? '', uang_representasi: props.standarBiaya?.uang_representasi ?? '', transport_lokal: props.standarBiaya?.transport_lokal ?? '' })
function submit() {
  if (isEdit) form.put(route('master-data.standar-biaya.update', props.standarBiaya.id))
  else form.post(route('master-data.standar-biaya.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Standar Biaya' : 'Tambah Standar Biaya'" :breadcrumbs="[{ label: 'Master Data' }, { label: 'Standar Biaya', href: route('master-data.standar-biaya.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Standar Biaya" class="max-w-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <AppSelect v-if="!isEdit" v-model="form.golongan_id" label="Golongan" required :options="golongans" option-label="nama" :error="form.errors.golongan_id" />
          <AppSelect v-if="!isEdit" v-model="form.kota_tujuan_id" label="Kota Tujuan" required :options="kotaTujuans" option-label="nama" :error="form.errors.kota_tujuan_id" />
          <AppInput v-if="!isEdit" v-model="form.tahun" label="Tahun" type="number" required :error="form.errors.tahun" />
          <AppCurrencyInput v-model="form.uang_harian" label="Uang Harian" required :error="form.errors.uang_harian" />
          <AppCurrencyInput v-model="form.penginapan" label="Penginapan" required :error="form.errors.penginapan" />
          <AppCurrencyInput v-model="form.transportasi" label="Transportasi" required :error="form.errors.transportasi" />
          <AppCurrencyInput v-model="form.uang_representasi" label="Uang Representasi" :error="form.errors.uang_representasi" />
          <AppCurrencyInput v-model="form.transport_lokal" label="Transport Lokal" :error="form.errors.transport_lokal" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('master-data.standar-biaya.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
