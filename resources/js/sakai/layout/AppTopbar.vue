<script setup>
import { useLayout } from "@/sakai/layout/composables/layout";
import AppConfigurator from "./AppConfigurator.vue";
import NavLink from "@/Components/NavLink.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { usePage } from "@inertiajs/vue3";

const { onMenuToggle, toggleDarkMode, isDarkTheme } = useLayout();

const username = `${usePage().props.auth.user.name} ${usePage().props.auth.user.apellido_paterno} ${usePage().props.auth.user.apellido_materno}`;
</script>

<template>
  <div class="layout-topbar">
    <div class="layout-topbar-logo-container">
      <button
        class="layout-menu-button layout-topbar-action"
        @click="onMenuToggle"
      >
        <i class="pi pi-bars"></i>
      </button>
      <nav-link href="/dashboard" class="layout-topbar-logo ms-2">
        <img
          src="/demo/images/logosmall.png"
          width="170"
          alt="logo-grupo-alfa"
        />
      </nav-link>
    </div>

    <div class="layout-topbar-actions">
      <button
        class="layout-topbar-menu-button layout-topbar-action"
        v-styleclass="{
          selector: '@next',
          enterFromClass: 'hidden',
          enterActiveClass: 'animate-scalein',
          leaveToClass: 'hidden',
          leaveActiveClass: 'animate-fadeout',
          hideOnOutsideClick: true,
        }"
      >
        <i class="pi pi-ellipsis-v"></i>
      </button>

      <div class="layout-topbar-menu hidden lg:block">
        <div class="layout-topbar-menu-content">
          <div class="relative">
            <button
              type="button"
              class="layout-topbar-action"
              v-styleclass="{
                selector: '@next',
                enterFromClass: 'hidden',
                enterActiveClass: 'animate-scalein',
                leaveToClass: 'hidden',
                leaveActiveClass: 'animate-fadeout',
                hideOnOutsideClick: true,
              }"
            >
              <i class="pi pi-user"></i>
              <span>Profile</span>
            </button>
            <div
              class="hidden bg-white shadow-md absolute right-0 mt-2 w-52 py-2 rounded-md"
            >
              <ul class="px-4">
                <li><small>{{username}}</small></li>
              </ul>
              <DropdownLink :href="route('logout')" method="post" as="button">
                Log Out
              </DropdownLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
