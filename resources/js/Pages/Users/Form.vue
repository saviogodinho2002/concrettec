<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800">{{ user ? 'Editar' : 'Novo' }} Usuário</h2>
      </div>
    </template>

    <v-form @submit.prevent="submit">
      <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 tw-rounded-xl">
        <v-card-text class="!tw-p-6">
          <v-row>
            <!-- Seção: Dados básicos -->
            <v-col cols="12">
              <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
                <Icon icon="solar:user-id-bold-duotone" class="tw-text-emerald-600" width="24" height="24" />
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Dados do Usuário</h3>
              </div>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.name"
                label="Nome"
                :error-messages="form.errors.name"
                variant="outlined"
                density="comfortable"
                required
                autocomplete="name"
                hide-details="auto"
                prepend-inner-icon="solar:user-bold-duotone"
                class="tw-mb-4"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.email"
                label="E-mail"
                :error-messages="form.errors.email"
                variant="outlined"
                density="comfortable"
                required
                autocomplete="email"
                type="email"
                prepend-inner-icon="solar:letter-bold-duotone"
                class="tw-mb-4"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.password"
                label="Senha"
                :error-messages="form.errors.password"
                variant="outlined"
                density="comfortable"
                :required="!user"
                autocomplete="new-password"
                type="password"
                :placeholder="user ? 'Deixe em branco para manter a senha atual' : ''"
                prepend-inner-icon="solar:lock-password-bold-duotone"
                class="tw-mb-4"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.password_confirmation"
                label="Confirmar Senha"
                variant="outlined"
                density="comfortable"
                :required="!user"
                autocomplete="new-password"
                type="password"
                prepend-inner-icon="solar:lock-password-bold-duotone"
                class="tw-mb-4"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="form.enterprise_id"
                label="Empresa"
                :items="enterprises"
                item-title="name"
                item-value="id"
                :error-messages="form.errors.enterprise_id"
                variant="outlined"
                density="comfortable"
                clearable
                :disabled="isSystemRole"
                :hint="isSystemRole ? 'Usuários com perfis de sistema não podem ter empresa vinculada' : ''"
                persistent-hint
                prepend-inner-icon="solar:buildings-2-bold-duotone"
                class="tw-mb-4"
              />
            </v-col>

            <!-- Seção: Perfis e Contatos -->
            <v-col cols="12" class="!tw-mt-4">
              <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
                <Icon icon="solar:users-group-rounded-bold-duotone" class="tw-text-emerald-600" width="24" height="24" />
                <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Perfis e Contatos</h3>
              </div>
            </v-col>

            <v-col cols="12" md="6">
              <div class="tw-bg-gray-50/50 tw-p-4 tw-rounded-xl tw-border tw-border-gray-100">
                <h4 class="tw-font-medium tw-mb-4 tw-text-gray-700">Perfis do Usuário</h4>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-2">
                  <div
                    v-for="role in roles"
                    :key="role.id"
                    class="tw-bg-white tw-p-3 tw-rounded-lg tw-border tw-border-gray-100 tw-transition-all hover:tw-border-emerald-200"
                  >
                    <v-checkbox
                      v-model="form.roles"
                      :label="formatRoleName(role.name)"
                      :value="role.name"
                      hide-details="auto"
                      color="success"
                      density="comfortable"
                      class="!tw-m-0"
                      @update:model-value="checkRoleTypes"
                    />
                  </div>
                </div>

                <div v-if="form.errors.roles" class="tw-text-red-500 tw-mt-3 tw-text-sm">
                  {{ form.errors.roles }}
                </div>
              </div>
            </v-col>

            <v-col cols="12" md="6">
              <div class="tw-bg-gray-50/50 tw-p-4 tw-rounded-xl tw-border tw-border-gray-100">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                  <h4 class="tw-font-medium tw-text-gray-700">Telefones</h4>
                  <v-btn
                    color="success"
                    variant="tonal"
                    @click="addPhoneNumber"
                    class="!tw-font-medium"
                    size="small"
                    prepend-icon="solar:add-circle-bold-duotone"
                  >
                    Adicionar
                  </v-btn>
                </div>

                <div v-if="form.phone_numbers.length === 0" class="tw-bg-gray-100/50 tw-rounded-lg tw-p-4 tw-text-gray-500 tw-text-sm tw-text-center">
                  <Icon icon="solar:phone-bold-duotone" class="tw-mb-2" width="24" height="24" />
                  <p>Nenhum telefone cadastrado</p>
                </div>

                <div v-else class="tw-space-y-2">
                  <div 
                    v-for="(phone, index) in form.phone_numbers" 
                    :key="index" 
                    class="tw-flex tw-items-center tw-gap-2"
                  >
                    <v-text-field
                      v-model="phone.phone_number"
                      label="Número de Telefone"
                      variant="outlined"
                      density="comfortable"
                      v-maska
                      data-maska="(##) #####-####"
                      hide-details
                      prepend-inner-icon="solar:phone-bold-duotone"
                      class="tw-flex-grow"
                    />

                    <v-btn
                      color="error"
                      variant="tonal"
                      icon
                      @click="removePhoneNumber(index)"
                      size="small"
                    >
                      <Icon icon="solar:trash-bin-trash-bold-duotone" width="18" height="18" />
                    </v-btn>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>

          <v-divider class="tw-my-6 !tw-border-gray-200"></v-divider>

          <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
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

            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              rounded="lg"
              class="!tw-h-[48px] !tw-font-medium"
            >
              <div class="tw-flex tw-gap-3 tw-items-center">
                <Icon icon="solar:add-square-bold-duotone" width="22" height="22" />
                {{ user ? 'Atualizar' : 'Criar' }} Usuário
              </div>
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-form>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  user: {
    type: Object,
    default: null
  },
  roles: {
    type: Array,
    required: true
  },
  enterprises: {
    type: Array,
    required: true
  },
  userRoles: {
    type: Array,
    default: () => []
  },
  phoneNumbers: {
    type: Array,
    default: () => []
  }
})

