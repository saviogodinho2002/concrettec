<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Meu Perfil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="tw-flex tw-justify-between tw-items-center">
                <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800">Meu Perfil</h2>
            </div>
        </template>

        <div class="tw-py-6">
            <div class="tw-max-w-5xl tw-mx-auto tw-space-y-6">
                <!-- Informações de Perfil -->
                <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 tw-rounded-xl">
                    <v-card-text class="!tw-p-6">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
                            <Icon icon="solar:user-id-bold-duotone" class="tw-text-emerald-600" width="24" height="24" />
                            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Informações Pessoais</h3>
                        </div>
                        
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </v-card-text>
                </v-card>

                <!-- Atualização de Senha -->
                <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 tw-rounded-xl">
                    <v-card-text class="!tw-p-6">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
                            <Icon icon="solar:lock-password-bold-duotone" class="tw-text-emerald-600" width="24" height="24" />
                            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Alterar Senha</h3>
                        </div>
                        
                        <UpdatePasswordForm />
                    </v-card-text>
                </v-card>

                <!-- Exclusão de Conta -->
                <v-card class="!tw-shadow-sm !tw-border !tw-border-gray-100 tw-rounded-xl">
                    <v-card-text class="!tw-p-6">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-mb-6">
                            <Icon icon="solar:shield-warning-bold-duotone" class="tw-text-red-500" width="24" height="24" />
                            <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">Área de Perigo</h3>
                        </div>
                        
                        <DeleteUserForm />
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
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

:deep(.v-btn--icon.v-btn--density-default) {
    width: 38px;
    height: 38px;
}
</style>
