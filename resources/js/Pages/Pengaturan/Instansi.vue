<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import KopSurat from '@/Components/Print/KopSurat.vue'
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({ instansi: Object })

const form = useForm({
  nama:            props.instansi.nama            ?? '',
  singkatan:       props.instansi.singkatan       ?? '',
  alamat:          props.instansi.alamat          ?? '',
  kota:            props.instansi.kota            ?? '',
  telepon:         props.instansi.telepon         ?? '',
  fax:             props.instansi.fax             ?? '',
  website:         props.instansi.website         ?? '',
  email:           props.instansi.email           ?? '',
  kode_pos:        props.instansi.kode_pos        ?? '',
  pejabat_nama:    props.instansi.pejabat_nama    ?? '',
  pejabat_jabatan: props.instansi.pejabat_jabatan ?? '',
  pejabat_nip:     props.instansi.pejabat_nip     ?? '',
  pejabat_pangkat: props.instansi.pejabat_pangkat ?? '',
  logo_posisi:     props.instansi.logo_posisi     ?? 'kiri',
  logo1:           null,
  logo2:           null,
  hapus_logo1:     false,
  hapus_logo2:     false,
})

// Logo previews
const preview1 = ref(props.instansi.logo1_url ?? null)
const preview2 = ref(props.instansi.logo2_url ?? null)

function pickLogo(slot) {
  const input = document.createElement('input')
  input.type = 'file'
  input.accept = 'image/png,image/jpeg,image/jpg,image/svg+xml'
  input.onchange = (e) => {
    const file = e.target.files[0]
    if (!file) return
    const preview = slot === 1 ? preview1 : preview2
    // Revoke old ObjectURL to prevent memory leak
    if (preview.value?.startsWith('blob:')) URL.revokeObjectURL(preview.value)
    if (slot === 1) {
      form.logo1 = file
      form.hapus_logo1 = false
    } else {
      form.logo2 = file
      form.hapus_logo2 = false
    }
    preview.value = URL.createObjectURL(file)
  }
  input.click()
}

function removeLogo(slot) {
  if (slot === 1) {
    form.logo1 = null
    form.hapus_logo1 = true
    preview1.value = null
  } else {
    form.logo2 = null
    form.hapus_logo2 = true
    preview2.value = null
  }
}

// Live preview instansi object for KopSurat (only fields used by KopSurat)
const previewInstansi = computed(() => ({
  nama:        form.nama,
  singkatan:   form.singkatan,
  alamat:      form.alamat,
  kota:        form.kota,
  telepon:     form.telepon,
  fax:         form.fax,
  website:     form.website,
  email:       form.email,
  kode_pos:    form.kode_pos,
  logo_posisi: form.logo_posisi,
  logo1_url:   preview1.value,
  logo2_url:   preview2.value,
}))

const posisiOptions = [
  { value: 'kiri',  label: 'Logo di Kiri' },
  { value: 'kanan', label: 'Logo di Kanan' },
  { value: 'atas',  label: 'Logo di Atas' },
]

// Logo slots config for v-for deduplication
const logoSlots = [
  { slot: 1, label: 'Logo 1', hint: '(PNG/JPG/SVG, maks. 2 MB)', preview: preview1, errorKey: 'logo1' },
  { slot: 2, label: 'Logo 2', hint: '(opsional)',                preview: preview2, errorKey: 'logo2' },
]

