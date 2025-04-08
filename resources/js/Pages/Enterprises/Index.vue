<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Empresas</h2>
        <Link
          v-if="$page.props.auth.user.permissions.includes('enterprise-create')"
          color="primary"
          :href="route('enterprises.create')"
          prepend-icon="solar:add-circle-bold-duotone"
          class="!tw-font-medium"
        >
          Nova Empresa
        </Link>
      </div>
    </template>
    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-data-table
        :headers="headers"
        :items="enterprises"
        :loading="loading"
      >
        <template v-slot:item.type="{ item }">
          <v-chip
            :color="item.type === 'constructor' ? 'info' : 'success'"
            size="small"
          >
            {{ item.type === 'constructor' ? 'Construtora' : 'Concreteira' }}
          </v-chip>
        </template>

        <template v-slot:item.actions="{ item }">
          <div class="tw-flex tw-gap-2">
            <v-btn
              v-if="$page.props.auth.user.permissions.includes('enterprise-view')"
              icon="solar:eye-bold-duotone"
              size="small"
              variant="text"
              :to="route('enterprises.show', item.id)"
            />
            <Link
              v-if="$page.props.auth.user.permissions.includes('enterprise-edit')"
              :href="route('enterprises.edit', item.id)"
              class="tw-text-gray-600 hover:tw-text-primary"
            >
              <Icon icon="solar:pen-bold-duotone" width="22" height="22" />
            </Link>
            <v-btn
              v-if="$page.props.auth.user.permissions.includes('enterprise-delete')"
              icon="solar:trash-bin-trash-bold-duotone"
              size="small"
              color="error"
              variant="text"
              @click="confirmDelete(item)"
            />
          </div>
        </template>
      </v-data-table>
    </v-card>

    <!-- Dialog de confirmação de exclusão -->
    <v-dialog v-model="deleteDialog" max-width="500px">
      <v-card>
        <v-card-title class="tw-text-lg tw-font-medium">Confirmar Exclusão</v-card-title>
        <v-card-text>
          Tem certeza que deseja excluir esta empresa?
          Esta ação não pode ser desfeita.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="deleteDialog = false">
            Cancelar
          </v-btn>
          <v-btn color="error" variant="text" @click="deleteEnterprise">
            Excluir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm,Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  enterprises: {
    type: Array,
    required: true
  }
})

const loading = ref(false)
const deleteDialog = ref(false)
const enterpriseToDelete = ref(null)

const headers = [
    { title: 'Nome', key: 'name', align: 'start' },
    { title: 'Nome Fantasia', key: 'fantasy_name', align: 'start' },
  { title: 'CNPJ', key: 'cnpj', align: 'start' },
  { title: 'Tipo', key: 'type', align: 'start' },
  { title: 'Preço CP', key: 'price_cp', align: 'start' },
  { title: 'Ações', key: 'actions', align: 'end', sortable: false }
]

const confirmDelete = (item) => {
  enterpriseToDelete.value = item
  deleteDialog.value = true
}

const deleteEnterprise = () => {
  const form = useForm()
  form.delete(route('enterprises.destroy', enterpriseToDelete.value.id), {
    onSuccess: () => {
      deleteDialog.value = false
      enterpriseToDelete.value = null
    }
  })
}
</script>
