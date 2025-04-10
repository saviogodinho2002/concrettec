<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Detalhes do Usuário</h2>
        <div class="tw-flex tw-gap-3">
          <Link
            :href="route('users.index')"
            class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
          >
            <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
            <span>Voltar</span>
          </Link>
          
          <Link
            v-if="can.edit"
            :href="route('users.edit', user.id)"
            class="!tw-font-medium tw-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-md tw-bg-primary-500 tw-text-white hover:tw-bg-primary-600 tw-transition"
          >
            <Icon icon="solar:pen-bold-duotone" width="22" height="22" />
            <span>Editar</span>
          </Link>
        </div>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-card-text>
        <div class="tw-p-2">
          <div class="tw-flex tw-justify-between tw-items-center tw-pb-4 tw-border-b tw-border-gray-200">
            <div>
              <h3 class="tw-text-xl tw-font-medium">{{ user.name }}</h3>
              <p class="tw-text-gray-600">{{ user.email }}</p>
            </div>
            <div class="tw-flex tw-flex-wrap tw-gap-2">
              <v-chip
                v-for="role in user.roles"
                :key="role.id"
                size="small"
                class="!tw-mr-1"
                :color="getRoleColor(role.name)"
                text-color="white"
              >
                {{ formatRoleName(role.name) }}
              </v-chip>
            </div>
          </div>

          <!-- Informações pessoais -->
          <div class="tw-mt-6">
            <h4 class="tw-text-lg tw-font-medium tw-mb-4">Informações</h4>
            
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-x-6 tw-gap-y-4">
              <div>
                <p class="tw-text-sm tw-text-gray-500">Nome</p>
                <p>{{ user.name }}</p>
              </div>
              
              <div>
                <p class="tw-text-sm tw-text-gray-500">E-mail</p>
                <p>{{ user.email }}</p>
              </div>
              
              <div>
                <p class="tw-text-sm tw-text-gray-500">Empresa</p>
                <p v-if="user.enterprise">{{ user.enterprise.name }}</p>
                <p v-else class="tw-text-gray-500">Não associado a empresa</p>
              </div>
              
              <div>
                <p class="tw-text-sm tw-text-gray-500">Perfis</p>
                <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mt-1">
                  <v-chip
                    v-for="role in user.roles"
                    :key="role.id"
                    size="small"
                    class="!tw-mr-1"
                    :color="getRoleColor(role.name)"
                    text-color="white"
                  >
                    {{ formatRoleName(role.name) }}
                  </v-chip>
                </div>
              </div>
            </div>
          </div>

          <!-- Telefones -->
          <div class="tw-mt-8">
            <h4 class="tw-text-lg tw-font-medium tw-mb-4">Telefones</h4>
            
            <div v-if="user.phone_numbers && user.phone_numbers.length > 0">
              <div class="tw-flex tw-flex-col tw-gap-3">
                <div v-for="(phone, index) in user.phone_numbers" :key="index" class="tw-flex tw-items-center tw-gap-2">
                  <Icon icon="solar:phone-bold-duotone" class="tw-text-gray-500" width="18" height="18" />
                  <span>{{ formatPhoneNumber(phone.phone_number) }}</span>
                </div>
              </div>
            </div>
            <div v-else class="tw-text-gray-500">
              Nenhum telefone cadastrado
            </div>
          </div>
          
          <!-- Botão de exclusão -->
          <div v-if="can.delete" class="tw-mt-10 tw-pt-6 tw-border-t tw-border-gray-200">
            <v-btn
              color="error"
              variant="outlined"
              @click="confirmUserDeletion"
              class="!tw-font-medium"
            >
              <Icon icon="solar:trash-bin-trash-bold-duotone" width="18" height="18" class="tw-mr-2" />
              Excluir Usuário
            </v-btn>
          </div>
        </div>
      </v-card-text>
    </v-card>

    <!-- Modal de confirmação de exclusão -->
    <v-dialog v-model="confirmingDeletion" max-width="500px">
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
            @click="confirmingDeletion = false"
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
  user: {
    type: Object,
    required: true
  }
})

// Verificar permissões do usuário
const can = computed(() => {
  const abilities = usePage().props.auth.user.permissions
  
  return {
    edit: abilities.includes('user-edit'),
    delete: abilities.includes('user-delete')
  }
})

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

// Formatar número de telefone
const formatPhoneNumber = (phone) => {
  if (!phone) return ''
  
  // Remove caracteres não numéricos
  const numbers = phone.replace(/\D/g, '')
  
  // Aplica formatação baseada no tamanho
  if (numbers.length === 11) {
    return `(${numbers.substring(0, 2)}) ${numbers.substring(2, 7)}-${numbers.substring(7)}`
  } else if (numbers.length === 10) {
    return `(${numbers.substring(0, 2)}) ${numbers.substring(2, 6)}-${numbers.substring(6)}`
  }
  
  return phone
}

// Estado para exclusão
const confirmingDeletion = ref(false)
const form = useForm({})

// Confirmar exclusão de usuário
const confirmUserDeletion = () => {
  confirmingDeletion.value = true
}

// Excluir usuário
const deleteUser = () => {
  form.delete(route('users.destroy', props.user.id), {
    onSuccess: () => {
      // Redirecionamento é tratado pelo controller
    }
  })
}
</script> 