<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";
import {usePage, useForm, Head} from '@inertiajs/vue3';

import {reactive, ref, watch, computed} from "vue";
import pkg from "lodash";
import {router} from "@inertiajs/vue3";

const {_, debounce, pickBy} = pkg;
import {loadToast} from '@/composables/loadToast';

import Card from 'primevue/card';
import Create from "@/Pages/Cargo/Create.vue";
import Edit from "@/Pages/Cargo/Edit.vue";

const props = defineProps({
  filters: Object,
  cargos: Object,
  perPage: Number,
});

loadToast();

const deleteDialog = ref(false);
const form = useForm({});

const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
    // perPage: props.perPage,
    createOpen: false,
    editOpen: false,
  },
  cargo: null,
});

const deleteData = () => {
  deleteDialog.value = false;

  form.delete(route("cargos.destroy", data.cargo?.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
}

const onPageChange = (event) => {
  router.get(route('cargos.index'), {page: event.page + 1}, {preserveState: true});
};

watch(
  () => _.cloneDeep(data.params),
  debounce(() => {
    let params = pickBy(data.params);
    router.get(route("cargos.index"), params, {
      replace: true,
      preserveState: true,
      preserveScroll: true,
    });
  }, 150)
);
const limpiarBuscador = () => {
  data.params.search = "";
}
</script>

<template>
  <app-layout>
    <Head title="Empresas"/>

    <Card class="mb-8">
      <template #content>
        <div class="flex flex-wrap justify-between items-center">
          <h2 class="text-2xl font-bold">GESTIÓN DE CARGOS</h2>
          <Button v-show="can(['create cargo'])" label="Nuevo Cargo" @click="data.createOpen = true" icon="pi pi-plus"/>
        </div>
      </template>
    </Card>

    <div class="card">
      <Create
        :show="data.createOpen"
        title="Cargo"
        @close="data.createOpen = false"
      />
      <Edit
        :show="data.editOpen"
        title="Cargo"
        @close="data.editOpen = false"
        :cargo="data.cargo"
      />

      <DataTable
        lazy
        :value="cargos.data"
        paginator
        :rows="cargos.per_page"
        :totalRecords="cargos.total"
        :first="(cargos.current_page - 1) * cargos.per_page"
        @page="onPageChange"
        tableStyle="min-width: 50rem"
        stripedRows
      >
        <template #header>
          <div class="flex justify-end">
            <div class="flex w-1/3 h-10">
              <InputGroup>
                <InputGroupAddon>
                  <i class="pi pi-search"/>
                </InputGroupAddon>
                <InputText v-model="data.params.search" placeholder="Buscar Cargo..."/>
                <InputGroupAddon>
                  <Button icon="pi pi-times" severity="secondary" class="h-8" @click="limpiarBuscador"/>
                </InputGroupAddon>
              </InputGroup>
            </div>
          </div>
        </template>
        <template #empty> Sin datos encontrados.</template>
        <template #loading> Cargando datos. Espere un momento porfavor.</template>

        <Column header="No">
          <template #body="slotProps">
            {{ slotProps.index + 1 }}
          </template>
        </Column>

        <Column field="cargo" header="Cargos"></Column>
        <Column :exportable="false" style="min-width: 12rem">
          <template #body="slotProps">
            <Button v-show="can(['update cargo'])" icon="pi pi-pencil" outlined rounded class="mr-2" @click="
                                                    (data.editOpen = true),
                                                    (data.cargo = slotProps.data)"/>
            <Button v-show="can(['delete cargo'])" icon="pi pi-trash" outlined rounded severity="danger"
                    @click="deleteDialog = true; data.cargo = slotProps.data"/>
          </template>
        </Column>
      </DataTable>

      <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Eliminar Cargo" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-exclamation-triangle !text-3xl"/>
          <span v-if="data.cargo">
            ¿Estás seguro de eliminar de el cargo <b>{{ data.cargo.cargo }}</b>?
          </span>
        </div>
        <template #footer>
          <Button label="No" icon="pi pi-times" text @click="deleteDialog = false"/>
          <Button label="Eliminar" icon="pi pi-check" @click="deleteData"/>
        </template>
      </Dialog>
    </div>
  </app-layout>
</template>

<style scoped lang="scss">
</style>
