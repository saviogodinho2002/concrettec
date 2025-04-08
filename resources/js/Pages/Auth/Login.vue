<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

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
    <VContainer class="fill-height d-flex align-center justify-center" >
        <VCard variant="outlined" width="700" elevation="0" style="border-radius: 16px; overflow: hidden;">
            <VCardText class="pa-6">
                <VForm @submit.prevent="submit" >
                    <VTextField
                        v-model="form.email"
                        label="Email"
                        type="email"
                        required
                        :error-messages="form.errors.email"
                        variant="outlined"
                        dense
                        class="mb-4"
                    />

                    <VTextField
                        v-model="form.password"
                        label="Password"
                        type="password"
                        required
                        :error-messages="form.errors.password"
                        variant="outlined"
                        dense
                        class="mb-4"
                    />

                    
                    <VCheckbox
                        v-model="form.remember"
                        label="Remember me"
                        class="mb-4"
                    />
                </VForm>
            </VCardText>

            <VCardActions class="d-flex justify-end pa-4">
                <VBtn
                    :loading="form.processing"
                    :disabled="form.processing"
                    color="primary"
                    @click="submit"
                    class="elevation-1"
                    style="border-radius: 8px;"
                >
                    Log in
                </VBtn>
            </VCardActions>
        </VCard>
    </VContainer>
</template>
