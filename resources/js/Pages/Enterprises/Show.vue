<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">Detalhes da Empresa</h2>
        <div class="tw-flex tw-gap-2">
          <v-btn
            v-if="$page.props.auth.user.permissions.includes('enterprise-edit')"
            color="primary"
            :to="route('enterprises.edit', enterprise.id)"
            prepend-icon="solar:pen-bold-duotone"
            class="!tw-font-medium"
          >
            Editar
          </v-btn>
          <v-btn
            color="secondary"
            variant="text"
            :to="route('enterprises.index')"
            prepend-icon="solar:arrow-left-bold-duotone"
            class="!tw-font-medium"
          >
            Voltar
          </v-btn>
        </div>
      </div>
    </template>

    <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100">
      <v-card-text>
        <v-row>
          <v-col cols="12" md="6">
            <v-list>
              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:buildings-3-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">Razão Social</v-list-item-title>
                <v-list-item-subtitle class="tw-font-medium">{{ enterprise.name }}</v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:buildings-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">Nome Fantasia</v-list-item-title>
                <v-list-item-subtitle class="tw-font-medium">{{ enterprise.fantasy_name }}</v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:document-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">CNPJ</v-list-item-title>
                <v-list-item-subtitle class="tw-font-medium">{{ enterprise.cnpj }}</v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-col>

          <v-col cols="12" md="6">
            <v-list>
              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:letter-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">Email</v-list-item-title>
                <v-list-item-subtitle class="tw-font-medium">{{ enterprise.email }}</v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:dollar-minimalistic-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">Preço CP</v-list-item-title>
                <v-list-item-subtitle class="tw-font-medium">
                  {{ enterprise.price_cp ? `R$ ${enterprise.price_cp}` : '-' }}
                </v-list-item-subtitle>
              </v-list-item>

              <v-list-item>
                <template v-slot:prepend>
                  <Icon icon="solar:buildings-bold-duotone" class="tw-mr-2 tw-text-xl" />
                </template>
                <v-list-item-title class="tw-text-gray-500 tw-text-sm">Tipo</v-list-item-title>
                <v-list-item-subtitle>
                  <v-chip
                    :color="enterprise.type === 'construcao' ? 'info' : 'success'"
                    size="small"
                  >
                    {{ enterprise.type === 'construcao' ? 'Construtora' : 'Concreteira' }}
                  </v-chip>
                </v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'

defineProps({
  enterprise: {
    type: Object,
    required: true
  }
})
</script> 