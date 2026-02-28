<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { ref, computed, nextTick } from 'vue'

// Active section tracking
const activeSection = ref('overview')

const sections = [
  { id: 'overview',      label: 'Gambaran Umum',       icon: 'eye' },
  { id: 'alur',          label: 'Alur Proses SPPD',    icon: 'flow' },
  { id: 'master',        label: '1. Master Data',      icon: 'database' },
  { id: 'perencanaan',   label: '2. Perencanaan',      icon: 'calendar' },
  { id: 'dokumen',       label: '3. Dokumen',          icon: 'document' },
  { id: 'keuangan',      label: '4. Keuangan',         icon: 'money' },
  { id: 'realisasi',     label: '5. Realisasi',        icon: 'chart' },
  { id: 'approval',      label: '6. Approval',         icon: 'check' },
  { id: 'laporan',       label: '7. Laporan',          icon: 'report' },
  { id: 'pengaturan',    label: '8. Pengaturan',       icon: 'setting' },
  { id: 'tips',          label: 'Tips & FAQ',          icon: 'info' },
]

function scrollTo(id) {
  activeSection.value = id
  nextTick(() => {
    const el = document.getElementById(`doc-${id}`)
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' })
  })
}

// Search
const search = ref('')
const filteredSections = computed(() => {
  if (!search.value) return sections
  const q = search.value.toLowerCase()
  return sections.filter(s => s.label.toLowerCase().includes(q))
})

// FAQ data (dipisah agar tidak ada konflik quotes di template)
const faqList = [
  { q: 'Bagaimana urutan yang benar mengisi data pertama kali?', a: 'Mulai dari: (1) Pengaturan Instansi → (2) Master Data (Golongan, Jabatan, Unit Kerja, Kota Tujuan, Standar Biaya, Pegawai) → (3) Program Kegiatan. Setelah itu, baru bisa membuat rencana perjalanan dan dokumen SPPD.' },
  { q: 'Kenapa biaya perjalanan tidak muncul di SPPD?', a: 'Pastikan data Standar Biaya sudah diisi untuk golongan pegawai dan kota tujuan yang bersangkutan. Sistem menghitung biaya otomatis berdasarkan data tersebut.' },
  { q: 'Siapa yang bisa menyetujui perjalanan dinas?', a: 'User dengan role yang memiliki permission "approval" dapat mengakses halaman Persetujuan. Atur melalui Sistem → Role & Hak Akses.' },
  { q: 'Apakah dokumen bisa dicetak ulang?', a: 'Ya. Buka detail Surat Tugas, SPPD, atau SPJ, lalu klik tombol Cetak. Dokumen akan dibuka dalam format siap cetak.' },
  { q: 'Bagaimana jika perjalanan dibatalkan?', a: 'Ubah status rencana perjalanan menjadi "Dibatalkan". Jika uang muka sudah cair, buat SPJ dengan realisasi biaya Rp 0 dan kembalikan seluruh uang muka.' },
]
</script>