// Inicializar o formulário
const form = useForm({
  name: props.user?.name ?? '',
  email: props.user?.email ?? '',
  password: '',
  password_confirmation: '',
  enterprise_id: props.user?.enterprise_id ?? null,
  roles: props.userRoles ?? [],
  phone_numbers: props.phoneNumbers?.length > 0
    ? props.phoneNumbers.map(phone => ({ phone_number: phone.phone_number }))
    : []
})

// Debugging
const devMode = ref(true); // Set to false in production

// Verificar se tem perfil de sistema selecionado
const isSystemRole = computed(() => {
  const systemRoles = ['master', 'gestor', 'colaborador']
  return form.roles.some(role => systemRoles.includes(role))
})

// Se for perfil de sistema, limpa o enterprise_id
watch(isSystemRole, (newValue) => {
  if (newValue) {
    form.enterprise_id = null
  }
})

// Verificar tipos de perfis selecionados e previnir valores inválidos
const checkRoleTypes = () => {
  // Certificar que apenas valores válidos estejam na lista
  form.roles = form.roles.filter(role => {
    return props.roles.some(r => r.name === role)
  })

  const systemRoles = ['master', 'gestor', 'colaborador']
  const hasSystemRole = form.roles.some(role => systemRoles.includes(role))

  if (hasSystemRole) {
    form.enterprise_id = null
  }
}

// Verificar valores iniciais
onMounted(() => {
  // Certificar que apenas valores válidos estejam na lista inicial
  form.roles = form.roles.filter(role => {
    return props.roles.some(r => r.name === role)
  })
})

// Adicionar um novo telefone
const addPhoneNumber = () => {
  form.phone_numbers.push({ phone_number: '' })
}

// Remover um telefone
const removePhoneNumber = (index) => {
  form.phone_numbers.splice(index, 1)
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

// Submeter o formulário
const submit = () => {
  if (props.user) {
    form.put(route('users.update', props.user.id))
  } else {
    form.post(route('users.store'))
  }
}
</script>

<style scoped>
.v-divider {
  opacity: 1;
}

:deep(.v-btn--icon.v-btn--density-default) {
  width: 38px;
  height: 38px;
}

:deep(.v-field__input) {
  padding-top: 6px !important;
  padding-bottom: 6px !important;
}

:deep(.v-field__prepend-inner) {
  padding-inline-start: 12px !important;
}

:deep(.v-label) {
  font-size: 0.875rem !important;
}
</style>
