<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  /** Override instansi data (untuk preview di halaman pengaturan).
   *  Jika tidak diisi, diambil dari shared prop Inertia. */
  instansi: { type: Object, default: null },
  /** Tampilkan garis tebal bawah KOP */
  border: { type: Boolean, default: true },
})

const page = usePage()

const data = computed(() => props.instansi ?? page.props.instansi ?? {})
const posisi = computed(() => data.value.logo_posisi ?? 'kiri')
const logo1  = computed(() => data.value.logo1_url ?? null)
const logo2  = computed(() => data.value.logo2_url ?? null)
const hasAnyLogo = computed(() => logo1.value || logo2.value)
</script>

<template>
  <!-- wrapper: border bawah dan font surat -->
  <div :style="[
    'width:100%; font-family: Times New Roman, Times, serif;',
    border ? 'border-bottom: 3pt solid #111; padding-bottom: 8pt; margin-bottom: 14pt;' : 'margin-bottom: 8pt;'
  ]">

    <!-- ===== POSISI: ATAS — logo di atas teks, semua centered ===== -->
    <template v-if="posisi === 'atas'">
      <div v-if="hasAnyLogo" style="display:flex; justify-content:center; align-items:center; gap:16pt; margin-bottom:6pt;">
        <img v-if="logo1" :src="logo1" style="max-height:60pt; max-width:60pt; object-fit:contain;" alt="Logo 1" />
        <img v-if="logo2" :src="logo2" style="max-height:60pt; max-width:60pt; object-fit:contain;" alt="Logo 2" />
      </div>
      <div style="text-align:center; width:100%;">
        <p style="font-size:16pt; font-weight:800; text-transform:uppercase; letter-spacing:0.5pt; line-height:1.3; margin:0 0 2pt;">{{ data.nama }}</p>
        <p v-if="data.singkatan" style="font-size:11pt; margin:0 0 2pt;">{{ data.singkatan }}</p>
        <p style="font-size:10pt; margin:0 0 1pt;">{{ [data.alamat, data.kota, data.kode_pos].filter(Boolean).join(', ') }}</p>
        <p v-if="data.telepon || data.fax || data.website" style="font-size:9.5pt; margin:0 0 1pt;">
          <template v-if="data.telepon">Telepon: {{ data.telepon }}</template>
          <template v-if="data.fax">&nbsp;&nbsp;Fax: {{ data.fax }}</template>
          <template v-if="data.website">&nbsp;&nbsp;{{ data.website }}</template>
        </p>
        <p v-if="data.email" style="font-size:9.5pt; margin:0;">Email: {{ data.email }}</p>
      </div>
    </template>

    <!-- ===== POSISI: KIRI — logo kiri, teks di kanan ===== -->
    <template v-else-if="posisi === 'kiri'">
      <div style="display:flex; align-items:center; gap:14pt; width:100%;">
        <!-- Logos -->
        <div v-if="hasAnyLogo" style="display:flex; align-items:center; gap:8pt; flex-shrink:0;">
          <img v-if="logo1" :src="logo1" style="max-height:65pt; max-width:65pt; object-fit:contain;" alt="Logo 1" />
          <img v-if="logo2" :src="logo2" style="max-height:65pt; max-width:65pt; object-fit:contain;" alt="Logo 2" />
        </div>
        <div v-else style="width:60pt; height:60pt; border:1.5pt dashed #bbb; border-radius:4pt; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
          <span style="font-size:8pt; color:#aaa; font-family:Arial,sans-serif;">LOGO</span>
        </div>
        <!-- Text -->
        <div style="flex:1; text-align:center;">
          <p style="font-size:16pt; font-weight:800; text-transform:uppercase; letter-spacing:0.5pt; line-height:1.3; margin:0 0 2pt;">{{ data.nama }}</p>
          <p v-if="data.singkatan" style="font-size:11pt; margin:0 0 2pt;">{{ data.singkatan }}</p>
          <p style="font-size:10pt; margin:0 0 1pt;">{{ [data.alamat, data.kota, data.kode_pos].filter(Boolean).join(', ') }}</p>
          <p v-if="data.telepon || data.fax || data.website" style="font-size:9.5pt; margin:0 0 1pt;">
            <template v-if="data.telepon">Telepon: {{ data.telepon }}</template>
            <template v-if="data.fax">&nbsp;&nbsp;Fax: {{ data.fax }}</template>
            <template v-if="data.website">&nbsp;&nbsp;{{ data.website }}</template>
          </p>
          <p v-if="data.email" style="font-size:9.5pt; margin:0;">Email: {{ data.email }}</p>
        </div>
      </div>
    </template>

    <!-- ===== POSISI: KANAN — teks di kiri, logo kanan ===== -->
    <template v-else>
      <div style="display:flex; align-items:center; gap:14pt; width:100%;">
        <!-- Text -->
        <div style="flex:1; text-align:center;">
          <p style="font-size:16pt; font-weight:800; text-transform:uppercase; letter-spacing:0.5pt; line-height:1.3; margin:0 0 2pt;">{{ data.nama }}</p>
          <p v-if="data.singkatan" style="font-size:11pt; margin:0 0 2pt;">{{ data.singkatan }}</p>
          <p style="font-size:10pt; margin:0 0 1pt;">{{ [data.alamat, data.kota, data.kode_pos].filter(Boolean).join(', ') }}</p>
          <p v-if="data.telepon || data.fax || data.website" style="font-size:9.5pt; margin:0 0 1pt;">
            <template v-if="data.telepon">Telepon: {{ data.telepon }}</template>
            <template v-if="data.fax">&nbsp;&nbsp;Fax: {{ data.fax }}</template>
            <template v-if="data.website">&nbsp;&nbsp;{{ data.website }}</template>
          </p>
          <p v-if="data.email" style="font-size:9.5pt; margin:0;">Email: {{ data.email }}</p>
        </div>
        <!-- Logos -->
        <div v-if="hasAnyLogo" style="display:flex; align-items:center; gap:8pt; flex-shrink:0;">
          <img v-if="logo1" :src="logo1" style="max-height:65pt; max-width:65pt; object-fit:contain;" alt="Logo 1" />
          <img v-if="logo2" :src="logo2" style="max-height:65pt; max-width:65pt; object-fit:contain;" alt="Logo 2" />
        </div>
        <div v-else style="width:60pt; height:60pt; border:1.5pt dashed #bbb; border-radius:4pt; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
          <span style="font-size:8pt; color:#aaa; font-family:Arial,sans-serif;">LOGO</span>
        </div>
      </div>
    </template>

  </div>
</template>
