<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ user: Object })

// Profile form
const profileForm = useForm({
  name:  props.user.name ?? '',
  email: props.user.email ?? '',
})

function submitProfile() {
  profileForm.put(route('profile.update'), {
    preserveScroll: true,
  })
}

// Password form
const passwordForm = useForm({
  current_password:      '',
  password:              '',
  password_confirmation: '',
})

const showPasswords = ref(false)

function submitPassword() {
  passwordForm.put(route('profile.password'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset()
      showPasswords.value = false
    },
  })
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Profil Saya" description="Kelola informasi akun dan keamanan" :breadcrumbs="[{ label: 'Profil' }]" />

    <div class="max-w-3xl space-y-6">

      <!-- Info Pegawai (read-only) -->
      <div v-if="user.pegawai" class="bg-linear-to-r from-green-600 to-green-700 rounded-xl p-5 text-white">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold shrink-0">
            {{ user.name?.charAt(0)?.toUpperCase() ?? 'U' }}
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="text-lg font-bold truncate">{{ user.pegawai.nama }}</h3>
            <p class="text-green-100 text-sm">NIP: {{ user.pegawai.nip }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-1 mt-3 text-sm">
              <div>
                <span class="text-green-200 text-xs">Jabatan</span>
                <p class="text-white truncate">{{ user.pegawai.jabatan ?? '-' }}</p>
              </div>
              <div>
                <span class="text-green-200 text-xs">Golongan</span>
                <p class="text-white truncate">{{ user.pegawai.golongan ?? '-' }}</p>
              </div>
              <div>
                <span class="text-green-200 text-xs">Unit Kerja</span>
                <p class="text-white truncate">{{ user.pegawai.unit_kerja ?? '-' }}</p>
              </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-1 mt-2 text-sm">
              <div v-if="user.pegawai.no_hp">
                <span class="text-green-200 text-xs">No. HP</span>
                <p class="text-white">{{ user.pegawai.no_hp }}</p>
              </div>
              <div v-if="user.pegawai.email">
                <span class="text-green-200 text-xs">Email Pegawai</span>
                <p class="text-white truncate">{{ user.pegawai.email }}</p>
              </div>
            </div>
          </div>
        </div>
        <p class="mt-3 text-xs text-green-200 flex items-center gap-1">
          <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Data pegawai dikelola oleh Admin melalui menu Master Data → Pegawai
        </p>
      </div>

      <!-- Informasi Akun -->
      <AppCard title="Informasi Akun">
        <form @submit.prevent="submitProfile" class="space-y-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <AppInput
              v-model="profileForm.name"
              label="Nama"
              required
              :error="profileForm.errors.name"
            />
            <AppInput
              v-model="profileForm.email"
              label="Email"
              type="email"
              required
              :error="profileForm.errors.email"
            />
          </div>

          <!-- Role (read-only) -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                {{ user.role?.name ?? 'Tidak ada role' }}
              </span>
              <span class="text-xs text-gray-400">— Hanya dapat diubah oleh Admin</span>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <AppButton type="submit" :disabled="profileForm.processing">
              {{ profileForm.processing ? 'Menyimpan...' : 'Simpan Profil' }}
            </AppButton>
          </div>
        </form>
      </AppCard>

      <!-- Ubah Password -->
      <AppCard title="Ubah Password">
        <form @submit.prevent="submitPassword" class="space-y-4">
          <AppInput
            v-model="passwordForm.current_password"
            label="Password Saat Ini"
            :type="showPasswords ? 'text' : 'password'"
            required
            autocomplete="current-password"
            :error="passwordForm.errors.current_password"
          />

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <AppInput
              v-model="passwordForm.password"
              label="Password Baru"
              :type="showPasswords ? 'text' : 'password'"
              required
              autocomplete="new-password"
              :error="passwordForm.errors.password"
            />
            <AppInput
              v-model="passwordForm.password_confirmation"
              label="Konfirmasi Password Baru"
              :type="showPasswords ? 'text' : 'password'"
              required
              autocomplete="new-password"
            />
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="showPasswords = !showPasswords"
              class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-700 transition-colors"
            >
              <svg v-if="!showPasswords" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
              {{ showPasswords ? 'Sembunyikan' : 'Tampilkan' }} password
            </button>
          </div>

          <div class="flex justify-end pt-2">
            <AppButton type="submit" :disabled="passwordForm.processing">
              {{ passwordForm.processing ? 'Menyimpan...' : 'Ubah Password' }}
            </AppButton>
          </div>
        </form>
      </AppCard>

    </div>
  </AppLayout>
</template>
