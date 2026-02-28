<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ sppds: Array })
const form = useForm({ sppd_id: '', tanggal_laporan: new Date().toISOString().split('T')[0], uraian_kegiatan: '', hasil: '', kendala: '', rekomendasi: '' })
function submit() { form.post(route('realisasi.laporan.store')) }
</script>

<template>
  <AppLayout>
    <PageHeader title="Buat Laporan Perjalanan" :breadcrumbs="[{ label: 'Realisasi' }, { label: 'Laporan', href: route('realisasi.laporan.index') }, { label: 'Buat' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data Laporan" class="max-w-2xl">
        <div class="space-y-4">
          <AppSelect v-model="form.sppd_id" label="SPPD" required :options="sppds" option-label="nomor" :error="form.errors.sppd_id" />
          <AppInput v-model="form.tanggal_laporan" label="Tanggal Laporan" type="date" required :error="form.errors.tanggal_laporan" />
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Kegiatan <span class="text-red-500">*</span></label>
            <textarea v-model="form.uraian_kegiatan" rows="4" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
            <p v-if="form.errors.uraian_kegiatan" class="mt-1 text-xs text-red-600">{{ form.errors.uraian_kegiatan }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Hasil</label>
            <textarea v-model="form.hasil" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
          </div>
          <AppInput v-model="form.kendala" label="Kendala" :error="form.errors.kendala" />
          <AppInput v-model="form.rekomendasi" label="Rekomendasi" :error="form.errors.rekomendasi" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">Simpan Laporan</AppButton>
          <Link :href="route('realisasi.laporan.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
