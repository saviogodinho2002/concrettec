<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Empresas</h2>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 rounded-xl">
      <v-card-text>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
          <v-text-field
            v-model="search"
            label="Buscar empresa"
            hide-details
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="solar:magnifer-bold-duotone"
            clearable
            rounded="lg"
            class="!tw-flex-grow"
          />
          <Link
            v-if="$page.props.auth.user.permissions.includes('enterprise-create')"
            :href="route('enterprises.create')"
            class="!tw-shrink-0"
          >
            <v-btn rounded="lg" variant="flat" color="primary" class="!tw-h-[48px]">
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:add-square-broken" width="22" height="22" />
                Nova Empresa
              </div>
            </v-btn>
          </Link>
        </div>

        <v-data-table
          :headers="headers"
          :items="enterprises"
          :loading="loading"
          :search="search"
          :custom-filter="customFilter"
        >
          <template v-slot:item.type="{ item }">
            <v-chip
              :color="item.type === 'construcao' ? 'info' : 'success'"
              size="small"
            >
              {{ ENTERPRISES_TYPES[ item.type]  }}
            </v-chip>
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="tw-flex tw-items-center tw-gap-2">
              <Link
                v-if="$page.props.auth.user.permissions.includes('enterprise-view')"
                :href="route('enterprises.show', item.id)"
                class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                title="Visualizar"
              >
                <Icon icon="solar:eye-bold-duotone" width="18" height="18" />
              </Link>

              <Link
                v-if="$page.props.auth.user.permissions.includes('enterprise-edit')"
                :href="route('enterprises.edit', item.id)"
                class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                title="Editar"
              >
                <Icon icon="solar:pen-bold-duotone" width="18" height="18" />
              </Link>

              <button
                v-if="$page.props.auth.user.permissions.includes('enterprise-delete')"
                @click="confirmDelete(item)"
                class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-red-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                title="Excluir"
              >
                <Icon icon="solar:trash-bin-trash-bold-duotone" width="18" height="18" />
              </button>
            </div>
          </template>
        </v-data-table>
      </v-card-text>
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
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import {ENTERPRISES_TYPES} from "../../scripts/mapConstants.js";

const props = defineProps({
  enterprises: {
    type: Array,
    required: true
  }
})

const loading = ref(false)
const deleteDialog = ref(false)
const search = ref('')
const enterpriseToDelete = ref(null)

const headers = [
    { title: 'Nome', key: 'name', align: 'start' },
    { title: 'Nome Fantasia', key: 'fantasy_name', align: 'start' },
  { title: 'CNPJ', key: 'cnpj', align: 'start' },
  { title: 'Tipo', key: 'type', align: 'start' },
  { title: 'Preço CP', key: 'price_cp', align: 'start' },
  { title: 'Ações', key: 'actions', align: 'start', sortable: false }
]

// Função de filtro personalizada
const customFilter = (value, search, item) => {
    if (!search) return true

    const searchLower = search?.toString().toLowerCase() || ''
    const itemValue = value?.toString().toLowerCase() || ''

    // Verifica se o valor corresponde diretamente
    if (itemValue.includes(searchLower)) return true

    // Verifica campos específicos do item
    const name = item?.name?.toString().toLowerCase() || ''
    const fantasyName = item?.fantasy_name?.toString().toLowerCase() || ''
    const cnpj = item?.cnpj?.toString().toLowerCase() || ''
    const type = item?.type === 'constructor' ? 'construtora' : 'concreteira'

    return name.includes(searchLower) ||
           fantasyName.includes(searchLower) ||
           cnpj.includes(searchLower) ||
           type.includes(searchLower)
}

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
