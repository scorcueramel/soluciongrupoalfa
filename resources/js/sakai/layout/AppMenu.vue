<script setup>
import {ref} from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
  {
    items: [
      {label: 'Panel', icon: 'pi pi-fw pi-home', to: '/dashboard'}
    ]
  },
  {
    label: 'Gestión de accesos',
    items: [
      {
        label: 'Accesos', icon: 'pi pi-fw pi-key',
        class: 'active-menuitem',
        items: [
          //cambiar "can : read user" por read habilitar formatos
          {label: 'Habilitar Formatos', icon: 'pi pi-fw pi-file-edit', to: '/formatos', can: 'read user'},
          {label: 'Usuarios', icon: 'pi pi-fw pi-user', to: '/user', can: 'read user'},
          {label: 'Roles', icon: 'pi pi-fw pi-id-card', to: '/role', can: 'read role'},
          {label: 'Permisos',icon: 'pi pi-fw pi-mobile',to: '/permission',class: 'rotated-icon',can: 'read permission'},
        ]
      }
    ]
  },
  {
    items: [
      {label: 'Evaluados', icon: 'pi pi-fw pi-users', to: '/dashboard'}
    ]
  },
  {
    items: [
      {
        label: 'Formatos', icon: 'pi pi-fw pi-folder-open',
        class: 'active-menuitem',
        items: [
          //cambiar "can : read user" por read habilitar formatos
          {label: 'Formatos 01', icon: 'pi pi-fw pi-file', to: '/formatos', can: 'read user'},
          {label: 'Formatos 02', icon: 'pi pi-fw pi-file', to: '/formatos', can: 'read user'},
          {label: 'Informes Finales', icon: 'pi pi-fw pi-file', to: '/formatos', can: 'read user'},
        ]
      }
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
