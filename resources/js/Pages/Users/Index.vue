<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Usuários</h2>
        <Link
          v-if="can.create"
          :href="route('users.create')"
          class="!tw-font-medium !tw-flex !tw-items-center !tw-gap-2 !tw-px-4 !tw-py-2 !tw-rounded-md !tw-bg-primary-500 !tw-text-white hover:!tw-bg-primary-600 !tw-transition"
        >
          <Icon icon="solar:add-circle-bold-duotone" width="22" height="22" />
          <span>Novo Usuário</span>
        </Link>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-card-text>
        <div v-if="users.length === 0" class="tw-text-center tw-py-8">
          <p class="tw-text-gray-500">Nenhum usuário cadastrado.</p>
        </div>

        <v-data-table
          v-else
          :headers="headers"
          :items="users"
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
        <v-card-title class="!tw-bg-red-50 !tw-pb-4">
          <span class="!tw-text-red-600">Excluir Usuário</span>
        </v-card-title>
        <v-card-text class="!tw-pt-4">
          <p class="!tw-mb-4">Tem certeza que deseja excluir este usuário?</p>
          <p class="!tw-text-sm !tw-text-gray-600">Esta ação não pode ser desfeita. O usuário será marcado como excluído.</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            variant="outlined"
            color="grey"
            @click="cancelUserDeletion"
            class="!tw-text-gray-700"
          >
            Cancelar
          </v-btn>
          <v-btn
            variant="flat"
            color="error"
            @click="deleteUser"
            class="!tw-text-white"
            :loading="form.processing"
          >
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