<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import AppCurrencyInput from '@/Components/UI/AppCurrencyInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { formatRupiah } from '@/utils/currency.js'

const props = defineProps({ nomor: String, sppds: Array, perhitungans: Object })

const form = useForm({
  nomor: props.nomor ?? '',
  sppd_id: '',
  tanggal: new Date().toISOString().split('T')[0],
  catatan: '',
  rekap: [],
})

const selectedSppd = ref(null)

function onSelectSppd(id) {
  const sppd = props.sppds?.find(s => s.id === id)
  selectedSppd.value = sppd
  const perhitunganList = props.perhitungans?.[id] ?? []
  form.rekap = perhitunganList.map(p => ({
    jenis_biaya: p.jenis_biaya,
    rencana: p.total_biaya ?? 0,
    realisasi: p.total_biaya ?? 0,
    keterangan: '',
  }))
}

function submit() { form.post(route('spj.store')) }

const totalRealisasi = () => form.rekap.reduce((s, r) => s + Number(r.realisasi ?? 0), 0)
</script>

<template>
  <AppLayout>
    <PageHeader title="Buat SPJ" :breadcrumbs="[{ label: 'Realisasi' }, { label: 'SPJ', href: route('spj.index') }, { label: 'Buat' }]" />
    <form @submit.prevent="submit">
      <div class="space-y-5 max-w-4xl">
        <AppCard title="Informasi SPJ">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <AppInput v-model="form.nomor" label="Nomor SPJ" required :error="form.errors.nomor" />
            <AppInput v-model="form.tanggal" label="Tanggal" type="date" required :error="form.errors.tanggal" />
            <div class="sm:col-span-2">
              <AppSelect v-model="form.sppd_id" label="SPPD" required :options="sppds" option-label="nomor" :error="form.errors.sppd_id" @update:model-value="onSelectSppd" />
            </div>
            <div class="sm:col-span-2">
              <AppInput v-model="form.catatan" label="Catatan" :error="form.errors.catatan" />
            </div>
          </div>
        </AppCard>

        <AppCard v-if="form.rekap.length" title="Rekap Biaya">
          <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-100">
              <thead>
                <tr class="text-xs text-gray-500 uppercase tracking-wide">
                  <th class="px-3 py-2 text-left">Jenis Biaya</th>
                  <th class="px-3 py-2 text-right">Rencana (Rp)</th>
                  <th class="px-3 py-2 text-right">Realisasi (Rp)</th>
                  <th class="px-3 py-2 text-left">Keterangan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-for="(item, idx) in form.rekap" :key="idx">
                  <td class="px-3 py-2 capitalize">{{ item.jenis_biaya?.replace(/_/g, ' ') }}</td>
                  <td class="px-3 py-2 text-right text-gray-500">{{ formatRupiah(item.rencana) }}</td>
                  <td class="px-3 py-2">
                    <AppCurrencyInput v-model="form.rekap[idx].realisasi" class="w-40" />
                  </td>
                  <td class="px-3 py-2">
                    <input v-model="form.rekap[idx].keterangan" type="text" class="w-full rounded border border-gray-200 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" />
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="bg-green-50 font-semibold">
                  <td colspan="2" class="px-3 py-2 text-right text-gray-700">Total Realisasi:</td>
                  <td class="px-3 py-2 text-right text-green-800">{{ formatRupiah(totalRealisasi()) }}</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </AppCard>

        <div class="flex gap-3">
          <AppButton type="submit" :loading="form.processing">Simpan SPJ</AppButton>
          <Link :href="route('spj.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
