<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">{{ user ? 'Editar' : 'Novo' }} Usuário</h2>
        <Link
          :href="route('users.index')"
          class="tw-text-gray-600 hover:tw-text-primary tw-flex tw-items-center tw-gap-2"
        >
          <Icon icon="solar:arrow-left-bold-duotone" width="22" height="22" />
          <span>Voltar</span>
        </Link>
      </div>
    </template>

    <v-form @submit.prevent="submit">
      <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
        <v-card-text>
          <v-row>
            <!-- Dados básicos -->
            <v-col cols="12">
              <h3 class="tw-text-lg tw-font-medium tw-mb-4">Dados do Usuário</h3>
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
              />
            </v-col>

            <!-- Reorganização dos perfis e telefones (lado a lado) -->
            <v-col cols="12">
              <h3 class="tw-text-lg tw-font-medium tw-mb-4">Perfis e Contatos</h3>
            </v-col>

            <v-col cols="12" md="6">
              <h4 class="tw-font-medium tw-mb-3">Perfis do Usuário</h4>
              <div class="tw-flex tw-flex-wrap tw-gap-3">
                  <div
                      v-for="role in roles"
                      :key="role.id"
                      class="tw-flex-basis-[45%] tw-mb-3 tw-p-2 tw-bg-gray-50 tw-rounded-md tw-min-h-[56px]"
                  >
                    <v-checkbox
                        v-model="form.roles"
                        :label="formatRoleName(role.name)"
                        :value="role.name"
                        color="secondary"
                        density="comfortable"
                        class="tw-w-full"
                        @update:model-value="checkRoleTypes"
                    />
</div>
              </div>

              <div class="tw-mt-3 tw-text-sm tw-p-2 tw-bg-blue-50 tw-rounded-md" v-if="devMode">
                <div> Roles selecionadas: {{ form.roles.join(', ') }}</div>
              </div>

              <div v-if="form.errors.roles" class="tw-text-red-500 tw-mt-2 tw-text-sm">
                {{ form.errors.roles }}
              </div>
            </v-col>

            <v-col cols="12" md="6">
              <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                <h4 class="tw-font-medium">Telefones</h4>
                <v-btn
                  color="primary"
                  variant="text"
                  @click="addPhoneNumber"
                  class="!tw-font-medium !tw-flex !tw-items-center !tw-gap-1 !tw-px-2 !tw-py-1"
                  size="small"
                >
                  <Icon icon="solar:add-circle-bold-duotone" width="16" height="16" />
                  <span>Adicionar</span>
                </v-btn>
              </div>

              <div v-if="form.phone_numbers.length === 0" class="tw-text-gray-500 tw-mb-3 tw-text-sm tw-px-1">
                Nenhum telefone cadastrado
              </div>

              <div v-for="(phone, index) in form.phone_numbers" :key="index" class="tw-flex tw-items-center tw-gap-2 tw-mb-3">
                <v-text-field
                  v-model="phone.phone_number"
                  label="Número de Telefone"
                  variant="outlined"
                  density="comfortable"
                  v-maska
                  data-maska="(##) #####-####"
                  hide-details
                  class="tw-flex-grow"
                />

                <v-btn
                  color="error"
                  variant="text"
                  icon
                  @click="removePhoneNumber(index)"
                  size="small"
                  class="!tw-mt-0"
                >
                  <Icon icon="solar:trash-bin-trash-bold-duotone" width="18" height="18" />
                </v-btn>
              </div>
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions class="tw-p-4">
          <v-spacer />
          <v-btn
            color="primary"
            type="submit"
            :loading="form.processing"
            class="!tw-font-medium tw-flex tw-items-center tw-gap-2"
          >
            <Icon icon="solar:disk-bold-duotone" width="22" height="22" />
            {{ user ? 'Atualizar' : 'Criar' }}
          </v-btn>
        </v-card-actions>
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
console.log('Available roles:', props.roles)
console.log('Initial roles selected:', props.userRoles)

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
  console.log('Roles after change:', form.roles)

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
