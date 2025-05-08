<script setup>
import {ref} from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
  {
    items: [
      {label: 'Panel', icon: 'pi pi-fw pi-home', to: '/dashboard'},
      {label: 'Habilitar Formato', icon: 'pi pi-fw pi-file-edit', to: '/formatos', can: 'read formato'},
    ]
  },
  {
    items: [
      {label: 'Evaluados', icon: 'pi pi-fw pi-users', to: '/evaluados', can: 'read evaluado'},
      {label: 'Empresas', icon: 'pi pi-fw pi-building', to: '/empresas', can: 'read empresa'},
      {label: 'Cargos', icon: 'pi pi-fw pi-sitemap', to: '/cargos', can: 'read cargo'},
      {label: 'Usuarios', icon: 'pi pi-fw pi-user', to: '/user', can: 'read user'},
      {label: 'Roles', icon: 'pi pi-fw pi-id-card', to: '/role', can: 'read role'},
      {label: 'Permisos', icon: 'pi pi-fw pi-mobile', to: '/permission', can: 'read permission'},
    ]
  },
]);
</script>

<template>
  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style lang="scss" scoped></style>
<!--<hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">-->
