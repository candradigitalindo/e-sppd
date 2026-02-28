<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import AppCard from '@/Components/UI/AppCard.vue'
import AppButton from '@/Components/UI/AppButton.vue'
import AppInput from '@/Components/UI/AppInput.vue'
import AppSelect from '@/Components/UI/AppSelect.vue'
import PageHeader from '@/Components/UI/PageHeader.vue'

const props = defineProps({ user: Object, roles: Array })
const isEdit = !!props.user
const form = useForm({ name: props.user?.name ?? '', email: props.user?.email ?? '', password: '', password_confirmation: '', role_id: props.user?.role_id ?? '' })
function submit() {
  if (isEdit) form.put(route('sistem.user.update', props.user.id))
  else form.post(route('sistem.user.store'))
}
</script>

<template>
  <AppLayout>
    <PageHeader :title="isEdit ? 'Edit User' : 'Tambah User'" :breadcrumbs="[{ label: 'Sistem' }, { label: 'User', href: route('sistem.user.index') }, { label: isEdit ? 'Edit' : 'Tambah' }]" />
    <form @submit.prevent="submit">
      <AppCard title="Data User" class="max-w-md">
        <div class="space-y-4">
          <AppInput v-model="form.name" label="Nama" required :error="form.errors.name" />
          <AppInput v-model="form.email" label="Email" type="email" required :disabled="isEdit" :error="form.errors.email" />
          <AppInput v-model="form.password" label="Password" type="password" :required="!isEdit" :placeholder="isEdit ? 'Kosongkan jika tidak diubah' : ''" :error="form.errors.password" />
          <AppInput v-model="form.password_confirmation" label="Konfirmasi Password" type="password" :required="!isEdit" :error="form.errors.password_confirmation" />
          <AppSelect v-model="form.role_id" label="Role" :options="roles" option-label="name" :error="form.errors.role_id" />
        </div>
        <div class="mt-6 flex gap-3 border-t pt-5">
          <AppButton type="submit" :loading="form.processing">{{ isEdit ? 'Perbarui' : 'Simpan' }}</AppButton>
          <Link :href="route('sistem.user.index')"><AppButton variant="secondary">Batal</AppButton></Link>
        </div>
      </AppCard>
    </form>
  </AppLayout>
</template>