<template>
  <AppLayout>
    <PageHeader title="Dokumentasi Aplikasi" description="Panduan lengkap penggunaan aplikasi e-SPPD" :breadcrumbs="[{ label: 'Dokumentasi' }]" />

    <div class="flex gap-6 items-start">

      <!-- Sidebar Navigasi Dokumentasi (sticky) -->
      <aside class="hidden lg:block w-56 shrink-0 sticky top-0">
        <div class="bg-white rounded-xl border border-gray-200 p-3 space-y-1">
          <input
            v-model="search"
            type="text"
            placeholder="Cari topik..."
            class="w-full text-xs rounded-lg border border-gray-200 px-3 py-2 mb-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
          />
          <button
            v-for="s in filteredSections" :key="s.id"
            @click="scrollTo(s.id)"
            class="w-full text-left px-3 py-2 rounded-lg text-xs font-medium transition-all"
            :class="activeSection === s.id
              ? 'bg-green-50 text-green-700 border border-green-200'
              : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
          >
            {{ s.label }}
          </button>
        </div>
      </aside>

      <!-- Konten Utama -->
      <div class="flex-1 min-w-0 space-y-8 pb-12">

        <!-- ==================== GAMBARAN UMUM ==================== -->
        <section id="doc-overview" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="bg-linear-to-r from-green-600 to-green-700 px-6 py-5">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Gambaran Umum
            </h2>
            <p class="text-green-100 text-sm mt-1">Apa itu e-SPPD dan bagaimana cara kerjanya</p>
          </div>
          <div class="px-6 py-5 space-y-4">
            <p class="text-sm text-gray-700 leading-relaxed">
              <strong>e-SPPD</strong> adalah Sistem Informasi Perjalanan Dinas Elektronik yang mengelola seluruh proses perjalanan dinas, mulai dari perencanaan, pembuatan dokumen (Surat Tugas & SPPD), pengajuan dana, realisasi, hingga pelaporan — dalam satu platform terintegrasi.
            </p>

            <!-- Fitur Utama -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
              <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-100">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-xs font-semibold text-blue-700">Dokumen Digital</p>
                <p class="text-[10px] text-blue-500 mt-0.5">ST, SPPD, SPJ otomatis</p>
              </div>
              <div class="bg-green-50 rounded-lg p-3 text-center border border-green-100">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs font-semibold text-green-700">Persetujuan Online</p>
                <p class="text-[10px] text-green-500 mt-0.5">Approval digital cepat</p>
              </div>
              <div class="bg-amber-50 rounded-lg p-3 text-center border border-amber-100">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/></svg>
                </div>
                <p class="text-xs font-semibold text-amber-700">Biaya Otomatis</p>
                <p class="text-[10px] text-amber-500 mt-0.5">Perhitungan standar biaya</p>
              </div>
              <div class="bg-purple-50 rounded-lg p-3 text-center border border-purple-100">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                  <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <p class="text-xs font-semibold text-purple-700">Laporan Lengkap</p>
                <p class="text-[10px] text-purple-500 mt-0.5">Rekap & analisis data</p>
              </div>
            </div>

            <!-- Role -->
            <div class="bg-gray-50 rounded-lg p-4 mt-4 border border-gray-100">
              <p class="text-xs font-semibold text-gray-700 mb-2">Peran Pengguna dalam Aplikasi:</p>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                <div class="flex items-start gap-2">
                  <span class="w-2 h-2 bg-green-500 rounded-full mt-1 shrink-0"></span>
                  <div><strong class="text-gray-800">Admin</strong> <span class="text-gray-500">— Kelola master data, user, pengaturan sistem</span></div>
                </div>
                <div class="flex items-start gap-2">
                  <span class="w-2 h-2 bg-blue-500 rounded-full mt-1 shrink-0"></span>
                  <div><strong class="text-gray-800">Operator</strong> <span class="text-gray-500">— Input perjalanan, dokumen, keuangan</span></div>
                </div>
                <div class="flex items-start gap-2">
                  <span class="w-2 h-2 bg-amber-500 rounded-full mt-1 shrink-0"></span>
                  <div><strong class="text-gray-800">Pimpinan</strong> <span class="text-gray-500">— Approval & monitoring laporan</span></div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== ALUR PROSES ==================== -->
        <section id="doc-alur" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="bg-linear-to-r from-blue-600 to-blue-700 px-6 py-5">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
              Alur Proses SPPD
            </h2>
            <p class="text-blue-100 text-sm mt-1">Diagram alur dari awal hingga selesai</p>
          </div>
          <div class="px-6 py-5">
            <!-- Flow diagram -->
            <div class="flex flex-col items-center gap-0">
              <!-- Step 1 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 border-2 border-green-500">
                  <span class="text-sm font-bold text-green-700">1</span>
                </div>
                <div class="flex-1 bg-green-50 rounded-lg p-3 border border-green-200">
                  <p class="text-xs font-bold text-green-800">Setup Master Data</p>
                  <p class="text-[11px] text-green-600">Pegawai, Unit Kerja, Jabatan, Golongan, Kota, Standar Biaya</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 2 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0 border-2 border-blue-500">
                  <span class="text-sm font-bold text-blue-700">2</span>
                </div>
                <div class="flex-1 bg-blue-50 rounded-lg p-3 border border-blue-200">
                  <p class="text-xs font-bold text-blue-800">Perencanaan</p>
                  <p class="text-[11px] text-blue-600">Buat Program Kegiatan → Rencana Perjalanan</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 3 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 border-2 border-indigo-500">
                  <span class="text-sm font-bold text-indigo-700">3</span>
                </div>
                <div class="flex-1 bg-indigo-50 rounded-lg p-3 border border-indigo-200">
                  <p class="text-xs font-bold text-indigo-800">Buat Dokumen</p>
                  <p class="text-[11px] text-indigo-600">Surat Tugas → SPPD (otomatis kalkulasi biaya)</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 4 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0 border-2 border-amber-500">
                  <span class="text-sm font-bold text-amber-700">4</span>
                </div>
                <div class="flex-1 bg-amber-50 rounded-lg p-3 border border-amber-200">
                  <p class="text-xs font-bold text-amber-800">Approval Pimpinan</p>
                  <p class="text-[11px] text-amber-600">Pimpinan menyetujui / menolak perjalanan dinas</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 5 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center shrink-0 border-2 border-orange-500">
                  <span class="text-sm font-bold text-orange-700">5</span>
                </div>
                <div class="flex-1 bg-orange-50 rounded-lg p-3 border border-orange-200">
                  <p class="text-xs font-bold text-orange-800">Pengajuan & Pencairan Dana</p>
                  <p class="text-[11px] text-orange-600">Pengajuan uang muka perjalanan dinas</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 6 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center shrink-0 border-2 border-cyan-500">
                  <span class="text-sm font-bold text-cyan-700">6</span>
                </div>
                <div class="flex-1 bg-cyan-50 rounded-lg p-3 border border-cyan-200">
                  <p class="text-xs font-bold text-cyan-800">Pelaksanaan & Cetak Dokumen</p>
                  <p class="text-[11px] text-cyan-600">Cetak Surat Tugas & SPPD, laksanakan perjalanan</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 7 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center shrink-0 border-2 border-teal-500">
                  <span class="text-sm font-bold text-teal-700">7</span>
                </div>
                <div class="flex-1 bg-teal-50 rounded-lg p-3 border border-teal-200">
                  <p class="text-xs font-bold text-teal-800">Realisasi & SPJ</p>
                  <p class="text-[11px] text-teal-600">Laporan perjalanan + SPJ (Surat Pertanggungjawaban)</p>
                </div>
              </div>
              <div class="w-0.5 h-5 bg-gray-300"></div>

              <!-- Step 8 -->
              <div class="flex items-center gap-3 w-full max-w-xl">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0 border-2 border-purple-500">
                  <span class="text-sm font-bold text-purple-700">8</span>
                </div>
                <div class="flex-1 bg-purple-50 rounded-lg p-3 border border-purple-200">
                  <p class="text-xs font-bold text-purple-800">Pelaporan</p>
                  <p class="text-[11px] text-purple-600">Rekap perjalanan, penggunaan anggaran, rekap tahunan</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 1. MASTER DATA ==================== -->
        <section id="doc-master" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
              <span class="text-sm font-bold text-green-700">1</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Master Data</h2>
              <p class="text-xs text-gray-500">Data dasar yang harus diisi sebelum membuat SPPD</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-5">
            <!-- Halaman preview: Master Data -->
            <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
              <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                <div class="flex gap-1">
                  <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                  <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                  <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                </div>
                <span class="text-[10px] text-gray-500 ml-2">Master Data → Pegawai</span>
              </div>
              <div class="p-4">
                <!-- Simulated table UI -->
                <div class="flex justify-between items-center mb-3">
                  <div class="flex items-center gap-2">
                    <div class="h-6 w-28 bg-gray-200 rounded text-[10px] flex items-center px-2 text-gray-500">🔍 Cari pegawai...</div>
                  </div>
                  <div class="relative">
                    <div class="h-6 px-3 bg-green-500 rounded text-[10px] text-white flex items-center font-medium">+ Tambah Data</div>
                    <!-- Pointer arrow -->
                    <div class="absolute -top-1 -right-1">
                      <div class="relative">
                        <svg class="w-6 h-6 text-red-500 animate-bounce" viewBox="0 0 24 24" fill="currentColor"><path d="M7.05 3.434a.5.5 0 01.849-.354L19.314 14.48a.5.5 0 01-.354.849H12.5l-2.793 5.586a.5.5 0 01-.894-.006L7.05 3.434z"/></svg>
                        <span class="absolute -top-5 right-0 bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full whitespace-nowrap font-medium">Klik untuk tambah</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                  <div class="grid grid-cols-5 bg-gray-100 text-[10px] font-semibold text-gray-600 px-3 py-2">
                    <span>NIP</span><span>Nama</span><span>Jabatan</span><span>Unit Kerja</span><span>Aksi</span>
                  </div>
                  <div class="grid grid-cols-5 px-3 py-2 text-[10px] text-gray-700 border-t border-gray-100">
                    <span>19800101</span><span>Ahmad Surya</span><span>Kepala Dinas</span><span>Dinas A</span>
                    <span class="flex gap-1">
                      <span class="px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded text-[9px]">Edit</span>
                      <span class="px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[9px]">Hapus</span>
                    </span>
                  </div>
                  <div class="grid grid-cols-5 px-3 py-2 text-[10px] text-gray-500 bg-gray-50 border-t border-gray-100">
                    <span>19900202</span><span>Budi Santoso</span><span>Kabid</span><span>Dinas B</span>
                    <span class="flex gap-1">
                      <span class="px-1.5 py-0.5 bg-blue-100 text-blue-600 rounded text-[9px]">Edit</span>
                      <span class="px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[9px]">Hapus</span>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sub-module list -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
              <div v-for="item in [
                { title: 'Pegawai', desc: 'Data pegawai yang melakukan perjalanan dinas', menu: 'Master Data → Pegawai' },
                { title: 'Unit Kerja', desc: 'Struktur organisasi / unit kerja instansi', menu: 'Master Data → Unit Kerja' },
                { title: 'Jabatan', desc: 'Daftar jabatan struktural & fungsional', menu: 'Master Data → Jabatan' },
                { title: 'Golongan', desc: 'Golongan / ruang kepangkatan PNS', menu: 'Master Data → Golongan' },
                { title: 'Kota Tujuan', desc: 'Daftar kota tujuan perjalanan dinas', menu: 'Master Data → Kota Tujuan' },
                { title: 'Standar Biaya', desc: 'Tarif uang harian, transport, penginapan per golongan/kota', menu: 'Master Data → Standar Biaya' },
              ]" :key="item.title" class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                <p class="text-xs font-semibold text-gray-800">{{ item.title }}</p>
                <p class="text-[11px] text-gray-500 mt-0.5">{{ item.desc }}</p>
                <p class="text-[10px] text-green-600 mt-1.5 font-medium">📍 {{ item.menu }}</p>
              </div>
            </div>

            <!-- Tips -->
            <div class="flex gap-3 bg-amber-50 rounded-lg p-3 border border-amber-200">
              <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.072 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
              <div>
                <p class="text-xs font-semibold text-amber-800">Penting!</p>
                <p class="text-[11px] text-amber-700">Isi <strong>Standar Biaya</strong> terlebih dahulu. Data ini digunakan untuk menghitung biaya perjalanan secara otomatis pada SPPD.</p>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 2. PERENCANAAN ==================== -->
        <section id="doc-perencanaan" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
              <span class="text-sm font-bold text-blue-700">2</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Perencanaan</h2>
              <p class="text-xs text-gray-500">Membuat program kegiatan dan rencana perjalanan dinas</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-5">
            <!-- Step-by-step -->
            <div class="space-y-4">
              <!-- Step A -->
              <div class="flex gap-4 items-start">
                <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold shrink-0">A</div>
                <div class="flex-1">
                  <p class="text-sm font-semibold text-gray-800">Buat Program Kegiatan</p>
                  <p class="text-xs text-gray-500 mt-1">Buka menu <strong>Perencanaan → Program Kegiatan</strong>, klik <strong>Tambah</strong>. Isi nama program, kode kegiatan, anggaran, dan tahun anggaran.</p>
                  <!-- Mini UI preview -->
                  <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <div class="flex items-center gap-2 mb-2">
                      <div class="text-[10px] font-medium text-gray-400">Form Program Kegiatan</div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                      <div>
                        <div class="text-[9px] text-gray-500 mb-0.5">Nama Program *</div>
                        <div class="h-5 bg-white rounded border border-gray-200 px-1.5 text-[9px] text-gray-600 flex items-center">Program Koordinasi Daerah</div>
                      </div>
                      <div>
                        <div class="text-[9px] text-gray-500 mb-0.5">Kode Kegiatan</div>
                        <div class="h-5 bg-white rounded border border-gray-200 px-1.5 text-[9px] text-gray-600 flex items-center">5.1.02.01</div>
                      </div>
                      <div>
                        <div class="text-[9px] text-gray-500 mb-0.5">Pagu Anggaran</div>
                        <div class="h-5 bg-white rounded border border-gray-200 px-1.5 text-[9px] text-gray-600 flex items-center">Rp 150.000.000</div>
                      </div>
                      <div>
                        <div class="text-[9px] text-gray-500 mb-0.5">Tahun Anggaran</div>
                        <div class="h-5 bg-white rounded border border-gray-200 px-1.5 text-[9px] text-gray-600 flex items-center">2026</div>
                      </div>
                    </div>
                    <div class="mt-2 flex justify-end">
                      <div class="h-5 px-3 bg-green-500 rounded text-[9px] text-white flex items-center font-medium">Simpan</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Step B -->
              <div class="flex gap-4 items-start">
                <div class="w-7 h-7 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs font-bold shrink-0">B</div>
                <div class="flex-1">
                  <p class="text-sm font-semibold text-gray-800">Buat Rencana Perjalanan</p>
                  <p class="text-xs text-gray-500 mt-1">Buka <strong>Perencanaan → Rencana Perjalanan</strong>, klik <strong>Tambah</strong>. Pilih program kegiatan, pegawai, kota tujuan, tanggal berangkat & kembali, dan maksud perjalanan.</p>
                  <div class="mt-2 flex gap-2 bg-green-50 rounded-lg p-2 border border-green-200">
                    <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                    <p class="text-[11px] text-green-700">Status rencana: <strong>Draft → Disetujui → Selesai</strong>. Rencana harus disetujui pimpinan sebelum bisa dibuat Surat Tugas.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 3. DOKUMEN ==================== -->
        <section id="doc-dokumen" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
              <span class="text-sm font-bold text-indigo-700">3</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Dokumen</h2>
              <p class="text-xs text-gray-500">Surat Tugas & SPPD — dokumen inti perjalanan dinas</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-5">
            <!-- Surat Tugas -->
            <div class="space-y-3">
              <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-indigo-100 flex items-center justify-center">
                  <svg class="w-3 h-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-800">Surat Tugas</p>
              </div>
              <p class="text-xs text-gray-600 ml-7">Buka <strong>Dokumen → Surat Tugas → Tambah</strong>. Pilih rencana perjalanan, pegawai yang ditugaskan, dan isi dasar penugasan. Nomor surat digenerate otomatis.</p>

              <!-- Preview Surat Tugas -->
              <div class="ml-7 rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                  <div class="flex gap-1">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                    <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                  </div>
                  <span class="text-[10px] text-gray-500 ml-2">Detail Surat Tugas</span>
                </div>
                <div class="p-4 flex items-start gap-3">
                  <div class="flex-1">
                    <div class="text-[10px] space-y-1.5">
                      <div class="flex"><span class="w-20 text-gray-500">Nomor</span><span class="text-gray-800 font-medium">ST/001/II/2026</span></div>
                      <div class="flex"><span class="w-20 text-gray-500">Pegawai</span><span class="text-gray-800">Ahmad Surya, S.Kom</span></div>
                      <div class="flex"><span class="w-20 text-gray-500">Tujuan</span><span class="text-gray-800">Jakarta</span></div>
                      <div class="flex"><span class="w-20 text-gray-500">Tanggal</span><span class="text-gray-800">01 - 03 Mar 2026</span></div>
                    </div>
                  </div>
                  <div class="relative">
                    <div class="h-6 px-3 bg-indigo-500 rounded text-[10px] text-white flex items-center font-medium gap-1">
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
                      Cetak
                    </div>
                    <div class="absolute -top-1 -right-1">
                      <svg class="w-5 h-5 text-red-500 animate-bounce" viewBox="0 0 24 24" fill="currentColor"><path d="M7.05 3.434a.5.5 0 01.849-.354L19.314 14.48a.5.5 0 01-.354.849H12.5l-2.793 5.586a.5.5 0 01-.894-.006L7.05 3.434z"/></svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SPPD -->
            <div class="space-y-3 mt-4">
              <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-violet-100 flex items-center justify-center">
                  <svg class="w-3 h-3 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>
                <p class="text-sm font-semibold text-gray-800">SPPD</p>
              </div>
              <p class="text-xs text-gray-600 ml-7">Buka <strong>Dokumen → SPPD → Tambah</strong>. Pilih Surat Tugas yang sudah dibuat. Sistem otomatis mengisi data perjalanan dan menghitung biaya berdasarkan <strong>Standar Biaya</strong>.</p>

              <div class="ml-7 grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-violet-50 rounded-lg p-3 border border-violet-200">
                  <p class="text-xs font-semibold text-violet-800">Perhitungan Biaya</p>
                  <div class="mt-2 space-y-1 text-[10px]">
                    <div class="flex justify-between"><span class="text-violet-600">Uang Harian (3 hari)</span><span class="text-violet-800 font-medium">Rp 1.200.000</span></div>
                    <div class="flex justify-between"><span class="text-violet-600">Transport</span><span class="text-violet-800 font-medium">Rp 500.000</span></div>
                    <div class="flex justify-between"><span class="text-violet-600">Penginapan (2 malam)</span><span class="text-violet-800 font-medium">Rp 700.000</span></div>
                    <div class="flex justify-between border-t border-violet-200 pt-1 mt-1"><span class="font-bold text-violet-700">Total</span><span class="font-bold text-violet-900">Rp 2.400.000</span></div>
                  </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                  <p class="text-xs font-semibold text-gray-700">Bukti Perjalanan</p>
                  <p class="text-[10px] text-gray-500 mt-1">Upload bukti perjalanan (boarding pass, tiket, kuitansi) di tab <strong>Bukti</strong> pada halaman detail SPPD.</p>
                  <div class="mt-2 flex gap-1.5">
                    <div class="h-8 w-10 rounded bg-gray-200 flex items-center justify-center text-[8px] text-gray-400">📄 .pdf</div>
                    <div class="h-8 w-10 rounded bg-gray-200 flex items-center justify-center text-[8px] text-gray-400">🖼 .jpg</div>
                    <div class="h-8 w-10 rounded border-2 border-dashed border-gray-300 flex items-center justify-center text-[8px] text-gray-400">+</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 4. KEUANGAN ==================== -->
        <section id="doc-keuangan" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
              <span class="text-sm font-bold text-amber-700">4</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Keuangan</h2>
              <p class="text-xs text-gray-500">Pengajuan dan pencairan dana perjalanan dinas</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-4">
            <div class="flex gap-4 items-start">
              <div class="w-7 h-7 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0">$</div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">Pengajuan Dana / Uang Muka</p>
                <p class="text-xs text-gray-500 mt-1">Buka <strong>Keuangan → Pengajuan Dana → Tambah</strong>. Pilih SPPD yang sudah disetujui, sistem akan menampilkan rincian biaya. Setelah diajukan, pimpinan harus menyetujui pencairan.</p>
              </div>
            </div>

            <!-- Alur status -->
            <div class="flex items-center gap-1 ml-11 flex-wrap">
              <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-medium border border-gray-200">Draft</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-[10px] font-medium border border-amber-200">Diajukan</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-medium border border-green-200">Disetujui ✓</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-[10px] font-medium border border-blue-200">Dicairkan</span>
            </div>
          </div>
        </section>

        <!-- ==================== 5. REALISASI ==================== -->
        <section id="doc-realisasi" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center">
              <span class="text-sm font-bold text-teal-700">5</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Realisasi</h2>
              <p class="text-xs text-gray-500">Laporan perjalanan dan SPJ setelah perjalanan selesai</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-5">
            <!-- Laporan Perjalanan -->
            <div class="flex gap-4 items-start">
              <div class="w-7 h-7 rounded-full bg-teal-500 text-white flex items-center justify-center text-xs font-bold shrink-0">A</div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">Laporan Perjalanan</p>
                <p class="text-xs text-gray-500 mt-1">Setelah perjalanan selesai, buka <strong>Realisasi → Laporan Perjalanan → Tambah</strong>. Pilih SPPD terkait, isi ringkasan hasil perjalanan, temuan, dan tindak lanjut.</p>
              </div>
            </div>

            <!-- SPJ -->
            <div class="flex gap-4 items-start">
              <div class="w-7 h-7 rounded-full bg-teal-500 text-white flex items-center justify-center text-xs font-bold shrink-0">B</div>
              <div class="flex-1">
                <p class="text-sm font-semibold text-gray-800">SPJ (Surat Pertanggungjawaban)</p>
                <p class="text-xs text-gray-500 mt-1">Buka <strong>Realisasi → SPJ → Tambah</strong>. Pilih SPPD, isi realisasi biaya aktual. Bandingkan dengan uang muka yang diterima. SPJ bisa dicetak untuk dokumentasi.</p>
                <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                  <div class="text-[10px] space-y-1.5 max-w-xs">
                    <div class="flex justify-between"><span class="text-gray-500">Uang Muka Diterima</span><span class="text-gray-800">Rp 2.400.000</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Realisasi Biaya</span><span class="text-gray-800">Rp 2.150.000</span></div>
                    <div class="flex justify-between border-t border-gray-200 pt-1"><span class="font-semibold text-green-700">Sisa Pengembalian</span><span class="font-semibold text-green-700">Rp 250.000</span></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200 flex gap-2 ml-11">
              <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
              <p class="text-[11px] text-blue-700">SPJ yang sudah selesai dapat dicetak melalui tombol <strong>Cetak</strong> pada halaman detail.</p>
            </div>
          </div>
        </section>

        <!-- ==================== 6. APPROVAL ==================== -->
        <section id="doc-approval" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
              <span class="text-sm font-bold text-orange-700">6</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Approval (Persetujuan)</h2>
              <p class="text-xs text-gray-500">Halaman khusus pimpinan untuk menyetujui/menolak</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-4">
            <p class="text-xs text-gray-600">Buka <strong>Approval → Persetujuan</strong>. Halaman ini menampilkan daftar perjalanan dinas yang menunggu persetujuan pimpinan.</p>

            <!-- Approval UI Preview -->
            <div class="rounded-xl border border-gray-200 bg-gray-50 overflow-hidden">
              <div class="bg-gray-100 px-4 py-2 flex items-center gap-2 border-b border-gray-200">
                <div class="flex gap-1">
                  <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                  <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                  <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                </div>
                <span class="text-[10px] text-gray-500 ml-2">Persetujuan</span>
              </div>
              <div class="p-4">
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 flex items-center justify-between">
                  <div class="text-[10px]">
                    <p class="font-semibold text-gray-800">Perjalanan Dinas ke Jakarta</p>
                    <p class="text-gray-500 mt-0.5">Ahmad Surya — 01-03 Mar 2026</p>
                  </div>
                  <div class="flex gap-1.5 relative">
                    <div class="h-6 px-3 bg-green-500 rounded text-[10px] text-white flex items-center font-medium gap-1">
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      Setujui
                    </div>
                    <div class="h-6 px-3 bg-red-500 rounded text-[10px] text-white flex items-center font-medium gap-1">
                      <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                      Tolak
                    </div>
                    <div class="absolute -top-7 left-0">
                      <span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full whitespace-nowrap font-medium">Klik untuk aksi</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 7. LAPORAN ==================== -->
        <section id="doc-laporan" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
              <span class="text-sm font-bold text-purple-700">7</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Laporan</h2>
              <p class="text-xs text-gray-500">Rekap data perjalanan dinas dan penggunaan anggaran</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 text-center">
                <svg class="w-8 h-8 text-purple-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <p class="text-xs font-bold text-purple-800">Perjalanan Pegawai</p>
                <p class="text-[10px] text-purple-500 mt-1">Rekap perjalanan dinas per pegawai, filter periode</p>
                <p class="text-[9px] text-purple-400 mt-1">📍 Laporan → Perjalanan Pegawai</p>
              </div>
              <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 text-center">
                <svg class="w-8 h-8 text-purple-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                <p class="text-xs font-bold text-purple-800">Penggunaan Anggaran</p>
                <p class="text-[10px] text-purple-500 mt-1">Monitoring realisasi vs pagu anggaran per program</p>
                <p class="text-[9px] text-purple-400 mt-1">📍 Laporan → Penggunaan Anggaran</p>
              </div>
              <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 text-center">
                <svg class="w-8 h-8 text-purple-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p class="text-xs font-bold text-purple-800">Rekap Tahunan</p>
                <p class="text-[10px] text-purple-500 mt-1">Ringkasan perjalanan dinas selama satu tahun</p>
                <p class="text-[9px] text-purple-400 mt-1">📍 Laporan → Rekap Tahunan</p>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== 8. PENGATURAN ==================== -->
        <section id="doc-pengaturan" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
              <span class="text-sm font-bold text-gray-700">8</span>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Pengaturan & Sistem</h2>
              <p class="text-xs text-gray-500">Konfigurasi instansi, user, role, dan pemeliharaan</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <p class="text-xs font-semibold text-gray-800">📋 Data Instansi</p>
                <p class="text-[11px] text-gray-500 mt-1">Nama, alamat, logo, pejabat penandatangan, posisi KOP surat. Perubahan langsung terlihat pada dokumen cetak.</p>
                <p class="text-[10px] text-green-600 mt-1 font-medium">📍 Pengaturan → Data Instansi</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <p class="text-xs font-semibold text-gray-800">👤 Manajemen User</p>
                <p class="text-[11px] text-gray-500 mt-1">Tambah, edit, nonaktifkan user. Setiap user memiliki role yang menentukan hak aksesnya.</p>
                <p class="text-[10px] text-green-600 mt-1 font-medium">📍 Sistem → Manajemen User</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <p class="text-xs font-semibold text-gray-800">🔑 Role & Hak Akses</p>
                <p class="text-[11px] text-gray-500 mt-1">Buat role custom (misal: Kasubag, Bendahara) dan atur permission granular per menu/aksi.</p>
                <p class="text-[10px] text-green-600 mt-1 font-medium">📍 Sistem → Role & Hak Akses</p>
              </div>
              <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                <p class="text-xs font-semibold text-gray-800">🗄️ Audit Log & Backup</p>
                <p class="text-[11px] text-gray-500 mt-1">Audit log mencatat semua aktivitas user. Backup database dilakukan secara manual atau terjadwal.</p>
                <p class="text-[10px] text-green-600 mt-1 font-medium">📍 Sistem → Audit Log / Backup</p>
              </div>
            </div>
          </div>
        </section>

        <!-- ==================== TIPS & FAQ ==================== -->
        <section id="doc-tips" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center">
              <svg class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <h2 class="text-base font-bold text-gray-900">Tips & FAQ</h2>
              <p class="text-xs text-gray-500">Pertanyaan umum dan tips penggunaan</p>
            </div>
          </div>
          <div class="px-6 py-5 space-y-3">
            <div v-for="(faq, i) in faqList" :key="i" class="rounded-lg border border-gray-100 overflow-hidden">
              <div class="px-4 py-3 bg-gray-50 flex items-start gap-2">
                <span class="text-xs font-bold text-green-600 shrink-0">Q:</span>
                <p class="text-xs font-medium text-gray-800">{{ faq.q }}</p>
              </div>
              <div class="px-4 py-3 flex items-start gap-2">
                <span class="text-xs font-bold text-blue-600 shrink-0">A:</span>
                <p class="text-xs text-gray-600">{{ faq.a }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-400 pt-4">
          Dokumentasi e-SPPD v1.0 — Terakhir diperbarui Februari 2026
        </div>

      </div>
    </div>
  </AppLayout>
</template>
