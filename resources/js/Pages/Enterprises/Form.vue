<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="tw-flex tw-justify-between tw-items-center">
        <h2 class="tw-text-2xl tw-font-bold">{{ enterprise ? 'Editar' : 'Nova' }} Empresa</h2>
      </div>
    </template>

    <v-form @submit.prevent="submit">
      <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 rounded-xl">
        <v-card-text>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.name"
                label="Razão Social"
                :error-messages="form.errors.name"
                variant="outlined"
                density="comfortable"
                required
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.fantasy_name"
                label="Nome Fantasia"
                :error-messages="form.errors.fantasy_name"
                variant="outlined"
                density="comfortable"
                required
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-maska:[cnpjOptions]
                v-model="form.cnpj"
                label="CNPJ"
                :error-messages="form.errors.cnpj"
                variant="outlined"
                density="comfortable"
                maxlength="18"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.email"
                label="Email"
                type="email"
                :error-messages="form.errors.email"
                variant="outlined"
                density="comfortable"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                v-model="form.type"
                label="Tipo"
                :items="[
                  { title: 'Construtora', value: 'construcao' },
                  { title: 'Concreteira', value: 'concreteira' }
                ]"
                @update:model-value="form.price_cp = 0"
                item-title="title"
                item-value="value"
                :error-messages="form.errors.type"
                variant="outlined"
                density="comfortable"
                required
              />
            </v-col>

            <v-col cols="12" md="6" v-if="form.type === 'construcao'">
              <v-text-field
                v-model="form.price_cp"
                label="Preço CP"
                type="number"
                step="0.01"
                :error-messages="form.errors.price_cp"
                variant="outlined"
                density="comfortable"
                prefix="R$"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-maska:[phoneOptions]
                v-model="form.phone"
                label="Telefone"
                :error-messages="form.errors.phone"
                variant="outlined"
                density="comfortable"
                required
              />
            </v-col>
          </v-row>

          <v-divider thickness="1" class="tw-my-6"></v-divider>

          <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
            <Link
              :href="route('enterprises.index')"
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
                {{ enterprise ? 'Atualizar' : 'Criar' }} Empresa
              </div>
            </v-btn>
          </div>

        </v-card-text>
      </v-card>
    </v-form>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { vMaska } from 'maska'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Icon } from '@iconify/vue'
import { ref } from 'vue'

const props = defineProps({
  enterprise: {
    type: Object,
    default: null
  }
})

const cnpjOptions = { mask: '##.###.###/####-##' }
const phoneOptions = { mask: '(##) #####-####' }

const form = useForm({
  name: props.enterprise?.name ?? '',
  fantasy_name: props.enterprise?.fantasy_name ?? '',
  cnpj: props.enterprise?.cnpj ?? '',
  email: props.enterprise?.email ?? '',
  price_cp: props.enterprise?.price_cp ?? '',
  type: props.enterprise?.type ?? 'construcao',
  phone: props.enterprise?.phone ?? '',
})

const submit = () => {
  if (props.enterprise) {
    form.put(route('enterprises.update', props.enterprise.id))
  } else {
    form.post(route('enterprises.store'))
  }
}
</script>
