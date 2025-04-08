<script setup>
import { Icon } from '@iconify/vue';
import {Link, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
const props = defineProps({ item: Object});

const active = computed(()=>{
    return route().current(props.item.route)
})


</script>

<template>
    <!---Single Item-->
    <div class="mb-1 tw-mx-3 ">
        <Link :href="item.to" class="tw-flex tw-rounded-[16px] tw-px-5 tw-py-2  tw-w-full" :class="active ? 'tw-bg-primary ' :'' ">
            <span class="icon-box py-2 tw-flex tw-items-center tw-text-textDefault" :class="active ? 'tw-text-white' : ''">
                <Icon  :icon="item.icon" height="30" width="30"  class="z-index-2 hidden-sm-and-down" />
               <span class="tw-ml-8 tw-whitespace-nowrap"> {{item.title}}</span>
            </span>
        </Link>
    </div>
</template>
