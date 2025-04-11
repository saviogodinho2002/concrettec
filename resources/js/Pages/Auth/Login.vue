<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
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
                        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Entre em sua conta</h1>
                        <p class="tw-mt-2 tw-text-gray-600">Bem-vindo de volta! Digite suas credenciais abaixo</p>
                    </div>

                    <!-- Formulário -->
                    <form @submit.prevent="submit" class="tw-space-y-4">
                        <v-text-field
                            v-model="form.email"
                            label="E-mail"
                            type="email"
                            required
                            :error-messages="form.errors.email"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="solar:letter-bold-duotone"
                            rounded="lg"
                            hide-details
                        />

                        <v-text-field
                            v-model="form.password"
                            label="Senha"
                            type="password"
                            required
                            :error-messages="form.errors.password"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="solar:lock-password-bold-duotone"
                            rounded="lg"
                            hide-details
                        />

                        <div class="tw-flex tw-items-center tw-justify-between">
                            <v-checkbox
                                v-model="form.remember"
                                label="Lembrar-me"
                                color="success"
                                density="comfortable"
                                hide-details
                            />

                            <Link
                                v-if="route().has('password.request')"
                                :href="route('password.request')"
                                class="tw-text-sm tw-text-emerald-600 hover:tw-text-emerald-500"
                            >
                                Esqueceu a senha?
                            </Link>
                        </div>

                        <v-btn
                            :loading="form.processing"
                            :disabled="form.processing"
                            color="success"
                            type="submit"
                            block
                            rounded="lg"
                            class="!tw-h-[48px] !tw-text-base !tw-font-medium"
                        >
                            Entrar
                        </v-btn>

                        <p class="tw-text-center tw-text-sm tw-text-gray-600 tw-mt-6">
                            Não tem uma conta?
                            <Link 
                                :href="route('register')" 
                                class="tw-font-medium tw-text-emerald-600 hover:tw-text-emerald-500"
                            >
                                Criar conta
                            </Link>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Lado Direito - Banner -->
            <div class="tw-hidden lg:tw-block lg:tw-w-1/2 tw-bg-gradient-to-br tw-from-emerald-800 tw-to-emerald-600 tw-relative">
                <div class="tw-h-full tw-flex tw-flex-col tw-justify-center tw-items-center tw-p-12">
                    <div class="tw-max-w-lg tw-text-center">
                        <h2 class="tw-text-3xl tw-font-bold tw-mb-4 tw-text-white">Gestão Inteligente na Construção</h2>
                        <p class="tw-text-emerald-100 tw-mb-12">Conectando obras, equipes e processos com IA e automação avançada</p>
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

:deep(.v-checkbox) {
    margin: 0;
    padding: 0;
}

:deep(.v-checkbox .v-selection-control) {
    min-height: auto;
}

:deep(.v-checkbox .v-selection-control__wrapper) {
    margin-right: 8px;
}

:deep(.v-checkbox .v-selection-control__input) {
    opacity: 1 !important;
}

:deep(.v-checkbox .v-selection-control__input > .v-icon) {
    opacity: 1 !important;
    font-size: 18px;
}

:deep(.v-checkbox .v-selection-control__input::before) {
    border-width: 2px;
    border-style: solid;
    border-color: rgba(0, 0, 0, 0.38);
    border-radius: 2px;
    background: white;
}

:deep(.v-checkbox--selected .v-selection-control__input::before) {
    border-color: rgb(22 163 74) !important;
    background: rgb(22 163 74) !important;
}
</style>
