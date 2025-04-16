<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <form @submit.prevent="form.patch(route('profile.update'))" class="tw-space-y-6">
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.name"
                        label="Nome"
                        type="text"
                        :error-messages="form.errors.name"
                        variant="outlined"
                        density="comfortable"
                        required
                        autofocus
                        autocomplete="name"
                        prepend-inner-icon="solar:user-bold-duotone"
                        hide-details="auto"
                    />
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.email"
                        label="E-mail"
                        type="email"
                        :error-messages="form.errors.email"
                        variant="outlined"
                        density="comfortable"
                        required
                        autocomplete="email"
                        prepend-inner-icon="solar:letter-bold-duotone"
                        hide-details="auto"
                    />
                </v-col>
            </v-row>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="tw-rounded-lg tw-bg-blue-50 tw-p-4 tw-mt-4">
                <div class="tw-flex tw-items-start tw-gap-3">
                    <Icon icon="solar:info-circle-bold-duotone" class="tw-text-blue-500 tw-mt-0.5" width="20" height="20" />
                    <div>
                        <p class="tw-text-sm tw-text-blue-800">
                            Seu endereço de e-mail não foi verificado.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="tw-font-medium tw-text-blue-700 hover:tw-text-blue-800 tw-underline"
                            >
                                Clique aqui para reenviar o e-mail de verificação.
                            </Link>
                        </p>

                        <div
                            v-show="status === 'verification-link-sent'"
                            class="tw-mt-2 tw-text-sm tw-font-medium tw-text-blue-700"
                        >
                            Um novo link de verificação foi enviado para seu endereço de e-mail.
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4">
                <v-btn
                    color="success"
                    type="submit"
                    :loading="form.processing"
                    :disabled="form.processing"
                    prepend-icon="solar:diskette-bold-duotone"
                    rounded="lg"
                >
                    Salvar
                </v-btn>

                <v-snackbar
                    v-model="form.recentlySuccessful"
                    timeout="2000"
                    color="success"
                    location="top"
                >
                    Informações atualizadas com sucesso!
                </v-snackbar>
            </div>
        </form>
    </section>
</template>
