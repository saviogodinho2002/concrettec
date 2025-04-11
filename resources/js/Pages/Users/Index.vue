<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Usuários</h2>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 rounded-xl">
      <v-card-text>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
          <v-text-field
            v-model="search"
            label="Buscar usuário"
            hide-details
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="solar:magnifer-bold-duotone"
            clearable
            rounded="lg"
            class="!tw-flex-grow"
          />
          <Link
            v-if="can.create"
            :href="route('users.create')"
            class="!tw-shrink-0"
          >
            <v-btn rounded="lg" variant="flat" color="primary" class="!tw-h-[48px]">
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:add-square-broken" width="22" height="22" />
                Novo Usuário
              </div>
            </v-btn>
          </Link>
        </div>

        <div v-if="users.length === 0" class="tw-text-center tw-py-8">
          <p class="tw-text-gray-500">Nenhum usuário cadastrado.</p>
        </div>

        <v-data-table
          v-else
          :headers="headers"
          :items="users"
          :search="search"
          :custom-filter="customFilter"
          class="!tw-border-0"
          :items-per-page="10"
          :items-per-page-options="[10, 25, 50, 100]"
        >
          <template v-slot:item.roles="{ item }">
            <div class="tw-flex tw-flex-wrap tw-gap-1">
              <v-chip
                v-for="role in item.roles"
                :key="role.id"
                size="small"
                class="!tw-mr-1 !tw-mb-1"
                :color="getRoleColor(role.name)"
                text-color="white"
              >
                {{ formatRoleName(role.name) }}
              </v-chip>
            </div>
          </template>

          <template v-slot:item.enterprise="{ item }">
            <span v-if="item.enterprise">{{ item.enterprise.name }}</span>
            <span v-else class="tw-text-gray-500">-</span>
          </template>

          <template v-slot:item.actions="{ item }">
            <div class="tw-flex tw-items-center tw-gap-2">
              <Link
                v-if="can.view"
                :href="route('users.show', item.id)"
                class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                title="Visualizar"
              >
                <Icon icon="solar:eye-bold-duotone" width="18" height="18" />
              </Link>

              <Link
                v-if="can.edit"
                :href="route('users.edit', item.id)"
                class="!tw-p-2 !tw-rounded-full !tw-text-gray-600 hover:!tw-text-primary-500 hover:!tw-bg-gray-100 !tw-transition !tw-inline-flex !tw-items-center !tw-justify-center"
                title="Editar"
              >
                <Icon icon="solar:pen-bold-duotone" width="18" height="18" />
              </Link>

              <button
                v-if="can.delete"
                @click="confirmUserDeletion(item)"
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

    <!-- Modal de confirmação de exclusão -->
    <v-dialog v-model="confirmingUserDeletion" max-width="500px">
      <v-card>
        <v-card-title class="tw-text-lg tw-font-medium">Confirmar Exclusão</v-card-title>
        <v-card-text>
          Tem certeza que deseja excluir este usuário?
          Esta ação não pode ser desfeita.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey" variant="text" @click="cancelUserDeletion">
            Cancelar
          </v-btn>
          <v-btn color="error" variant="text" @click="deleteUser" :loading="form.processing">
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
  users: {
    type: Array,
    required: true
  }
})

const search = ref('')

// Verificar permissões do usuário
const can = computed(() => {
  const abilities = usePage().props.auth.user.permissions

  return {
    view: abilities.includes('user-view'),
    create: abilities.includes('user-create'),
    edit: abilities.includes('user-edit'),
    delete: abilities.includes('user-delete')
  }
})

// Configuração da tabela
const headers = [
  { title: 'Nome', key: 'name' },
  { title: 'E-mail', key: 'email' },
  { title: 'Empresa', key: 'enterprise' },
  { title: 'Perfis', key: 'roles' },
  { title: 'Ações', key: 'actions', sortable: false }
]

// Obter cor para o chip do perfil
const getRoleColor = (roleName) => {
  const colors = {
    'master': 'red',
    'gestor': 'blue',
    'colaborador': 'green',
    'cliente': 'purple',
    'funcionario': 'orange'
  }

  return colors[roleName] || 'gray'
}

// Formatar nome do perfil
const formatRoleName = (roleName) => {
  const names = {
    'master': 'Master',
    'gestor': 'Gestor',
    'colaborador': 'Colaborador',
    'cliente': 'Cliente',
    'funcionario': 'Funcionário'
  }

  return names[roleName] || roleName
}

// Estado para exclusão
const userToDelete = ref(null)
const confirmingUserDeletion = ref(false)
const form = useForm({})

// Função de filtro personalizada
const customFilter = (value, search, item) => {
  if (!search) return true

  const searchLower = search?.toString().toLowerCase() || ''
  const itemValue = value?.toString().toLowerCase() || ''

  // Verifica se o valor corresponde diretamente
  if (itemValue.includes(searchLower)) return true

  // Verifica campos específicos do item
  const name = item?.name?.toString().toLowerCase() || ''
  const email = item?.email?.toString().toLowerCase() || ''
  const enterprise = item?.enterprise?.name?.toString().toLowerCase() || ''
  const roles = item?.roles?.map(role => formatRoleName(role.name)?.toLowerCase())?.join(' ') || ''

  return name.includes(searchLower) ||
         email.includes(searchLower) ||
         enterprise.includes(searchLower) ||
         roles.includes(searchLower)
}

// Confirmar exclusão de usuário
const confirmUserDeletion = (user) => {
  userToDelete.value = user
  confirmingUserDeletion.value = true
}

// Cancelar exclusão
const cancelUserDeletion = () => {
  userToDelete.value = null
  confirmingUserDeletion.value = false
}

// Excluir usuário
const deleteUser = () => {
  if (!userToDelete.value) return

  form.delete(route('users.destroy', userToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      cancelUserDeletion()
    }
  })
}
</script>
