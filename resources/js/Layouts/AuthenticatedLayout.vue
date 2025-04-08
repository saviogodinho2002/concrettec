<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import NavItem from './NavItem/index.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Icon} from "@iconify/vue";
import {router, usePage} from "@inertiajs/vue3";
import {menuItem} from "@/Layouts/NavItem/menu-item.js";

const props = defineProps({
    title:String,
})



const snackbar = ref(false)
const auth = usePage().props.auth
const sidebar = ref(true)
const raill = ref(true)
const menuItemsByRoles = computed(() => {
    return menuItem.map(it => {
        let newItem = { ...it };
        newItem.to = route(newItem.to);
        return newItem;
    });
});

const snackbarColor = computed(()=>{
    if(!!usePage().props.flash.success || !!usePage().props.flash.error){
        return !!usePage().props.flash.success ? 'success' : 'error'
    }
    return 'surface'
})

function resetFlashSnackbar(value){
    if(!value){
        usePage().props.flash.success =  null
        usePage().props.flash.error =  null
    }
}

function notification(){
    if(!!usePage().props.flash.success || !!usePage().props.flash.error){
        snackbar.value = true
    }
}

// watch(
//     ()=> usePage().props.flash.success ||  usePage().props.flash.error ,
//     ()=>{
//         notification()
//     }
// )

onMounted(()=>{
    // notification()
})

function logout(){
    router.post(route('logout'))
}
</script>

<template>
    <v-card flat>
        <v-app color="white">
            <v-navigation-drawer
                class="!tw-border-none "
                width="250"
                
            >
                <div class="tw-flex    tw-flex-col tw-justify-between  tw-h-full  ">
                    <div class="tw-flex tw-flex-col ">
                        <div class="py-5 tw-my-5 tw-w-full tw-flex tw-items-center tw-justify-center ">
                            <ApplicationLogo class="tw-max-w-[50px] tw-p-1 bg-primary rounded-lg" />
                            <span class="tw-text-[25px] tw-font-medium ml-4">Concrettec</span>
                        </div>
                        <v-divider></v-divider>
                        <v-list>
                            <template v-for="(item, i) in menuItemsByRoles">
                                <NavItem :item="item"  class="leftPadding" />
                            </template>
                        </v-list>
                    </div>

                    <div>
                        <v-divider></v-divider>
                        <div class="  tw-px-8 tw-py-4 tw-flex   tw-w-full  tw-cursor-pointer  hover:!tw-bg-white/10 ">
                          <span class="icon-box py-2 tw-flex tw-items-center">
                                <Icon @click="logout" icon="solar:logout-2-bold" height="30" width="30"  class=" z-index-2 " />
                                <span class="tw-ml-8 tw-whitespace-nowrap tw-text-xl"> Sair</span>
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
                                <v-badge
                                    color="error"
                                    content="1"
                                    offset-x="3"
                                    offset-y="3"
                                >
                                    <v-btn icon variant="text">
                                        <v-icon>mdi-bell-outline</v-icon>
                                    </v-btn>
                                </v-badge>
                            </v-col>

                            <!-- Perfil -->
                            <v-col>
                                <v-menu location="bottom end">
                                    <template v-slot:activator="{ props }">
                                        <v-btn
                                            variant="text"
                                            v-bind="props"
                                        >
                                            <v-avatar size="35" class="mr-2">
                                                <v-img :src="auth.user.avatar || 'https://randomuser.me/api/portraits/men/85.jpg'"></v-img>
                                            </v-avatar>
                                            <span class="mr-2">{{ auth.user.name }}</span>
                                            <span class="text-grey">{{ auth.user.role || 'Admin' }}</span>
                                            <v-icon>mdi-chevron-down</v-icon>
                                        </v-btn>
                                    </template>

                                    <v-list width="200" elevation="2">
                                        <v-list-item @click="router.get(route('profile.edit'))">
                                            <v-list-item-title>Perfil</v-list-item-title>
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
                <div class=" hr-layout tw-h-full ">
                    <v-container fluid class="page-wrapper tw-bg-[#FAFBFC] tw-h-full pt-2 rounded-xl ">
                        <div class="px-sm-3 pt-3 tw-h-full">
                            <div class=" maxWidth tw-h-full">
                                <slot/>
                            </div>
                        </div>

                    </v-container>
                </div>
            </v-main>
        </v-app>
    
       
    </v-card>

</template>

