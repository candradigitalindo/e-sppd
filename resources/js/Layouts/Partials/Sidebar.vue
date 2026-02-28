<script setup>
import { ref, nextTick, onMounted, onUnmounted, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { usePermission } from '@/composables/usePermission'

defineProps({
  open: Boolean,
  collapsed: Boolean,
})
defineEmits(['close'])

const { can, isAdmin } = usePermission()

const menuGroups = [
  {
    label: 'Utama',
    items: [
      {
        label: 'Dashboard',
        route: 'dashboard',
        icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>`,
      },
    ],
  },
  {
    label: 'Master Data',
    permission: 'view_pegawai,view_unit_kerja,view_jabatan,view_golongan,view_kota_tujuan,view_standar_biaya',
    items: [
      { label: 'Pegawai', route: 'master-data.pegawai.index', permission: 'view_pegawai', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>` },
      { label: 'Unit Kerja', route: 'master-data.unit-kerja.index', permission: 'view_unit_kerja', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>` },
      { label: 'Jabatan', route: 'master-data.jabatan.index', permission: 'view_jabatan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>` },
      { label: 'Golongan', route: 'master-data.golongan.index', permission: 'view_golongan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>` },
      { label: 'Kota Tujuan', route: 'master-data.kota-tujuan.index', permission: 'view_kota_tujuan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>` },
      { label: 'Standar Biaya', route: 'master-data.standar-biaya.index', permission: 'view_standar_biaya', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` },
    ],
  },
  {
    label: 'Perencanaan',
    permission: 'view_program_kegiatan,view_rencana_perjalanan',
    items: [
      { label: 'Program Kegiatan', route: 'perencanaan.program-kegiatan.index', permission: 'view_program_kegiatan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>` },
      { label: 'Rencana Perjalanan', route: 'perencanaan.rencana-perjalanan.index', permission: 'view_rencana_perjalanan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>` },
    ],
  },
  {
    label: 'Dokumen',
    permission: 'view_surat_tugas,view_sppd',
    items: [
      { label: 'Surat Tugas', route: 'surat-tugas.index', permission: 'view_surat_tugas', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>` },
      { label: 'SPPD', route: 'sppd.index', permission: 'view_sppd', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>` },
    ],
  },
  {
    label: 'Keuangan',
    permission: 'view_pengajuan_dana',
    items: [
      { label: 'Pengajuan Dana', route: 'pengajuan-dana.index', permission: 'view_pengajuan_dana', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>` },
    ],
  },
  {
    label: 'Realisasi',
    permission: 'view_laporan_perjalanan,view_spj',
    items: [
      { label: 'Laporan Perjalanan', route: 'realisasi.laporan.index', permission: 'view_laporan_perjalanan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>` },
      { label: 'SPJ', route: 'spj.index', permission: 'view_spj', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>` },
    ],
  },
  {
    label: 'Approval',
    permission: 'view_approval',
    items: [
      { label: 'Persetujuan', route: 'approval.index', permission: 'view_approval', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>` },
    ],
  },
  {
    label: 'Laporan',
    permission: 'view_laporan',
    items: [
      { label: 'Perjalanan Pegawai', route: 'laporan.perjalanan-pegawai', permission: 'view_laporan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>` },
      { label: 'Penggunaan Anggaran', route: 'laporan.penggunaan-anggaran', permission: 'view_laporan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>` },
      { label: 'Rekap Tahunan', route: 'laporan.rekap-tahunan', permission: 'view_laporan', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>` },
    ],
  },
  {
    label: 'Sistem',
    permission: 'view_user,view_role,view_audit_log,view_backup',
    items: [
      { label: 'Manajemen User', route: 'sistem.user.index', permission: 'view_user', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>` },
      { label: 'Role & Hak Akses', route: 'sistem.role.index', permission: 'view_role', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>` },
      { label: 'Audit Log', route: 'sistem.audit-log.index', permission: 'view_audit_log', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>` },
      { label: 'Backup Database', route: 'sistem.backup.index', permission: 'view_backup', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>` },
    ],
  },
  {
    label: 'Pengaturan',
    permission: 'view_user,view_role',
    items: [
      { label: 'Data Instansi', route: 'pengaturan.instansi.edit', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>` },
    ],
  },
  {
    label: 'Bantuan',
    items: [
      { label: 'Dokumentasi', route: 'dokumentasi', icon: `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>` },
    ],
  },
]

/**
 * Cek apakah user punya minimal satu permission dari daftar (comma-separated).
 * Jika tidak ada permission field, item selalu tampil.
 */
function hasAnyPermission(permissionStr) {
  if (!permissionStr) return true
  if (isAdmin.value) return true
  return permissionStr.split(',').some(p => can(p.trim()))
}

/** Filter menu groups: sembunyikan group & item tanpa permission */
const filteredGroups = computed(() => {
  return menuGroups
    .map(group => ({
      ...group,
      items: group.items.filter(item => hasAnyPermission(item.permission)),
    }))
    .filter(group => {
      // Group tanpa permission field = selalu tampil (mis. Dashboard, Bantuan)
      if (!group.permission) return group.items.length > 0
      // Group dengan permission = tampil jika user punya salah satu permission
      return hasAnyPermission(group.permission) && group.items.length > 0
    })
})

function isActive(routeName) {
  try {
    return route().current(routeName) || route().current(routeName + '.*')
  } catch {
    return false
  }
}

const navRef = ref(null)

function scrollActiveIntoView() {
  nextTick(() => {
    const active = navRef.value?.querySelector('.bg-green-700')
    if (active) {
      active.scrollIntoView({ block: 'nearest', behavior: 'smooth' })
    }
  })
}

// Scroll on first load
onMounted(() => scrollActiveIntoView())

// Scroll after every Inertia navigation
let removeListener
onMounted(() => {
  removeListener = router.on('navigate', () => scrollActiveIntoView())
})
onUnmounted(() => removeListener?.())
</script>

<template>
  <!-- Desktop Sidebar -->
  <aside
    :class="[
      'fixed inset-y-0 left-0 z-30 flex flex-col bg-green-900 transition-all duration-300',
      'lg:relative lg:translate-x-0',
      open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      collapsed ? 'w-16' : 'w-64',
    ]"
  >
    <!-- Logo -->
    <div class="flex h-16 items-center justify-center border-b border-green-800 px-4">
      <span v-if="!collapsed" class="text-white font-semibold text-lg tracking-wide">e-SPPD</span>
      <span v-else class="text-white font-bold text-sm">eS</span>
    </div>

    <!-- Navigation -->
    <nav ref="navRef" class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
      <template v-for="group in filteredGroups" :key="group.label">
        <p
          v-if="!collapsed"
          class="px-3 mt-4 mb-1 text-xs font-semibold uppercase tracking-widest text-green-400"
        >
          {{ group.label }}
        </p>
        <div v-else class="border-t border-green-800 my-3" />

        <Link
          v-for="item in group.items"
          :key="item.route"
          :href="route(item.route)"
          :class="[
            'flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors',
            isActive(item.route)
              ? 'bg-green-700 text-white font-medium'
              : 'text-green-200 hover:bg-green-800 hover:text-white',
          ]"
          @click="$emit('close')"
        >
          <span class="shrink-0" v-html="item.icon" />
          <span v-if="!collapsed" class="truncate">{{ item.label }}</span>
        </Link>
      </template>
    </nav>
  </aside>
</template>
