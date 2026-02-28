<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from './Partials/Sidebar.vue'
import Topbar from './Partials/Topbar.vue'

const page = usePage()
const sidebarOpen = ref(false)
const sidebarCollapsed = ref(false)

const flash = computed(() => page.props.flash ?? {})
const appName = computed(() => page.props.appName ?? 'e-SPPD')
</script>

<template>
  <div class="flex h-screen bg-gray-50 overflow-hidden font-sans">
    <!-- Mobile overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-20 bg-black/50 lg:hidden"
      @click="sidebarOpen = false"
    />

    <!-- Sidebar -->
    <Sidebar
      :open="sidebarOpen"
      :collapsed="sidebarCollapsed"
      @close="sidebarOpen = false"
    />

    <!-- Main content -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
      <!-- Topbar -->
      <Topbar
        :sidebar-collapsed="sidebarCollapsed"
        @toggle-sidebar="sidebarOpen = !sidebarOpen"
        @toggle-collapse="sidebarCollapsed = !sidebarCollapsed"
      />

      <!-- Page content -->
      <main class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6">
        <!-- Flash Messages -->
        <div class="mb-4 space-y-2">
          <div
            v-if="flash.success"
            class="flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ flash.success }}
          </div>
          <div
            v-if="flash.error"
            class="flex items-center gap-2 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800"
          >
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ flash.error }}
          </div>
        </div>

        <slot />
      </main>
    </div>
  </div>
</template>
