<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import PrintLayout from '@/Layouts/PrintLayout.vue'
import KopSurat from '@/Components/Print/KopSurat.vue'
import { formatDate } from '@/utils/date.js'

const props = defineProps({ spt: Object })
const inst = computed(() => usePage().props.instansi ?? {})

const kotaTujuan = props.spt.kota_tujuan?.nama ?? props.spt.kota_tujuan_lainnya ?? '-'
</script>

<template>
  <PrintLayout title="Surat Perintah Tugas">
    <!-- KOP Surat -->
    <KopSurat />

    <!-- Judul Surat -->
    <div style="text-align:center; margin: 18pt 0 6pt;">
      <p style="font-size:13pt; font-weight:bold; letter-spacing:1px; text-decoration:underline; text-transform:uppercase; margin:0;">
        SURAT PERINTAH TUGAS
      </p>
      <p style="margin:4pt 0 0; font-size:11pt;">Nomor : {{ spt.nomor }}</p>
    </div>

    <!-- Pembuka -->
    <p style="margin: 14pt 0 8pt; text-align:justify; line-height:1.7;">
      Berdasarkan keperluan dinas dan dalam rangka pelaksanaan tugas kedinasan,
      dengan ini diperintahkan kepada pegawai yang namanya tersebut di bawah ini:
    </p>

    <!-- Tabel Pegawai -->
    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-bottom:14pt;">
      <thead>
        <tr style="background:#f3f4f6;">
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:6%;">No</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:26%;">Nama</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:22%;">NIP</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:28%;">Jabatan</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:18%;">Gol/Ruang</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(p, i) in spt.pegawais" :key="p.id">
          <td style="border:1px solid #333; padding:4pt 8pt; text-align:center;">{{ i + 1 }}</td>
          <td style="border:1px solid #333; padding:4pt 8pt;">{{ p.nama }}</td>
          <td style="border:1px solid #333; padding:4pt 8pt; font-family:monospace; font-size:10pt;">{{ p.nip }}</td>
          <td style="border:1px solid #333; padding:4pt 8pt;">{{ p.jabatan?.nama ?? '-' }}</td>
          <td style="border:1px solid #333; padding:4pt 8pt; text-align:center;">{{ p.golongan?.kode ?? '-' }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Rincian Tugas -->
    <p style="margin: 0 0 6pt; line-height:1.7;">
      Untuk melaksanakan tugas <strong>{{ spt.keperluan }}</strong> dengan rincian sebagai berikut:
    </p>
    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-bottom:16pt;">
      <tbody>
        <tr>
          <td style="padding:3pt 0; width:160pt; vertical-align:top;">Kota / Tempat Tujuan</td>
          <td style="padding:3pt 4pt; width:10pt;">:</td>
          <td style="padding:3pt 0;"><strong>{{ kotaTujuan }}</strong></td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Kota Asal</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ spt.kota_asal }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Tanggal Berangkat</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ formatDate(spt.tanggal_berangkat) }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Tanggal Kembali</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ formatDate(spt.tanggal_kembali) }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Dasar Hukum / Surat Undangan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ spt.dasar_hukum ?? '-' }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Penutup -->
    <p style="text-align:justify; line-height:1.7; margin-bottom:24pt;">
      Demikian Surat Perintah Tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan melaporkan
      hasilnya kepada pimpinan.
    </p>

    <!-- TTD -->
    <div style="display:flex; justify-content:flex-end;">
      <div style="text-align:center; min-width:200pt;">
        <p style="margin:0;">{{ inst.kota }}, {{ formatDate(spt.tanggal ?? new Date()) }}</p>
        <p style="margin:4pt 0 0;">{{ inst.pejabat_jabatan }},</p>
        <div style="height:56pt;"></div>
        <p style="margin:0; font-weight:bold; border-top:1px solid #000; padding-top:4pt; display:inline-block; min-width:180pt;">
          {{ inst.pejabat_nama }}
        </p>
        <p style="margin:2pt 0 0; font-size:10pt;">NIP. {{ inst.pejabat_nip }}</p>
      </div>
    </div>
  </PrintLayout>
</template>
