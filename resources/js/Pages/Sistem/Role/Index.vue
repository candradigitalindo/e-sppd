<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppTable from '@/Components/UI/AppTable.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppBadge from '@/Components/UI/AppBadge.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'
import { usePermission } from '@/composables/usePermission'

const props = defineProps({ roles: Array })
const { can } = usePermission()
const columns = [{ key: 'name', label: 'Nama Role' }, { key: 'slug', label: 'Slug' }, { key: 'permissions', label: 'Jumlah Permission' }, { key: 'users', label: 'Jumlah User' }, { key: 'aksi', label: 'Aksi', class: 'text-right w-28' }]

function destroy(id) {
  if (confirm('Hapus role ini?')) router.delete(route('sistem.role.destroy', id))
}
</script>

<template>
  <AppLayout>
    <PageHeader title="Manajemen Role" :breadcrumbs="[{ label: 'Sistem' }, { label: 'Role' }]">
      <Link v-if="can('create_role')" :href="route('sistem.role.create')"><AppButton>+ Tambah Role</AppButton></Link>
    </PageHeader>
    <AppCard :padding="false">
      <AppTable :columns="columns" :rows="roles ?? []">
        <template #default="{ row }">
          <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ row.name }}</td>
          <td class="px-4 py-3 text-sm font-mono text-gray-500">{{ row.slug }}</td>
          <td class="px-4 py-3"><AppBadge color="blue">{{ row.permissions_count ?? 0 }} permission</AppBadge></td>
          <td class="px-4 py-3 text-sm text-gray-600">{{ row.users_count ?? 0 }} user</td>
          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-1">
              <Link v-if="can('edit_role')" :href="route('sistem.role.edit', row.id)"><AppButton variant="ghost" size="sm">Edit</AppButton></Link>
              <AppButton v-if="can('delete_role')" variant="danger" size="sm" @click="destroy(row.id)">Hapus</AppButton>
            </div>
          </td>
        </template>
      </AppTable>
    </AppCard>
  </AppLayout>
</template>
