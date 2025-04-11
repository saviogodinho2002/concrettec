<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Detalhes do Usuário</h2>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 !tw-mb-6 rounded-xl">
      <v-card-text>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
          <Link
            :href="route('users.index')"
            class="!tw-shrink-0"
          >
            <v-btn rounded="lg" variant="outlined" color="gray" class="!tw-h-[48px]">
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
                Voltar
              </div>
            </v-btn>
          </Link>

          <Link
            v-if="$page.props.auth.user.permissions.includes('user-edit')"
            :href="route('users.edit', user.id)"
            class="!tw-shrink-0"
          >
            <v-btn rounded="lg" variant="flat" color="primary" class="!tw-h-[48px]">
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:pen-bold-duotone" width="22" height="22" />
                Editar Usuário
              </div>
            </v-btn>
          </Link>
        </div>

        <div class="tw-p-2">
          <!-- Cabeçalho com informações principais -->
          <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start md:tw-items-center tw-pb-4 tw-border-b tw-border-gray-200">
            <div>
              <h3 class="tw-text-xl tw-font-medium tw-mb-1">{{ user.name }}</h3>
              <p class="tw-text-gray-600 tw-flex tw-items-center tw-gap-2">
                <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                  <Icon icon="solar:user-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                </div>
                <span>{{ user.email }}</span>
              </p>
            </div>
            <div class="tw-mt-3 md:tw-mt-0">
              <div class="tw-flex tw-flex-wrap tw-gap-1">
                <v-chip
                  v-for="role in user.roles"
                  :key="role.id"
                  :color="getRoleColor(role.name)"
                  size="small"
                  class="!tw-font-medium"
                >
                  <Icon icon="solar:shield-user-bold-duotone" width="18" height="18" class="tw-mr-1" />
                  {{ formatRoleName(role.name) }}
                </v-chip>
              </div>
            </div>
          </div>

          <!-- Informações detalhadas em grid -->
          <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-8 tw-mt-6">
            <!-- Coluna de Informações do Usuário -->
            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
              <h4 class="tw-text-lg tw-font-medium tw-mb-4 tw-flex tw-items-center tw-gap-2">
                <Icon icon="solar:info-circle-bold-duotone" width="22" height="22" />
                Informações do Usuário
              </h4>

              <div class="tw-space-y-4">
                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:user-id-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Nome</p>
                    <p class="tw-font-medium">{{ user.name }}</p>
                  </div>
                </div>

                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:letter-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">E-mail</p>
                    <p class="tw-font-medium">{{ user.email }}</p>
                  </div>
                </div>

                <div class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:buildings-3-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Empresa</p>
                    <p class="tw-font-medium">{{ user.enterprise?.name || '-' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Coluna de Contatos -->
            <div class="tw-bg-gray-50 tw-p-4 tw-rounded-lg">
              <h4 class="tw-text-lg tw-font-medium tw-mb-4 tw-flex tw-items-center tw-gap-2">
                <Icon icon="solar:phone-bold-duotone" width="22" height="22" />
                Contatos
              </h4>

              <div v-if="user.phone_numbers && user.phone_numbers.length > 0" class="tw-space-y-4">
                <div v-for="phone in user.phone_numbers" :key="phone.id" class="tw-flex tw-gap-3">
                  <div class="tw-w-8 tw-h-8 tw-bg-primary-100 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-flex-shrink-0">
                    <Icon icon="solar:phone-bold-duotone" width="18" height="18" class="tw-text-primary-500" />
                  </div>
                  <div>
                    <p class="tw-text-sm tw-text-gray-500">Telefone</p>
                    <p class="tw-font-medium">{{ phone.phone_number }}</p>
                  </div>
                </div>
              </div>

              <div v-else class="tw-text-gray-500 tw-text-sm">
                Nenhum telefone cadastrado.
              </div>
            </div>
          </div>
        </div>
      </v-card-text>
    </v-card>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  user: {
    type: Object,
    required: true
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
</script>
