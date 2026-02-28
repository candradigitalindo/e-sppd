<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import PrintLayout from '@/Layouts/PrintLayout.vue'
import KopSurat from '@/Components/Print/KopSurat.vue'
import { formatDate } from '@/utils/date.js'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ spj: Object })
const inst = computed(() => usePage().props.instansi ?? {})

const sppd   = props.spj.sppd  ?? {}
const pegawai = sppd.pegawai   ?? {}
const rekap  = props.spj.rekap_biaya ?? []

const terbilang = (angka) => {
  // Simple terbilang for display — replace with full library if needed
  return `${angka.toLocaleString('id-ID')}`
}
</script>

<template>
  <PrintLayout title="Kwitansi SPJ">
    <!-- KOP Surat -->
    <KopSurat />

    <!-- Judul -->
    <div style="text-align:center; margin:16pt 0 4pt;">
      <p style="font-size:13pt; font-weight:bold; letter-spacing:1px; text-decoration:underline; text-transform:uppercase; margin:0;">
        SURAT PERTANGGUNGJAWABAN (SPJ)
      </p>
      <p style="font-size:11pt; margin:6pt 0 0;">Nomor: {{ spj.nomor }}</p>
    </div>

    <!-- Info SPJ -->
    <table style="width:100%; border-collapse:collapse; font-size:11pt; margin-top:14pt;">
      <tbody>
        <tr>
          <td style="padding:3pt 0; width:160pt;">Nama</td>
          <td style="padding:3pt 4pt; width:12pt;">:</td>
          <td style="padding:3pt 0;"><strong>{{ pegawai.nama ?? '-' }}</strong></td>
          <td style="padding:3pt 0; width:130pt;">NIP</td>
          <td style="padding:3pt 4pt; width:12pt;">:</td>
          <td style="padding:3pt 0; font-family:monospace;">{{ pegawai.nip ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Jabatan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ pegawai.jabatan?.nama ?? '-' }}</td>
          <td style="padding:3pt 0;">Golongan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ pegawai.golongan?.kode ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">No. SPPD</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ sppd.nomor ?? '-' }}</td>
          <td style="padding:3pt 0;">Tanggal SPJ</td>
          <td style="padding:3pt 4pt;">:</td>
          <td style="padding:3pt 0;">{{ formatDate(spj.tanggal) }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Tujuan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td colspan="4" style="padding:3pt 0;"><strong>{{ sppd.kota_tujuan?.nama ?? sppd.kota_tujuan_lainnya ?? '-' }}</strong></td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Keperluan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td colspan="4" style="padding:3pt 0;">{{ sppd.keperluan ?? '-' }}</td>
        </tr>
        <tr>
          <td style="padding:3pt 0;">Tanggal Perjalanan</td>
          <td style="padding:3pt 4pt;">:</td>
          <td colspan="4" style="padding:3pt 0;">
            {{ formatDate(sppd.tanggal_berangkat) }} s/d {{ formatDate(sppd.tanggal_kembali) }}
            ({{ sppd.lama_perjalanan }} hari)
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Kwitansi box -->
    <div style="margin:18pt 0; border:2px solid #000; padding:14pt 16pt;">
      <div style="text-align:center; font-size:14pt; font-weight:bold; letter-spacing:2px; margin-bottom:10pt;">KWITANSI</div>
      <table style="width:100%; border-collapse:collapse; font-size:11pt;">
        <tbody>
          <tr>
            <td style="padding:3pt 0; width:170pt;">Sudah terima dari</td>
            <td style="padding:3pt 4pt; width:12pt;">:</td>
            <td style="padding:3pt 0;">{{ inst.nama }}</td>
          </tr>
          <tr>
            <td style="padding:3pt 0;">Banyaknya uang</td>
            <td style="padding:3pt 4pt;">:</td>
            <td style="padding:3pt 0; font-weight:bold;">{{ formatRupiah(spj.total_realisasi) }}</td>
          </tr>
          <tr>
            <td style="padding:3pt 0; vertical-align:top;">Terbilang</td>
            <td style="padding:3pt 4pt; vertical-align:top;">:</td>
            <td style="padding:3pt 0; font-style:italic;">({{ terbilang(spj.total_realisasi ?? 0) }})</td>
          </tr>
          <tr>
            <td style="padding:3pt 0;">Untuk pembayaran</td>
            <td style="padding:3pt 4pt;">:</td>
            <td style="padding:3pt 0;">Biaya Perjalanan Dinas ke {{ sppd.kota_tujuan?.nama ?? sppd.kota_tujuan_lainnya ?? '-' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Rekap Biaya table -->
    <p style="font-weight:bold; font-size:11pt; margin:0 0 6pt;">RINCIAN BIAYA PERJALANAN DINAS</p>
    <table style="width:100%; border-collapse:collapse; font-size:10.5pt;">
      <thead>
        <tr style="background:#f3f4f6;">
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:center; width:6%;">No</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:left; width:44%;">Jenis Biaya</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:right; width:25%;">Rencana</th>
          <th style="border:1px solid #333; padding:5pt 8pt; text-align:right; width:25%;">Realisasi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(r, i) in rekap" :key="r.id ?? i">
          <td style="border:1px solid #333; padding:4pt 8pt; text-align:center;">{{ i + 1 }}</td>
          <td style="border:1px solid #333; padding:4pt 8pt; text-transform:capitalize;">
            {{ (r.jenis_biaya ?? r.jenis)?.replace(/_/g, ' ') }}
          </td>
          <td style="border:1px solid #333; padding:4pt 8pt; text-align:right; font-family:monospace;">
            {{ formatRupiah(r.rencana ?? r.jumlah_rencana) }}
          </td>
          <td style="border:1px solid #333; padding:4pt 8pt; text-align:right; font-family:monospace; font-weight:bold;">
            {{ formatRupiah(r.realisasi ?? r.jumlah_realisasi) }}
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr style="font-weight:bold; background:#f0fdf4;">
          <td colspan="2" style="border:1px solid #333; padding:5pt 8pt; text-align:right;">TOTAL</td>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:right; font-family:monospace;">
            {{ formatRupiah(spj.total_rencana) }}
          </td>
          <td style="border:1px solid #333; padding:5pt 8pt; text-align:right; font-family:monospace;">
            {{ formatRupiah(spj.total_realisasi) }}
          </td>
        </tr>
        <tr>
          <td colspan="2" style="border:1px solid #333; padding:5pt 8pt; text-align:right;">Sisa Anggaran</td>
          <td colspan="2" style="border:1px solid #333; padding:5pt 8pt; text-align:right; font-family:monospace;">
            {{ formatRupiah(spj.sisa_anggaran) }}
          </td>
        </tr>
      </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <table style="width:100%; margin-top:20pt; font-size:11pt; border-collapse:collapse;">
      <tbody>
        <tr>
          <td style="width:50%; text-align:center; vertical-align:top; padding:0 12pt;">
            <p style="margin:0 0 4pt;">Mengetahui,</p>
            <p style="margin:0 0 54pt;">{{ inst.pejabat_jabatan }},</p>
            <p style="margin:0; font-weight:bold; border-top:1px solid #000; padding-top:4pt; display:inline-block; min-width:160pt;">
              {{ inst.pejabat_nama }}
            </p>
            <p style="margin:2pt 0 0; font-size:10pt;">NIP. {{ inst.pejabat_nip }}</p>
          </td>
          <td style="width:50%; text-align:center; vertical-align:top; padding:0 12pt;">
            <p style="margin:0 0 4pt;">
              {{ inst.kota }}, {{ formatDate(spj.tanggal ?? new Date()) }}
            </p>
            <p style="margin:0 0 4pt;">Yang menerima,</p>
            <div style="height:52pt;"></div>
            <p style="margin:0; font-weight:bold; border-top:1px solid #000; padding-top:4pt; display:inline-block; min-width:160pt;">
              {{ pegawai.nama ?? '____________________' }}
            </p>
            <p style="margin:2pt 0 0; font-size:10pt;">NIP. {{ pegawai.nip ?? '-' }}</p>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Catatan -->
    <div v-if="spj.catatan" style="margin-top:16pt; border-top:1px solid #ccc; padding-top:8pt;">
      <p style="font-size:10pt; color:#555; margin:0;"><strong>Catatan:</strong> {{ spj.catatan }}</p>
    </div>
  </PrintLayout>
</template>
