<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.current_password) {
                form.reset('current_password');
            }
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
        },
    });
};
</script>

<template>
    <section>
        <form @submit.prevent="updatePassword" class="tw-space-y-6">
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        v-model="form.current_password"
                        label="Senha Atual"
                        type="password"
                        :error-messages="form.errors.current_password"
                        variant="outlined"
                        density="comfortable"
                        required
                        autocomplete="current-password"
                        prepend-inner-icon="solar:lock-bold-duotone"
                        hide-details="auto"
                    />
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.password"
                        label="Nova Senha"
                        type="password"
                        :error-messages="form.errors.password"
                        variant="outlined"
                        density="comfortable"
                        required
                        autocomplete="new-password"
                        prepend-inner-icon="solar:lock-password-bold-duotone"
                        hide-details="auto"
                    />
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.password_confirmation"
                        label="Confirmar Nova Senha"
                        type="password"
                        :error-messages="form.errors.password_confirmation"
                        variant="outlined"
                        density="comfortable"
                        required
                        autocomplete="new-password"
                        prepend-inner-icon="solar:lock-check-bold-duotone"
                        hide-details="auto"
                    />
                </v-col>
            </v-row>

            <div class="tw-flex tw-items-center tw-gap-4">
                <v-btn
                    color="success"
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                    prepend-icon="solar:diskette-bold-duotone"
                    rounded="lg"
                >
                    Atualizar Senha
                </v-btn>

                <v-snackbar
                    v-model="form.recentlySuccessful"
                    timeout="2000"
                    color="success"
                    location="top"
                >
                    Senha atualizada com sucesso!
                </v-snackbar>
            </div>
        </form>
    </section>
</template>
