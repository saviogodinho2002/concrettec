<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

const confirmingUserDeletion = ref(false);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <div class="tw-bg-red-50 tw-p-4 tw-rounded-lg tw-border tw-border-red-100">
            <p class="tw-text-sm tw-text-red-800 tw-mb-4">
                Ao excluir sua conta, todos os seus dados serão permanentemente removidos. Antes de prosseguir, 
                faça o download de quaisquer dados ou informações que deseja manter.
            </p>

            <v-btn
                color="error"
                variant="tonal"
                @click="confirmUserDeletion"
                prepend-icon="solar:trash-bin-trash-bold-duotone"
                rounded="lg"
            >
                Excluir Conta
            </v-btn>
        </div>

        <v-dialog v-model="confirmingUserDeletion" max-width="500" persistent>
            <v-card>
                <v-card-title class="tw-bg-red-50 tw-text-red-800 tw-py-4">
                    <Icon icon="solar:danger-triangle-bold-duotone" class="tw-mr-2" width="24" height="24" />
                    Confirmação de Exclusão
                </v-card-title>

                <v-card-text class="tw-py-5">
                    <p class="tw-text-gray-700 tw-mb-4">
                        Tem certeza de que deseja excluir sua conta? Esta ação é irreversível e todos os seus dados serão permanentemente excluídos.
                    </p>

                    <p class="tw-font-medium tw-text-gray-700 tw-mb-2">Digite sua senha para confirmar:</p>

                    <v-text-field
                        v-model="form.password"
                        label="Senha"
                        type="password"
                        :error-messages="form.errors.password"
                        variant="outlined"
                        density="comfortable"
                        autofocus
                        placeholder="Digite sua senha atual"
                        prepend-inner-icon="solar:lock-password-bold-duotone"
                        @keyup.enter="deleteUser"
                    />
                </v-card-text>

                <v-card-actions class="tw-px-4 tw-pb-4">
                    <v-spacer></v-spacer>
                    
                    <v-btn
                        color="gray"
                        variant="tonal"
                        @click="closeModal"
                        class="tw-mr-2"
                    >
                        Cancelar
                    </v-btn>

                    <v-btn
                        color="error"
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Confirmar Exclusão
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </section>
</template>
