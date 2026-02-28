<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  sidebarCollapsed: Boolean,
})
const emit = defineEmits(['toggle-sidebar', 'toggle-collapse'])

const page = usePage()
const user = computed(() => page.props.auth?.user)
const appName = computed(() => page.props.appName ?? 'e-SPPD')

// User dropdown
const showDropdown = ref(false)
const dropdownRef = ref(null)

function toggleDropdown() {
  showDropdown.value = !showDropdown.value
}

function closeDropdown(e) {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    showDropdown.value = false
  }
}

onMounted(() => document.addEventListener('click', closeDropdown))
onUnmounted(() => document.removeEventListener('click', closeDropdown))

function logout() {
  router.post(route('logout'))
}
</script>

<template>
  <header class="flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 shadow-sm">
    <!-- Mobile menu button -->
    <button
      class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100"
      @click="emit('toggle-sidebar')"
    >
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Desktop collapse button -->
    <button
      class="hidden lg:flex p-2 rounded-lg text-gray-500 hover:bg-gray-100"
      @click="emit('toggle-collapse')"
    >
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Page title from slot -->
    <div class="flex-1">
      <slot />
    </div>

    <!-- Right side -->
    <div class="flex items-center gap-3">
      <!-- App name badge -->
      <span class="hidden sm:inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
        {{ appName }}
      </span>

      <!-- User menu -->
      <div ref="dropdownRef" class="relative">
        <button
          @click="toggleDropdown"
          class="flex items-center gap-2 rounded-lg px-2 py-1.5 hover:bg-gray-100 transition-colors"
        >
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white text-sm font-semibold">
            {{ user?.name?.charAt(0)?.toUpperCase() ?? 'U' }}
          </div>
          <span class="hidden md:block text-sm font-medium text-gray-700">{{ user?.name }}</span>
          <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>

        <!-- Dropdown -->
        <Transition
          enter-active-class="transition ease-out duration-100"
          enter-from-class="transform opacity-0 scale-95"
          enter-to-class="transform opacity-100 scale-100"
          leave-active-class="transition ease-in duration-75"
          leave-from-class="transform opacity-100 scale-100"
          leave-to-class="transform opacity-0 scale-95"
        >
          <div v-if="showDropdown" class="absolute right-0 mt-2 w-56 rounded-xl bg-white border border-gray-200 shadow-lg py-1 z-50">
            <!-- User info -->
            <div class="px-4 py-3 border-b border-gray-100">
              <p class="text-sm font-medium text-gray-900 truncate">{{ user?.name }}</p>
              <p class="text-xs text-gray-500 truncate">{{ user?.email }}</p>
              <span v-if="user?.role" class="inline-flex items-center mt-1.5 rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-medium text-green-700">
                {{ user.role.name }}
              </span>
            </div>

            <!-- Menu items -->
            <div class="py-1">
              <Link
                :href="route('profile.edit')"
                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                @click="showDropdown = false"
              >
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
              </Link>
            </div>

            <!-- Logout -->
            <div class="border-t border-gray-100 py-1">
              <button
                @click="logout"
                class="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </header>
</template>
