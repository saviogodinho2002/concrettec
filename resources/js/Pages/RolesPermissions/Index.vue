<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Papéis e Permissões</h2>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 rounded-xl">
      <v-card-text>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
          <v-text-field
            v-model="search"
            label="Buscar papel"
            hide-details
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="solar:magnifer-bold-duotone"
            clearable
            rounded="lg"
            class="!tw-flex-grow"
          />
        </div>

        <div v-if="roles.length === 0" class="tw-text-center tw-py-8">
          <p class="tw-text-gray-500">Nenhum papel cadastrado.</p>
        </div>

        <v-data-table
          v-else
          :headers="headers"
          :items="roles"
          :search="search"
          :custom-filter="customFilter"
          class="!tw-border-0"
          :items-per-page="10"
          :items-per-page-options="[10, 25, 50, 100]"
        >
          <template v-slot:item.permissions="{ item }">
            <div class="tw-flex tw-flex-wrap tw-gap-1">
              <v-chip
                v-for="permission in item.permissions"
                :key="permission.id"
                size="small"
                class="!tw-mr-1 !tw-mb-1"
                color="primary"
                variant="tonal"
              >
                {{ formatPermissionName(permission.name) }}
              </v-chip>
            </div>
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="tw-flex tw-items-center tw-gap-2">
              <v-btn
                v-if="can.edit"
                color="primary"
                variant="tonal"
                size="small"
                @click="editRole(item)"
                prepend-icon="solar:pen-bold-duotone"
              >
                Editar
              </v-btn>
            </div>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <!-- Modal de edição de permissões -->
    <v-dialog v-model="editDialog" max-width="800px">
      <v-card>
        <v-card-title class="tw-text-lg tw-font-medium">
          Editar Permissões - {{ selectedRole?.name }}
        </v-card-title>
        <v-card-text>
          <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
            <div v-for="model in models" :key="model" class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
              <h4 class="tw-font-medium tw-mb-4 tw-text-gray-700">
                {{ formatModelName(model) }}
              </h4>
              <div class="tw-space-y-2">
                <v-checkbox
                  v-for="action in actions"
                  :key="action"
                  v-model="selectedPermissions"
                  :label="formatActionName(action)"
                  :value="`${model}-${action}`"
                  color="primary"
                  density="comfortable"
                  hide-details
                />
              </div>
            </div>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="closeEditDialog">
            Cancelar
          </v-btn>
          <v-btn color="primary" variant="text" @click="savePermissions" :loading="form.processing">
            Salvar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  roles: {
    type: Array,
    required: true
  }
})

const search = ref('')
const editDialog = ref(false)
const selectedRole = ref(null)
const selectedPermissions = ref([])

// Verificar permissões do usuário
const can = computed(() => {
  const abilities = usePage().props.auth.user.permissions

  return {
    view: abilities.includes('roles_permissions-view'),
    edit: abilities.includes('roles_permissions-edit')
  }
})

// Configuração da tabela
const headers = [
  { title: 'Papel', key: 'name' },
  { title: 'Permissões', key: 'permissions' },
  { title: 'Ações', key: 'actions', sortable: false }
]

// Lista de modelos e ações
const models = ['user', 'enterprise', 'construction', 'address', 'phone_number', 'roles_permissions']
const actions = ['view', 'list', 'create', 'edit', 'delete']

// Formulário para atualização
const form = useForm({
  permissions: []
})

// Formatar nome do modelo
const formatModelName = (model) => {
  const names = {
    'user': 'Usuários',
    'enterprise': 'Empresas',
    'construction': 'Obras',
    'address': 'Endereços',
    'phone_number': 'Telefones',
    'roles_permissions': 'Papéis e Permissões'
  }

  return names[model] || model
}

// Formatar nome da ação
const formatActionName = (action) => {
  const names = {
    'view': 'Visualizar',
    'list': 'Listar',
    'create': 'Criar',
    'edit': 'Editar',
    'delete': 'Excluir'
  }

  return names[action] || action
}

// Formatar nome da permissão
const formatPermissionName = (permission) => {
  const [model, action] = permission.split('-')
  return `${formatModelName(model)} - ${formatActionName(action)}`
}

// Função de filtro personalizada
const customFilter = (value, search, item) => {
  if (!search) return true

  const searchLower = search?.toString().toLowerCase() || ''
  const itemValue = value?.toString().toLowerCase() || ''

  // Verifica se o valor corresponde diretamente
  if (itemValue.includes(searchLower)) return true

  // Verifica campos específicos do item
  const name = item?.name?.toString().toLowerCase() || ''
  const permissions = item?.permissions?.map(p => formatPermissionName(p.name))?.join(' ') || ''

  return name.includes(searchLower) || permissions.includes(searchLower)
}

// Abrir modal de edição
const editRole = (role) => {
  selectedRole.value = role
  selectedPermissions.value = role.permissions.map(p => p.name)
  editDialog.value = true
}

// Fechar modal de edição
const closeEditDialog = () => {
  editDialog.value = false
  selectedRole.value = null
  selectedPermissions.value = []
}

// Salvar permissões
const savePermissions = () => {
  form.permissions = selectedPermissions.value

  form.put(route('roles.permissions.update', selectedRole.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      closeEditDialog()
    }
  })
}
</script>
