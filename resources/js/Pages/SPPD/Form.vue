<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({
  sppd: Object,
  nomor: String,
  sptList: Array,
  pegawais: Array,
  kotaTujuans: Array,
  programs: Array,
  jenisPerjalananOptions: Array,
})
const isEdit = !!props.sppd

const defaultJenis = [
  { value: 'dalam_negeri_biasa', label: 'Dalam Negeri Biasa (524111)' },
  { value: 'dalam_kota',         label: 'Dalam Kota (524113)' },
  { value: 'paket_meeting',      label: 'Paket Meeting (524114)' },
  { value: 'luar_negeri',        label: 'Luar Negeri (524121)' },
  { value: 'pindah_tugas',       label: 'Pindah Tugas (524119)' },
]
const jenisOpts = (props.jenisPerjalananOptions ?? defaultJenis).map(o => ({ id: o.value, nama: o.label }))

const transportasiOpts = [
  { id: 'pesawat', nama: 'Pesawat Udara' },
  { id: 'kereta', nama: 'Kereta Api' },
  { id: 'kapal', nama: 'Kapal Laut' },
  { id: 'bus', nama: 'Bus/Darat' },
  { id: 'kendaraan_dinas', nama: 'Kendaraan Dinas' },
  { id: 'kendaraan_pribadi', nama: 'Kendaraan Pribadi' },
]

const form = useForm({
  nomor: props.sppd?.nomor ?? props.nomor ?? '',
  surat_tugas_id: props.sppd?.surat_tugas_id ?? '',
  pegawai_id: props.sppd?.pegawai_id ?? '',
  program_kegiatan_id: props.sppd?.program_kegiatan_id ?? '',
  kota_asal: props.sppd?.kota_asal ?? '',
  kota_tujuan_id: props.sppd?.kota_tujuan_id ?? '',
  tanggal_berangkat: props.sppd?.tanggal_berangkat ?? '',
  tanggal_kembali: props.sppd?.tanggal_kembali ?? '',
  transportasi: props.sppd?.transportasi ?? '',
  keperluan: props.sppd?.keperluan ?? '',
  // Kolom regulasi PMK 113/2012
  jenis_perjalanan: props.sppd?.jenis_perjalanan ?? 'dalam_negeri_biasa',
  kode_akun: props.sppd?.kode_akun ?? '',
  penginapan_at_cost: props.sppd?.penginapan_at_cost ?? false,
})

function submit() {
  if (isEdit) form.put(route('sppd.update', props.sppd.id))
  else form.post(route('sppd.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit SPPD' : 'Buat SPPD'" :breadcrumbs="[{ label: 'Dokumen' }, { label: 'SPPD', href: route('sppd.index') }, { label: isEdit ? 'Edit' : 'Buat' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data SPPD" class="max-w-3xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <AppInput v-model="form.nomor" label="Nomor SPPD" required :disabled="isEdit" :error="form.errors.nomor" />
          <AppSelect v-model="form.surat_tugas_id" label="Surat Tugas" :options="sptList" option-label="nomor" :error="form.errors.surat_tugas_id" />
          <AppSelect v-model="form.pegawai_id" label="Pegawai" required :options="pegawais" option-label="nama" :error="form.errors.pegawai_id" />
          <AppSelect v-model="form.program_kegiatan_id" label="Program Kegiatan" :options="programs" option-label="nama" :error="form.errors.program_kegiatan_id" />
          <AppInput v-model="form.kota_asal" label="Kota Asal" required :error="form.errors.kota_asal" />
          <AppSelect v-model="form.kota_tujuan_id" label="Kota Tujuan" :options="kotaTujuans" option-label="nama" :error="form.errors.kota_tujuan_id" />
          <AppInput v-model="form.tanggal_berangkat" label="Tanggal Berangkat" type="date" required :error="form.errors.tanggal_berangkat" />
          <AppInput v-model="form.tanggal_kembali" label="Tanggal Kembali" type="date" required :error="form.errors.tanggal_kembali" />
          <AppSelect v-model="form.transportasi" label="Transportasi" required :options="transportasiOpts" option-label="nama" :error="form.errors.transportasi" />
          <!-- PMK 113/2012: Jenis Perjalanan & Kode MAK -->
          <AppSelect v-model="form.jenis_perjalanan" label="Jenis Perjalanan Dinas" :options="jenisOpts" option-label="nama" :error="form.errors.jenis_perjalanan" />
          <div class="sm:col-span-2">
            <AppInput v-model="form.keperluan" label="Keperluan" required :error="form.errors.keperluan" />
          </div>
          <!-- PMK 113/2012: Penginapan At-Cost -->
          <div class="sm:col-span-2 flex items-center gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
            <input
              id="penginapan_at_cost"
              v-model="form.penginapan_at_cost"
              type="checkbox"
              class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
            />
            <label for="penginapan_at_cost" class="text-sm text-gray-700">
              <span class="font-medium">Penginapan At-Cost (30%)</span>
              <span class="ml-1 text-gray-500">— Tidak tersedia hotel/penginapan, ganti 30% dari standar biaya (PMK 113/2012)</span>
            </label>
          </div>
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Buat SPPD' }}</AppButton>
          <Link :href="route('sppd.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>

