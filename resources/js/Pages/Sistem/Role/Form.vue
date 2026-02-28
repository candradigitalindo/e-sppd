<script setup>
import { computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ role: Object, permissions: Array })
const isEdit = !!props.role

const form = useForm({
  name: props.role?.name ?? '',
  slug: props.role?.slug ?? '',
  permission_ids: props.role?.permissions?.map(p => p.id) ?? [],
})

function autoSlug() {
  if (!isEdit) {
    form.slug = form.name.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '')
  }
}

function togglePermission(id) {
  const idx = form.permission_ids.indexOf(id)
  if (idx === -1) form.permission_ids.push(id)
  else form.permission_ids.splice(idx, 1)
}

const allPermissionIds = computed(() => (props.permissions ?? []).map(p => p.id))
const isAllChecked = computed(() => allPermissionIds.value.length > 0 && allPermissionIds.value.every(id => form.permission_ids.includes(id)))
const isSomeChecked = computed(() => !isAllChecked.value && allPermissionIds.value.some(id => form.permission_ids.includes(id)))

function toggleAll() {
  if (isAllChecked.value) {
    form.permission_ids = []
  } else {
    form.permission_ids = [...allPermissionIds.value]
  }
}

function isModuleAllChecked(perms) {
  return perms.every(p => form.permission_ids.includes(p.id))
}

function isModuleSomeChecked(perms) {
  return !isModuleAllChecked(perms) && perms.some(p => form.permission_ids.includes(p.id))
}

function toggleModule(perms) {
  if (isModuleAllChecked(perms)) {
    const ids = perms.map(p => p.id)
    form.permission_ids = form.permission_ids.filter(id => !ids.includes(id))
  } else {
    const ids = perms.map(p => p.id)
    const newIds = ids.filter(id => !form.permission_ids.includes(id))
    form.permission_ids.push(...newIds)
  }
}

function submit() {
  if (isEdit) form.put(route('sistem.role.update', props.role.id))
  else form.post(route('sistem.role.store'))
}

const groupedPermissions = (props.permissions ?? []).reduce((acc, p) => {
  const mod = p.module ?? 'Lainnya'
  if (!acc[mod]) acc[mod] = []
  acc[mod].push(p)
  return acc
}, {})
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit Role' : 'Tambah Role'" :breadcrumbs="[{ label: 'Sistem' }, { label: 'Role', href: route('sistem.role.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <div class="space-y-5 max-w-3xl">
        <AppCard title="Data Role">
          <div class="grid grid-cols-2 gap-4">
            <AppInput v-model="form.name" label="Nama Role" required :error="form.errors.name" @input="autoSlug" />
            <AppInput v-model="form.slug" label="Slug" required :error="form.errors.slug" />
          </div>
        </AppCard>

        <AppCard title="Permissions">
          <p v-if="form.errors.permission_ids" class="mb-3 text-xs text-red-600">{{ form.errors.permission_ids }}</p>
          <div v-if="Object.keys(groupedPermissions).length" class="space-y-0 divide-y divide-gray-200">
            <!-- Ceklis Semua (Global) -->
            <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer pb-4">
              <input
                type="checkbox"
                :checked="isAllChecked"
                :indeterminate="isSomeChecked"
                class="rounded border-gray-300 text-green-600"
                @change="toggleAll"
              />
              <span class="text-gray-800">Ceklis Semua Permission</span>
              <span class="text-xs font-normal text-gray-400">({{ form.permission_ids.length }}/{{ allPermissionIds.length }})</span>
            </label>

            <div v-for="(perms, module) in groupedPermissions" :key="module" class="py-4">
              <!-- Ceklis Semua per Kategori -->
              <label class="flex items-center gap-2 mb-2 cursor-pointer">
                <input
                  type="checkbox"
                  :checked="isModuleAllChecked(perms)"
                  :indeterminate="isModuleSomeChecked(perms)"
                  class="rounded border-gray-300 text-green-600"
                  @change="toggleModule(perms)"
                />
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ module }}</span>
                <span class="text-xs text-gray-400">({{ perms.filter(p => form.permission_ids.includes(p.id)).length }}/{{ perms.length }})</span>
              </label>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                <label
                  v-for="p in perms"
                  :key="p.id"
                  class="flex items-center gap-2 text-sm cursor-pointer"
                >
                  <input
                    type="checkbox"
                    :value="p.id"
                    :checked="form.permission_ids.includes(p.id)"
                    class="rounded border-gray-300 text-green-600"
                    @change="togglePermission(p.id)"
                  />
                  <span class="text-gray-700">{{ p.name }}</span>
                </label>
              </div>
            </div>
          </div>
          <p v-else class="text-sm text-gray-400">Belum ada permission tersedia.</p>
        </AppCard>

        <div class="flex gap-3">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('sistem.role.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </div>
    </form>
  </AppLayout>
</template>
