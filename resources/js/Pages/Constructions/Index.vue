<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Obras</h2>
        <Link
          v-if="$page.props.auth.user.permissions.includes('construction-create')"
          :href="route('constructions.create')"
          class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
        >
          <Icon icon="solar:add-circle-bold-duotone" width="22" height="22" />
          <span>Nova Obra</span>
        </Link>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-table>
        <thead>
          <tr>
            <th>Nome</th>
            <th>Empresa</th>
            <th>Endereço</th>
            <th >Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="construction in constructions.data" :key="construction.id">
            <td>{{ construction.name }}</td>
            <td>{{ construction.enterprise.name }}</td>
            <td>
              {{ construction.address.street }},
              {{ construction.address.number }}
              {{ construction.address.neighborhood ? ` - ${construction.address.neighborhood}` : '' }}
            </td>
            <td >
              <div class="tw-flex tw-items-center tw-gap-2">
                  <Link
                      v-if="$page.props.auth.user.permissions.includes('construction-view')"
                      :href="route('constructions.show', construction.id)"
                      class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                      title="Visualizar"
                  >
                      <Icon icon="solar:eye-bold-duotone" width="18" height="18" />
                  </Link>
                <Link
                  v-if="$page.props.auth.user.permissions.includes('construction-edit')"
                  :href="route('constructions.edit', construction.id)"
                  class="tw-text-gray-600 hover:tw-text-primary"
                >
                  <Icon icon="solar:pen-bold-duotone" width="22" height="22" />
                </Link>
                <Link
                  v-if="$page.props.auth.user.permissions.includes('construction-delete')"
                  :href="route('constructions.destroy', construction.id)"
                  method="delete"
                  as="button"
                  type="button"
                  class="tw-text-gray-600 hover:tw-text-red-500"
                  @click.prevent="destroy(construction.id)"
                >
                  <Icon icon="solar:trash-bin-trash-bold-duotone" width="22" height="22" />
                </Link>
              </div>
            </td>
          </tr>
          <tr v-if="constructions.data.length === 0">
            <td colspan="4" class="tw-text-center tw-py-4">
              Nenhuma obra encontrada
            </td>
          </tr>
        </tbody>
      </v-table>
      <v-divider></v-divider>
      <div class="tw-p-4">
        <Pagination :links="constructions.links" />
      </div>
    </v-card>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  constructions: {
    type: Object,
    required: true
  }
})

const destroy = (id) => {
  if (confirm('Tem certeza que deseja excluir esta obra?')) {
    router.delete(route('constructions.destroy', id))
  }
}
</script>
