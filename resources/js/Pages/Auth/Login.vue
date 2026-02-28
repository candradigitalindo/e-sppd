<script setup>
import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-linear-to-br from-green-900 via-green-800 to-emerald-700 p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">e-SPPD</h1>
        <p class="mt-1 text-green-200 text-sm">Sistem Surat Perintah Perjalanan Dinas</p>
      </div>

      <!-- Login form -->
      <div class="rounded-2xl bg-white shadow-2xl p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Masuk ke Sistem</h2>

        <form @submit.prevent="submit" class="space-y-5">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">
              Email / Username
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="nama@pemda.go.id"
              autocomplete="email"
              required
              :class="[
                'block w-full rounded-lg border px-3 py-2.5 text-sm transition',
                'focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500',
                form.errors.email ? 'border-red-400' : 'border-gray-300',
              ]"
            />
            <p v-if="form.errors.email" class="mt-1 text-xs text-red-600">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <input
              v-model="form.password"
              type="password"
              autocomplete="current-password"
              required
              :class="[
                'block w-full rounded-lg border px-3 py-2.5 text-sm transition',
                'focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500',
                form.errors.password ? 'border-red-400' : 'border-gray-300',
              ]"
            />
            <p v-if="form.errors.password" class="mt-1 text-xs text-red-600">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Remember me -->
          <label class="flex items-center gap-2 text-sm text-gray-600">
            <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
            Ingat saya
          </label>

          <!-- Submit -->
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
          >
            <span v-if="form.processing" class="flex items-center justify-center gap-2">
              <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
              </svg>
              Memproses...
            </span>
            <span v-else>Masuk</span>
          </button>
        </form>
      </div>

      <p class="mt-6 text-center text-xs text-green-300">
        &copy; {{ new Date().getFullYear() }} e-SPPD · Sistem Perjalanan Dinas
      </p>
    </div>
  </div>
</template>
