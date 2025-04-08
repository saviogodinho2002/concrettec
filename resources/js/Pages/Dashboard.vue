<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout >
        <template #header>
            <span class="tw-text-[25px] tw-font-bold ml-8">Dashboard</span>
        </template>

        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-7xl tw-sm:tw-px-6 tw-lg:tw-px-8">
                <div
                    class="tw-overflow-hidden tw-bg-white tw-shadow-sm tw-sm:tw-rounded-lg"
                >
                    <div class="tw-p-6 tw-text-gray-900">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
