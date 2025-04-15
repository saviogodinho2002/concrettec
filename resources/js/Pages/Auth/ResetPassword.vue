<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Redefinir Senha" />
    
    <div class="tw-min-h-screen tw-bg-gray-50 tw-flex tw-items-center tw-justify-center tw-p-4">
        <div class="tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-100 tw-w-full tw-max-w-6xl tw-flex tw-overflow-hidden">
            <!-- Lado Esquerdo - Formulário -->
            <div class="tw-w-full lg:tw-w-1/2 tw-p-8 lg:tw-p-12">
                <div class="tw-max-w-md tw-mx-auto">
                    <!-- Logo -->
                    <div class="tw-mb-8">
                        <ApplicationLogo class="tw-max-w-[50px] tw-p-1 bg-primary rounded-lg"/>
                    </div>

                    <!-- Título e Subtítulo -->
                    <div class="tw-mb-8">
                        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Redefinir sua senha</h1>
                        <p class="tw-mt-2 tw-text-gray-600">Crie uma nova senha segura para sua conta</p>
                    </div>

                    <!-- Formulário -->
                    <form @submit.prevent="submit" class="tw-space-y-4">
                        <v-text-field
                            v-model="form.email"
                            label="E-mail"
                            type="email"
                            :error-messages="form.errors.email"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="solar:letter-bold-duotone"
                            rounded="lg"
                            readonly
                            hide-details
                        />

                        <v-text-field
                            v-model="form.password"
                            label="Nova Senha"
                            type="password"
                            required
                            :error-messages="form.errors.password"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="solar:lock-password-bold-duotone"
                            rounded="lg"
                            hide-details
                            autofocus
                        />

                        <v-text-field
                            v-model="form.password_confirmation"
                            label="Confirmar Nova Senha"
                            type="password"
                            required
                            :error-messages="form.errors.password_confirmation"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="solar:lock-check-bold-duotone"
                            rounded="lg"
                            hide-details
                        />

                        <v-btn
                            :loading="form.processing"
                            :disabled="form.processing"
                            color="success"
                            type="submit"
                            block
                            rounded="lg"
                            class="!tw-h-[48px] !tw-text-base !tw-font-medium !tw-mt-6"
                        >
                            Redefinir Senha
                        </v-btn>
                    </form>
                </div>
            </div>

            <!-- Lado Direito - Banner -->
            <div class="tw-hidden lg:tw-block lg:tw-w-1/2 tw-bg-gradient-to-br tw-from-emerald-800 tw-to-emerald-600 tw-relative">
                <div class="tw-h-full tw-flex tw-flex-col tw-justify-center tw-items-center tw-p-12">
                    <div class="tw-max-w-lg tw-text-center">
                        <h2 class="tw-text-3xl tw-font-bold tw-mb-4 tw-text-white">Recupere seu acesso</h2>
                        <p class="tw-text-emerald-100 tw-mb-12">Crie uma nova senha segura para retornar ao sistema de gestão</p>
                    </div>

                    <!-- Ilustração -->
                    <div class="tw-flex tw-justify-center tw-items-center tw-w-full">
                        <img src="/images/Construction costs-amico.svg" alt="Ilustração do Sistema" class="tw-w-full tw-max-w-[500px]" />
                    </div>

                    <div class="tw-mt-12 tw-text-center">
                        <p class="tw-text-emerald-100 tw-text-sm">Agendamentos inteligentes • Análise preditiva • Gestão automatizada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.v-field {
    background-color: transparent !important;
}

:deep(.v-text-field .v-field__input) {
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
