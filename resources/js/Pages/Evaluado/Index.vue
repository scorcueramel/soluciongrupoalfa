
<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";

import {Head, router} from '@inertiajs/vue3';

import {reactive, ref, watch} from "vue";
import pkg from "lodash";
const { _, debounce, pickBy } = pkg;
import { loadToast } from '@/composables/loadToast';
import Card from "primevue/card";

const props = defineProps({
  filters: Object,
  perPage: Number,
  evaluados: Object,
});

loadToast();

const detailDialog = ref(false);

const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
    createOpen: false,
    editOpen: false,
  },
  evaluado: null,
});

const onPageChange = (event) => {
  router.get(route('evaluados.index'), { page: event.page + 1 }, { preserveState: true });
};

watch(
  () => _.cloneDeep(data.params),
  debounce(() => {
    let params = pickBy(data.params);
    router.get(route("evaluados.index"), params, {
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
    <Head title="Evaluados"/>

    <Card class="mb-8">
      <template #content>
        <div class="flex flex-wrap justify-between items-center">
          <h2 class="text-2xl font-bold">GESTIÓN DE EVALUADOS</h2>
        </div>
      </template>
    </Card>
    <div class="card">
      {{props.evaluados.data[0]}}
      <DataTable
      lazy :value="evaluados.data"
      paginator
      :rows="evaluados.per_page"
      :totalRecords="evaluados.total"
      :first="(evaluados.current_page - 1) * evaluados.per_page"
      @page="onPageChange"
      tableStyle="min-width: 50rem">
        <template #header>
          <div class="flex justify-end">
            <div class="flex w-1/3 h-10">
              <InputGroup>
                <InputGroupAddon>
                  <i class="pi pi-search"/>
                </InputGroupAddon>
                <InputText v-model="data.params.search" placeholder="Buscar Persona..."/>
                <InputGroupAddon>
                  <Button icon="pi pi-times" severity="secondary" class="h-8" @click="limpiarBuscador"/>
                </InputGroupAddon>
              </InputGroup>
            </div>
          </div>
        </template>
        <template #empty> No data found. </template>
        <template #loading> Loading data. Please wait. </template>
        <Column header="No">
          <template #body="slotProps">
            {{slotProps.index + 1}}
          </template>
        </Column>
        <Column field="tipo_documento.tipo_documento" header="Tipo Documento."></Column>
        <Column field="numero_documento" header="N° Documento"></Column>
        <Column field="nombres" header="Nombres"></Column>
        <Column field="apellido_paterno" header="Ap. Paterno"></Column>
        <Column field="apellido_materno" header="Ap. Materno"></Column>
        <Column :exportable="false" header="Detalles" style="min-width: 12rem">
          <template #body="slotProps">
            <Button icon="pi pi-eye" outlined rounded severity="info" class="ml-2" @click="detailDialog = true; data.user = slotProps.data"/>
          </template>
        </Column>
      </DataTable>

      <Dialog v-model:visible="detailDialog" :style="{ width: '450px' }"
              :header="data.user?.name+' '+data.user?.apellido_paterno+' '+data.user?.apellido_materno" :modal="true">
        <div class="flex items-center gap-4">
          <i class="pi pi-user !text-3xl"/>
          <div class="grig grid-cols-1">
            <!--<div class="col-span-2">
              <span v-if="data.user">Nombres y Apellidos: <b class="ms-2">{{
                  data.user.name
                }} {{ data.user.apellido_paterno }} {{ data.user.apellido_materno }}</b></span>
            </div>-->
            <p>data de evaluado</p>
          </div>
        </div>
        <template #footer>
          <Button label="Cerrar" text @click="detailDialog = false"/>
        </template>
      </Dialog>
    </div>
  </app-layout>
</template>

<style scoped lang="scss">
</style>
