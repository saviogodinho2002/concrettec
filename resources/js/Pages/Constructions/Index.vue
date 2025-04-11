<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Obras</h2>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 rounded-xl">
      <v-card-text>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
          <v-text-field
            v-model="search"
            label="Buscar obra"
            hide-details
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="solar:magnifer-bold-duotone"
            clearable
            rounded="lg"
            class="!tw-flex-grow"
          />
          <Link
            v-if="$page.props.auth.user.permissions.includes('construction-create')"
            :href="route('constructions.create')"
            class="!tw-shrink-0"
          >
            <v-btn rounded="lg" variant="flat" color="primary" class="!tw-h-[48px]">
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:add-square-broken" width="22" height="22" />
                Nova Obra
              </div>
            </v-btn>
          </Link>
        </div>

        <v-table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>Empresa</th>
              <th>Endereço</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="construction in filteredConstructions" :key="construction.id">
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
                    class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                    title="Editar"
                  >
                    <Icon icon="solar:pen-bold-duotone" width="18" height="18" />
                  </Link>

                  <button
                    v-if="$page.props.auth.user.permissions.includes('construction-delete')"
                    @click="confirmDelete(construction)"
                    class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-red-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                    title="Excluir"
                  >
                    <Icon icon="solar:trash-bin-trash-bold-duotone" width="18" height="18" />
                  </button>
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
      </v-card-text>
    </v-card>

    <!-- Dialog de confirmação de exclusão -->
    <v-dialog v-model="deleteDialog" max-width="500px">
      <v-card>
        <v-card-title class="tw-text-lg tw-font-medium">Confirmar Exclusão</v-card-title>
        <v-card-text>
          Tem certeza que deseja excluir esta obra?
          Esta ação não pode ser desfeita.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="deleteDialog = false">
            Cancelar
          </v-btn>
          <v-btn color="error" variant="text" @click="deleteConstruction">
            Excluir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
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

const search = ref('')
const deleteDialog = ref(false)
const constructionToDelete = ref(null)

// Filtra as construções baseado na busca
const filteredConstructions = computed(() => {
  if (!search.value) return props.constructions.data

  const searchLower = search.value.toLowerCase()
  
  return props.constructions.data.filter(construction => {
    const name = construction?.name?.toString().toLowerCase() || ''
    const enterprise = construction?.enterprise?.name?.toString().toLowerCase() || ''
    const address = [
      construction?.address?.street,
      construction?.address?.number,
      construction?.address?.neighborhood
    ].filter(Boolean).join(' ').toLowerCase()

    return name.includes(searchLower) ||
           enterprise.includes(searchLower) ||
           address.includes(searchLower)
  })
})

const confirmDelete = (construction) => {
  constructionToDelete.value = construction
  deleteDialog.value = true
}

const deleteConstruction = () => {
  if (!constructionToDelete.value) return

  const form = useForm()
  form.delete(route('constructions.destroy', constructionToDelete.value.id), {
    onSuccess: () => {
      deleteDialog.value = false
      constructionToDelete.value = null
    }
  })
}
</script>
