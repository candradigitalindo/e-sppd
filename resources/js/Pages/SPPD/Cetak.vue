<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import PrintLayout from '@/Layouts/PrintLayout.vue'
import KopSurat from '@/Components/Print/KopSurat.vue'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ sppd: Object })
const inst = computed(() => usePage().props.instansi ?? {})

const p = props.sppd.pegawai ?? {}
const kotaTujuan = props.sppd.kota_tujuan?.nama ?? props.sppd.kota_tujuan_lainnya ?? '-'
const lamaHari = props.sppd.lama_perjalanan ?? '-'

const transportasiLabel = {
  pesawat:           'Pesawat Udara',
  kereta:            'Kereta Api',
  kapal:             'Kapal Laut',
  bus:               'Bus / Kendaraan Umum',
  kendaraan_dinas:   'Kendaraan Dinas',
  kendaraan_pribadi: 'Kendaraan Pribadi',
}
</script>

<template>
  <PrintLayout title="SPPD">
    <!-- KOP Surat -->
    <KopSurat />

    <!-- Judul -->
    <div style="text-align:center; margin:16pt 0 4pt;">
      <p style="font-size:13pt; font-weight:bold; letter-spacing:1px; text-decoration:underline; text-transform:uppercase; margin:0;">
        SURAT PERJALANAN DINAS
      </p>
      <p style="font-size:10pt; margin:2pt 0 0;">(SPPD)</p>
      <p style="font-size:11pt; margin:6pt 0 0;">Nomor: {{ sppd.nomor }}</p>
    </div>

    <!-- Body table (government form rows) -->
    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:14pt;">
      <tbody>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; width:8%; text-align:center; vertical-align:top;">1.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; width:38%; vertical-align:top;">Pejabat yang memberi perintah</td>
          <td style="border:1px solid #333; padding:5pt 8pt;">{{ inst.pejabat_jabatan }}</td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">2.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">Nama / NIP Pegawai yang diperintahkan</td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            <strong>{{ p.nama ?? '-' }}</strong><br>
            NIP. {{ p.nip ?? '-' }}
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">3.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">
            a. Jabatan / Instansi<br>
            b. Tingkat / Golongan<br>
          </td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            a. {{ p.jabatan?.nama ?? '-' }}<br>
            b. {{ p.golongan?.kode ?? '-' }} / {{ p.pangkat ?? '-' }}
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">4.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">Maksud Perjalanan Dinas</td>
          <td style="border:1px solid #333; padding:5pt 8pt;">{{ sppd.keperluan }}</td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">5.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">
            a. Tempat berangkat<br>
            b. Tempat tujuan
          </td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            a. {{ sppd.kota_asal }}<br>
            b. <strong>{{ kotaTujuan }}</strong>
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">6.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">
            a. Lamanya perjalanan dinas<br>
            b. Tanggal berangkat<br>
            c. Tanggal harus kembali
          </td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            a. {{ lamaHari }} hari<br>
            b. {{ formatDate(sppd.tanggal_berangkat) }}<br>
            c. {{ formatDate(sppd.tanggal_kembali) }}
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">7.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">Transportasi yang digunakan</td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            {{ transportasiLabel[sppd.transportasi] ?? sppd.transportasi }}
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">8.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">Pembebanan anggaran</td>
          <td style="border:1px solid #333; padding:5pt 8pt;">
            Instansi : {{ inst.nama }}<br>
            Kode Akun : {{ sppd.kode_akun ?? sppd.jenis_perjalanan?.replace(/_/g, ' ') ?? '-' }}
          </td>
        </tr>

        <tr>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:center; vertical-align:top;">9.</td>
          <td style="border:1px solid #333; padding:5pt 8pt; vertical-align:top;">Keterangan lain-lain</td>
          <td style="border:1px solid #333; padding:5pt 8pt; min-height:30pt;">
            {{ sppd.keterangan ?? '-' }}
          </td>
        </tr>

      </tbody>
    </table>

    <!-- TTD Pemberi Izin -->
    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:0;">
      <tbody>
        <tr>
          <td style="border:1px solid #333; padding:8pt 10pt; width:50%; vertical-align:top;">
            <p style="text-align:center; margin:0 0 6pt; font-weight:bold;">Dikeluarkan di {{ inst.kota }}</p>
            <p style="text-align:center; margin:0 0 42pt;">
              pada tanggal {{ formatDate(sppd.created_at ?? new Date()) }}
            </p>
            <p style="text-align:center; margin:0 0 2pt;">{{ inst.pejabat_jabatan }},</p>
            <div style="height:52pt;"></div>
            <p style="text-align:center; margin:0; font-weight:bold;">{{ inst.pejabat_nama }}</p>
            <p style="text-align:center; margin:2pt 0 0; font-size:10pt;">NIP. {{ inst.pejabat_nip }}</p>
          </td>
          <td style="border:1px solid #333; padding:8pt 10pt; width:50%; vertical-align:top;"></td>
        </tr>
      </tbody>
    </table>

    <!-- Riwayat Keberangkatan / Kedatangan -->
    <div style="margin-top:18pt;">
      <p style="font-weight:bold; font-size:11pt; margin-bottom:4pt;">CATATAN KEBERANGKATAN / KEDATANGAN</p>
      <table style="width:100%; border-collapse:collapse; font-size:10.5pt;">
        <thead>
          <tr style="background:#f3f4f6; text-align:center;">
            <th style="border:1px solid #333; padding:5pt 6pt; width:30%;">Keterangan</th>
            <th style="border:1px solid #333; padding:5pt 6pt; width:35%;">Berangkat dari</th>
            <th style="border:1px solid #333; padding:5pt 6pt; width:35%;">Tiba di</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="border:1px solid #333; padding:4pt 6pt;">Tempat</td>
            <td style="border:1px solid #333; padding:4pt 6pt; min-height:18pt;"></td>
            <td style="border:1px solid #333; padding:4pt 6pt;"></td>
          </tr>
          <tr>
            <td style="border:1px solid #333; padding:4pt 6pt;">Tanggal</td>
            <td style="border:1px solid #333; padding:4pt 6pt;"></td>
            <td style="border:1px solid #333; padding:4pt 6pt;"></td>
          </tr>
          <tr>
            <td style="border:1px solid #333; padding:4pt 6pt;">Kepala Instansi / Pejabat</td>
            <td style="border:1px solid #333; padding:30pt 6pt;"></td>
            <td style="border:1px solid #333; padding:30pt 6pt;"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Perhitungan Biaya (jika ada) -->
    <div v-if="sppd.total_biaya" style="margin-top:18pt; page-break-inside:avoid;">
      <p style="font-weight:bold; font-size:11pt; margin-bottom:6pt;">PERHITUNGAN BIAYA PERJALANAN DINAS</p>
      <table style="width:100%; border-collapse:collapse; font-size:10.5pt;">
        <thead>
          <tr style="background:#f3f4f6;">
            <th style="border:1px solid #333; padding:5pt 8pt; text-align:left; width:60%;">Uraian</th>
            <th style="border:1px solid #333; padding:5pt 8pt; text-align:right; width:40%;">Jumlah (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="b in sppd.perhitungan_biaya" :key="b.id">
            <td style="border:1px solid #333; padding:4pt 8pt; text-transform:capitalize;">
              {{ b.komponen?.replace(/_/g, ' ') ?? b.keterangan }}
            </td>
            <td style="border:1px solid #333; padding:4pt 8pt; text-align:right; font-family:monospace;">
              {{ formatRupiah(b.jumlah) }}
            </td>
          </tr>
          <tr style="font-weight:bold; background:#f3f4f6;">
            <td style="border:1px solid #333; padding:5pt 8pt; text-align:right;">TOTAL</td>
            <td style="border:1px solid #333; padding:5pt 8pt; text-align:right; font-family:monospace;">{{ formatRupiah(sppd.total_biaya) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </PrintLayout>
</template>
