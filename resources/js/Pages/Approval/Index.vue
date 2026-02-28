<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppModal from '@/Components/UI/AppModal.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatDate } from '@/utils/date.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ pending: Array, history: Array })

const showModal = ref(false)
const actionType = ref('')
const selectedId = ref(null)
const catatan = ref('')

function openAction(id, type) {
  selectedId.value = id
  actionType.value = type
  catatan.value = ''
  showModal.value = true
}

function confirm() {
  const routeName = actionType.value === 'approve' ? 'approval.approve' : 'approval.reject'
  router.post(route(routeName, selectedId.value), { catatan: catatan.value }, {
    onSuccess: () => (showModal.value = false),
  })
}

function levelLabel(level) {
  if (level === 1) return 'Atasan Langsung'
  if (level === 2) return 'KPA'
  return `Level ${level ?? 1}`
}

function levelColor(level) {
  return level === 2 ? 'purple' : 'blue'
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Persetujuan" description="Dokumen yang perlu persetujuan Anda" :breadcrumbs="[{ label: 'Approval' }, { label: 'Persetujuan' }]" />

    <!-- Info multi-level -->
    <div class="mb-4 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-xs text-indigo-700">
      <strong>Alur Persetujuan:</strong> Dokumen perjalanan dinas disetujui secara bertahap —
      <span class="font-medium">Level 1 (Atasan Langsung)</span> → <span class="font-medium">Level 2 (KPA)</span>
      sesuai PMK No. 113/PMK.05/2012.
    </div>

    <div class="space-y-6">
      <AppCard title="Menunggu Persetujuan">
        <div v-if="pending?.length" class="space-y-3">
          <div
            v-for="item in pending"
            :key="item.id"
            class="flex items-center justify-between rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3"
          >
            <div>
              <div class="flex items-center gap-2 mb-1">
                <p class="text-sm font-medium text-gray-800">{{ item.jenis_dokumen?.replace(/_/g, ' ') ?? '-' }}</p>
                <AppBadge :color="levelColor(item.level)" class="text-xs">
                  {{ levelLabel(item.level) }}
                </AppBadge>
              </div>
              <p class="text-xs text-gray-500">
                {{ item.catatan ?? 'Menunggu persetujuan' }} ·
                {{ formatDate(item.created_at) }}
                <template v-if="item.batas_waktu">
                  · <span :class="['font-medium', new Date(item.batas_waktu) < new Date() ? 'text-red-600' : 'text-gray-500']">
                    Batas: {{ formatDate(item.batas_waktu) }}
                  </span>
                </template>
              </p>
            </div>
            <div v-if="can('edit_approval')" class="flex gap-2">
              <AppButton size="sm" @click="openAction(item.id, 'approve')">Setujui</AppButton>
              <AppButton variant="danger" size="sm" @click="openAction(item.id, 'reject')">Tolak</AppButton>
            </div>
          </div>
        </div>
        <div v-else class="py-6 text-center text-sm text-gray-400">Tidak ada dokumen yang menunggu persetujuan.</div>
      </AppCard>

      <AppCard title="Riwayat Persetujuan">
        <div v-if="history?.length" class="space-y-2">
          <div v-for="item in history" :key="item.id" class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">{{ item.jenis_dokumen?.replace(/_/g, ' ') }}</span>
              <AppBadge :color="levelColor(item.level)" class="text-xs">{{ levelLabel(item.level) }}</AppBadge>
            </div>
            <AppBadge :color="item.status === 'disetujui' ? 'green' : 'red'">{{ item.status }}</AppBadge>
          </div>
        </div>
        <div v-else class="py-4 text-center text-sm text-gray-400">Belum ada riwayat.</div>
      </AppCard>
    </div>

    <!-- Action Modal -->
    <AppModal :show="showModal" :title="actionType === 'approve' ? 'Setujui Dokumen' : 'Tolak Dokumen'" @close="showModal = false">
      <div class="space-y-3">
        <p class="text-sm text-gray-600">
          {{ actionType === 'approve' ? 'Apakah Anda yakin menyetujui dokumen ini?' : 'Berikan alasan penolakan:' }}
        </p>
        <textarea
          v-model="catatan"
          :required="actionType === 'reject'"
          rows="3"
          placeholder="Catatan (opsional)..."
          class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
        />
      </div>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Batal</AppButton>
        <AppButton :variant="actionType === 'approve' ? 'primary' : 'danger'" @click="confirm()">
          {{ actionType === 'approve' ? 'Setujui' : 'Tolak' }}
        </AppButton>
      </template>
    </AppModal>
  </AppLayout>
</template>