function submit() {
  form.post(route('pengaturan.instansi.update'), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      // Reset logo fields after save
      form.logo1 = null
      form.logo2 = null
      form.hapus_logo1 = false
      form.hapus_logo2 = false
    },
  })
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Data Instansi" :breadcrumbs="[{ label: 'Pengaturan' }, { label: 'Data Instansi' }]">
      <AppButton type="button" variant="secondary" @click="submit" :disabled="form.processing">
        {{ form.processing ? 'Menyimpan...' : 'Simpan Data Instansi' }}
      </AppButton>
    </PageHeader>

    <form @submit.prevent="submit" enctype="multipart/form-data">
      <div class="space-y-5">

        <!-- Identitas -->
        <AppCard title="Identitas Instansi">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="sm:col-span-2 lg:col-span-3">
              <AppInput
                v-model="form.nama"
                label="Nama Instansi"
                placeholder="DINAS ..."
                required
                :error="form.errors.nama"
              />
            </div>
            <AppInput
              v-model="form.singkatan"
              label="Singkatan"
              placeholder="Contoh: DISKOMINFO"
              :error="form.errors.singkatan"
            />
            <AppInput
              v-model="form.kota"
              label="Kota / Kabupaten"
              :error="form.errors.kota"
            />
            <AppInput v-model="form.kode_pos" label="Kode Pos" :error="form.errors.kode_pos" />
            <div class="sm:col-span-2 lg:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
              <textarea
                v-model="form.alamat"
                rows="2"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Jl. ..."
              />
            </div>
            <AppInput v-model="form.telepon" label="Telepon" :error="form.errors.telepon" />
            <AppInput v-model="form.fax"     label="Fax"     :error="form.errors.fax" />
            <AppInput v-model="form.website"  label="Website" :error="form.errors.website" />
            <AppInput v-model="form.email"    label="Email"   type="email" :error="form.errors.email" />
          </div>
        </AppCard>

        <!-- Pejabat TTD -->
        <AppCard title="Pejabat Penandatangan">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2 lg:col-span-4">
              <AppInput
                v-model="form.pejabat_nama"
                label="Nama Pejabat"
                placeholder="Drs. NAMA, M.Si."
                :error="form.errors.pejabat_nama"
              />
            </div>
            <div class="lg:col-span-2">
              <AppInput v-model="form.pejabat_jabatan" label="Jabatan" :error="form.errors.pejabat_jabatan" />
            </div>
            <AppInput v-model="form.pejabat_nip"     label="NIP"     :error="form.errors.pejabat_nip" />
            <AppInput v-model="form.pejabat_pangkat" label="Pangkat / Golongan" :error="form.errors.pejabat_pangkat" />
          </div>
        </AppCard>

        <!-- Logo -->
        <AppCard title="Logo Instansi">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kiri: posisi + logo slots -->
            <div class="space-y-5">
              <div>
                <p class="text-sm font-medium text-gray-700 mb-3">Posisi Logo</p>
                <div class="grid grid-cols-3 gap-3">
                  <div
                    v-for="opt in posisiOptions"
                    :key="opt.value"
                    @click="form.logo_posisi = opt.value"
                    class="cursor-pointer rounded-lg border-2 p-3 flex flex-col items-center gap-1 transition-all"
                    :class="form.logo_posisi === opt.value
                      ? 'border-green-500 bg-green-50'
                      : 'border-gray-200 hover:border-gray-300'"
                  >
                    <div class="w-full h-10 rounded bg-gray-100 flex items-center gap-1 px-2 overflow-hidden" v-if="opt.value === 'kiri'">
                      <div class="w-7 h-7 rounded bg-gray-300 shrink-0"></div>
                      <div class="flex-1 space-y-1">
                        <div class="h-1.5 bg-gray-400 rounded w-full"></div>
                        <div class="h-1 bg-gray-300 rounded w-4/5"></div>
                        <div class="h-1 bg-gray-300 rounded w-3/5"></div>
                      </div>
                    </div>
                    <div class="w-full h-10 rounded bg-gray-100 flex items-center gap-1 px-2 overflow-hidden" v-else-if="opt.value === 'kanan'">
                      <div class="flex-1 space-y-1">
                        <div class="h-1.5 bg-gray-400 rounded w-full"></div>
                        <div class="h-1 bg-gray-300 rounded w-4/5"></div>
                        <div class="h-1 bg-gray-300 rounded w-3/5"></div>
                      </div>
                      <div class="w-7 h-7 rounded bg-gray-300 shrink-0"></div>
                    </div>
                    <div class="w-full h-10 rounded bg-gray-100 flex flex-col items-center justify-center gap-0.5 px-2 overflow-hidden" v-else>
                      <div class="w-6 h-6 rounded-full bg-gray-300"></div>
                      <div class="h-1 bg-gray-400 rounded w-3/5"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">{{ opt.label }}</span>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div v-for="ls in logoSlots" :key="ls.slot">
                  <p class="text-sm font-medium text-gray-700 mb-2">{{ ls.label }} <span class="text-gray-400 font-normal text-xs">{{ ls.hint }}</span></p>
                  <div
                    class="rounded-xl border-2 border-dashed flex flex-col items-center justify-center gap-2 p-3 h-28 transition-colors"
                    :class="ls.preview.value ? 'border-green-300 bg-green-50' : 'border-gray-300 bg-gray-50'"
                  >
                    <img v-if="ls.preview.value" :src="ls.preview.value" class="max-h-16 max-w-full object-contain" :alt="ls.label" />
                    <div v-else class="text-center">
                      <svg class="w-8 h-8 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      <p class="text-xs text-gray-400 mt-1">Belum ada logo</p>
                    </div>
                  </div>
                  <div class="flex gap-1.5 mt-2">
                    <button type="button" @click="pickLogo(ls.slot)"
                      class="flex-1 text-xs py-1.5 px-2 rounded-lg border border-green-500 text-green-700 hover:bg-green-50 font-medium transition-colors">
                      {{ ls.preview.value ? 'Ganti' : 'Pilih Logo' }}
                    </button>
                    <button v-if="ls.preview.value" type="button" @click="removeLogo(ls.slot)"
                      class="text-xs py-1.5 px-2 rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition-colors">
                      Hapus
                    </button>
                  </div>
                  <p v-if="form.errors[ls.errorKey]" class="text-xs text-red-500 mt-1">{{ form.errors[ls.errorKey] }}</p>
                </div>
              </div>
            </div>

            <!-- Kanan: Live preview KOP -->
            <div>
              <p class="text-sm font-medium text-gray-700 mb-3">Preview KOP Surat</p>
              <div class="rounded-xl border border-gray-200 bg-white" style="height: 130px; position: relative; overflow: clip;">
                <div style="position: absolute; top: 12px; left: 12px; width: 154%; transform: scale(0.65); transform-origin: top left; pointer-events: none;">
                  <KopSurat :instansi="previewInstansi" />
                </div>
              </div>
            </div>
          </div>
        </AppCard>

        <!-- Simpan -->
        <div class="flex justify-end">
          <AppButton type="submit" :disabled="form.processing" class="px-8">
            {{ form.processing ? 'Menyimpan...' : 'Simpan Data Instansi' }}
          </AppButton>
        </div>

      </div>
    </form>
  </AppLayout>
</template>
