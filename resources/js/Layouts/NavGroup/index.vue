<script setup>
const props = defineProps({ item: Object });
</script>

<template>
    <v-list-subheader  class="smallCap text-capitalize text-subtitle-1 mt-7 d-flex align-items-center">
        <span class="mini-icon"></span>
       <span class="mini-text font-weight-semibold p-0 text-medium-emphasis text-uppercase text-body-2">{{ item.header }}</span>
    </v-list-subheader>
</template>
