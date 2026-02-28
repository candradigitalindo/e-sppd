<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ spt: Object, nomor: String, pegawais: Array, kotaTujuans: Array, programs: Array })
const isEdit = !!props.spt

const form = useForm({
  nomor: props.spt?.nomor ?? props.nomor ?? '',
  tanggal: props.spt?.tanggal ?? '',
  program_kegiatan_id: props.spt?.program_kegiatan_id ?? '',
  dasar_hukum: props.spt?.dasar_hukum ?? '',
  keperluan: props.spt?.keperluan ?? '',
  kota_asal: props.spt?.kota_asal ?? '',
  kota_tujuan_id: props.spt?.kota_tujuan_id ?? '',
  tanggal_berangkat: props.spt?.tanggal_berangkat ?? '',
  tanggal_kembali: props.spt?.tanggal_kembali ?? '',
  pegawai_ids: props.spt?.pegawais?.map(p => p.id) ?? [],
})

function togglePegawai(id) {
  const idx = form.pegawai_ids.indexOf(id)
  if (idx === -1) form.pegawai_ids.push(id)
  else form.pegawai_ids.splice(idx, 1)
}

function submit() {
  if (isEdit) form.put(route('surat-tugas.update', props.spt.id))
  else form.post(route('surat-tugas.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Surat Tugas' : 'Buat Surat Tugas'" :breadcrumbs="[{ label: 'Dokumen' }, { label: 'Surat Tugas', href: route('surat-tugas.index') }, { label: isEdit ? 'Edit' : 'Buat' }]" />
    <form @submit.prevent="submit">
      <div class="space-y-5 max-w-3xl">
        <AppCard title="Informasi Surat Tugas">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <AppInput v-model="form.nomor" label="Nomor SPT" required :disabled="isEdit" :error="form.errors.nomor" />
            <AppInput v-model="form.tanggal" label="Tanggal" type="date" required :error="form.errors.tanggal" />
            <AppSelect v-model="form.program_kegiatan_id" label="Program Kegiatan" :options="programs" option-label="nama" :error="form.errors.program_kegiatan_id" />
            <AppInput v-model="form.dasar_hukum" label="Dasar Hukum" :error="form.errors.dasar_hukum" />
            <div class="sm:col-span-2">
              <AppInput v-model="form.keperluan" label="Keperluan" required :error="form.errors.keperluan" />
            </div>
            <AppInput v-model="form.kota_asal" label="Kota Asal" required :error="form.errors.kota_asal" />
            <AppSelect v-model="form.kota_tujuan_id" label="Kota Tujuan" :options="kotaTujuans" option-label="nama" :error="form.errors.kota_tujuan_id" />
            <AppInput v-model="form.tanggal_berangkat" label="Tanggal Berangkat" type="date" required :error="form.errors.tanggal_berangkat" />
            <AppInput v-model="form.tanggal_kembali" label="Tanggal Kembali" type="date" required :error="form.errors.tanggal_kembali" />
          </div>
        </AppCard>

        <AppCard title="Pilih Pegawai yang Ditugaskan" v-if="!isEdit">
          <p v-if="form.errors.pegawai_ids" class="mb-3 text-xs text-red-600">{{ form.errors.pegawai_ids }}</p>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto">
            <label
              v-for="p in pegawais"
              :key="p.id"
              class="flex items-center gap-3 rounded-lg border px-3 py-2.5 cursor-pointer hover:bg-green-50 transition-colors"
              :class="form.pegawai_ids.includes(p.id) ? 'border-green-400 bg-green-50' : 'border-gray-200'"
            >
              <input type="checkbox" :value="p.id" :checked="form.pegawai_ids.includes(p.id)" class="rounded border-gray-300 text-green-600" @change="togglePegawai(p.id)" />
              <div>
                <p class="text-sm font-medium text-gray-800">{{ p.nama }}</p>
                <p class="text-xs text-gray-400">{{ p.jabatan?.nama ?? '' }}</p>
              </div>
            </label>
          </div>
        </AppCard>

        <div class="flex gap-3">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Buat Surat Tugas' }}</AppButton>
          <Link :href="route('surat-tugas.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
