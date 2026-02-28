<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import AppPagination from '@/Components/UI/AppPagination.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { useTable } from '@/composables/useTable.js'
import { usePermission } from '@/composables/usePermission'

const { can } = usePermission()

const props = defineProps({ users: Object, filters: Object })
const { filters, loading, apply, reset } = useTable(props.filters ?? {})
const columns = [{ key: 'name', label: 'Nama' }, { key: 'email', label: 'Email' }, { key: 'role', label: 'Role' }, { key: 'status', label: 'Status' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-36' }]

function toggleActive(id) { router.post(route('sistem.user.toggle-active', id)) }
function destroy(id) { if (confirm('Hapus user ini?')) router.delete(route('sistem.user.destroy', id)) }
</script>

<template>
  <AppLayout>
    <PageHeader title="Manajemen User" :breadcrumbs="[{ label: 'Sistem' }, { label: 'User' }]">
      <Link v-if="can('create_user')" :href="route('sistem.user.create')"><AppButton>+ Tambah User</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <div class="flex gap-3 border-b px-5 py-4">
        <input v-model="filters.q" type="search" placeholder="Cari user..." class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" @keyup.enter="apply()" />
        <AppButton @click="apply()">Cari</AppButton>
      </div>
      <AppTable :columns="columns" :rows="users?.data ?? []" :loading="loading">
        <template #default="{ row }">
          <td class="px-4 py-3">
            <div class="flex items-center gap-3">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-700 text-sm font-semibold">{{ row.name?.charAt(0) }}</div>
              <span class="text-sm font-medium text-gray-800">{{ row.name }}</span>
            </div>
          </td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.email }}</td>
          <td class="px-4 py-3"><AppBadge color="blue">{{ row.role?.name ?? 'No Role' }}</AppBadge></td>
          <td class="px-4 py-3">
            <AppBadge :color="row.is_active ? 'green' : 'gray'">{{ row.is_active ? 'Aktif' : 'Nonaktif' }}</AppBadge>
          </td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_user')" :href="route('sistem.user.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('edit_user')" variant="secondary" size="sm" @click="toggleActive(row.id)">{{ row.is_active ? 'Nonaktifkan' : 'Aktifkan' }}</AppButton>
              <AppButton v-if="can('delete_user')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
      <div class="px-5"><AppPagination :links="users?.links ?? []" :from="users?.from" :to="users?.to" :total="users?.total" /></div>
    </AppCard>
  </AppLayout>
</template>
