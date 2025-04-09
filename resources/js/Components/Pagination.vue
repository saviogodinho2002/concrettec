<template>
  <div v-if="links.length > 3" class="tw-flex tw-justify-center tw-gap-1">
    <template v-for="(link, key) in links" :key="key">
      <div
        v-if="link.url === null"
        class="tw-px-4 tw-py-2 tw-text-sm tw-text-gray-500 tw-bg-white tw-border tw-border-gray-300 tw-rounded-md"
        v-html="link.label"
      />
      <Link
        v-else
        :href="link.url"
        class="tw-px-4 tw-py-2 tw-text-sm tw-bg-white tw-border tw-border-gray-300 tw-rounded-md"
        :class="{
          'tw-text-primary hover:tw-text-primary-dark': !link.active,
          'tw-text-white tw-bg-primary hover:tw-bg-primary-dark': link.active,
        }"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    required: true
  }
})
</script> 