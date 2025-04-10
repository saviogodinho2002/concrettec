<script setup>
import {computed, ref} from 'vue';
import NavItem from './NavItem/index.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Icon} from "@iconify/vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {menuItem} from "@/Layouts/NavItem/menu-item.js";

const props = defineProps({
    title: String,
})

const snackbar = ref(false)
const auth = usePage().props.auth
const sidebar = ref(true)
const raill = ref(true)

const menuItemsByPermissions = computed(() => {
    return menuItem
        .filter(item => {
            // Se não tiver permissões definidas, mostra o item
            if (!item.permissions || item.permissions.length === 0) return true;

            // Verifica se o usuário tem pelo menos uma das permissões necessárias
            return item.permissions.some(permission =>
                auth.user.permissions.includes(permission)
            );
        })
        .map(it => {
            let newItem = { ...it };
            newItem.to = route(newItem.to);
            return newItem;
        });
});

const snackbarColor = computed(() => {
    if (!!usePage().props.flash.success || !!usePage().props.flash.error) {
        return !!usePage().props.flash.success ? 'success' : 'error'
    }
    return 'surface'
})

function resetFlashSnackbar(value) {
    if (!value) {
        usePage().props.flash.success = null
        usePage().props.flash.error = null
    }
}

function notification() {
    if (!!usePage().props.flash.success || !!usePage().props.flash.error) {
        snackbar.value = true
    }
}

function logout() {
    router.post(route('logout'))
}
</script>

<template>
    <v-card flat>
        <v-app color="white">
            <v-navigation-drawer class="!tw-border-none" width="250">
                <div class="tw-flex tw-flex-col tw-justify-between tw-h-full">
                    <div class="tw-flex tw-flex-col">
                        <div class="py-5 tw-my-5 tw-w-full tw-flex tw-items-center tw-justify-center">
                            <ApplicationLogo class="tw-max-w-[50px] tw-p-1 bg-primary rounded-lg"/>
                            <span class="tw-text-[25px] tw-font-medium ml-4">Concrettec</span>
                        </div>
                        <v-divider></v-divider>
                        <v-list>
                            <template v-for="(item, i) in menuItemsByPermissions">
                                <NavItem :item="item" class="leftPadding"/>
                            </template>
                        </v-list>
                    </div>

                    <div>
                        <v-divider></v-divider>
                        <div class="tw-px-8 tw-py-4 tw-flex tw-w-full tw-cursor-pointer hover:!tw-bg-white/10">
                            <span class="icon-box py-2 tw-flex tw-items-center" @click="logout">
                                <Icon icon="solar:logout-2-bold" width="30" height="30" class="z-index-2"/>
                                <span class="tw-ml-8 tw-whitespace-nowrap tw-text-xl">Sair</span>
                            </span>
                        </div>
                    </div>
                </div>
            </v-navigation-drawer>

            <v-app-bar flat class="px-4">
                <v-row align="center" no-gutters>
                    <v-col>
                        <slot name="header"></slot>
                    </v-col>

                    <v-col cols="auto">
                        <v-row no-gutters align="center">
                            <!-- Notificação -->
                            <v-col class="mr-4">
                                <v-badge color="error" content="1" offset-x="3" offset-y="3">
                                    <v-btn icon variant="text">
                                        <Icon icon="solar:bell-bold-duotone" width="24" height="24"/>
                                    </v-btn>
                                </v-badge>
                            </v-col>

                            <!-- Perfil -->
                            <v-col>
                                <v-menu location="bottom end">
                                    <template v-slot:activator="{ props }">
                                        <v-btn variant="text" v-bind="props">
                                            <v-avatar size="35" class="mr-2">
                                                <v-img
                                                    :src="auth.user.avatar || 'https://randomuser.me/api/portraits/men/85.jpg'"></v-img>
                                            </v-avatar>
                                            <span class="mr-2">{{ auth.user.name }}</span>
                                            <span class="text-grey">{{ auth.user.roles[0] }}</span>
                                            <Icon icon="solar:alt-arrow-down-bold-duotone" width="20" height="20"/>
                                        </v-btn>
                                    </template>

                                    <v-list width="200" elevation="2">
                                        <v-list-item>
                                            <Link :href="route('profile.edit')" class="tw-text-gray-600">
                                                <v-list-item-title>Perfil</v-list-item-title>
                                            </Link>
                                        </v-list-item>
                                        <v-list-item @click="logout">
                                            <v-list-item-title>Sair</v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-app-bar>

            <v-main class="!tw-bg-white">
                <div class="hr-layout tw-h-full">
                    <v-container fluid class="page-wrapper tw-bg-[#FAFBFC] tw-h-full  rounded-xl">
                        <div class=" tw-h-full">
                            <div class="maxWidth tw-h-full">
                                <slot/>
                            </div>
                        </div>
                    </v-container>
                </div>
            </v-main>
        </v-app>
    </v-card>
</template>

