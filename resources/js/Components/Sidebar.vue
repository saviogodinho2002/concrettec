<template>
  <v-navigation-drawer>
    <v-list>
      <v-list-item
        prepend-icon="mdi-view-dashboard"
        title="Dashboard"
        :to="route('dashboard')"
        :active="route().current('dashboard')"
      />

      <v-list-item
        v-if="$page.props.auth.user.permissions.includes('enterprise-list')"
        prepend-icon="mdi-domain"
        title="Empresas"
        :to="route('enterprises.index')"
        :active="route().current('enterprises.*')"
      />

      <!-- Outros itens do menu -->
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
// Não precisa de lógica adicional
</script> 