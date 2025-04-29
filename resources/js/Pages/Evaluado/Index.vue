<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";

import {Head, router} from '@inertiajs/vue3';

import {reactive, ref, watch} from "vue";
import pkg from "lodash";

const {_, debounce, pickBy} = pkg;

import {loadToast} from '@/composables/loadToast';
import Card from "primevue/card";

import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';

const props = defineProps({
  filters: Object,
  perPage: Number,
  evaluados: Object,
});

loadToast();

const detailDialog = ref(false);
const parentescos = ref({})

const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
  },
  evaluado: null,
});

const onPageChange = (event) => {
  router.get(route('evaluados.index'), {page: event.page + 1}, {preserveState: true});
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

const obtenerParentescos = () => {
  router.get(route("evaluados.parentescos", data.evaluado?.personaId), {
    preserveScroll: true,
    onSuccess: (data) => {
      detailDialog.value = true;
      console.log(data)
    },
    onError: () => null,
    onFinish: () => null,
  })
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
      <DataTable
        lazy :value="evaluados.data"
        paginator
        :rows="evaluados.per_page"
        :totalRecords="evaluados.total"
        :first="(evaluados.current_page - 1) * evaluados.per_page"
        @page="onPageChange"
        tableStyle="min-width: 50rem">
        <template #header>
          <div class="flex justify-end mb-4">
            <div class="flex w-full xl:w-1/4 lg:w-1/3 md:w-1/3 h-10">
              <InputGroup>
                <InputGroupAddon>
                  <i class="pi pi-search"/>
                </InputGroupAddon>
                <InputText v-model="data.params.search" placeholder="Buscar Persona..."/>
                <InputGroupAddon>
                  <button type="button" @click="limpiarBuscador">
                    <i class="pi pi-times"></i>
                  </button>
                </InputGroupAddon>
              </InputGroup>
            </div>
          </div>
        </template>
        <template #empty> Sin datos para mostrar.</template>
        <template #loading> Cargando datos. Porf favor espere.</template>
        <Column header="No">
          <template #body="slotProps">
            {{ slotProps.index + 1 }}
          </template>
        </Column>
        <Column field="tipo_documento" header="Tipo Documento."></Column>
        <Column field="numero_documento" header="N° Documento"></Column>
        <Column header="Nombres y Apellidos">
          <template #body="slotProps">
            {{ slotProps.data.nombres }} {{ slotProps.data.apellido_paterno }} {{ slotProps.data.apellido_materno }}
          </template>
        </Column>
        <Column :exportable="false" header="Detalles" style="min-width: 12rem">
          <template #body="slotProps">
            <Button icon="pi pi-eye" outlined rounded severity="info" class="ml-2"
                    @click="detailDialog = true; data.evaluado = slotProps.data"/>
          </template>
        </Column>
      </DataTable>

      <Dialog
        v-model:visible="detailDialog"
        :style="{ width: '90%' }"
        header="Información del Evaluado"
        position="top"
        :modal="true">
        <div class="w-full">
          <Accordion value="0">
            <AccordionPanel value="0">
              <AccordionHeader>
                <i class="pi pi-user !text-2xl"/>Datos Personales
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                          <tr>
                            <th scope="col" class="px-6 py-3">Nombres y Apellidos</th>
                            <th scope="col" class="px-6 py-3">Nacioinalidad</th>
                            <th scope="col" class="px-6 py-3">Tipo Documento</th>
                            <th scope="col" class="px-6 py-3">N° Documento</th>
                            <th scope="col" class="px-6 py-3">Fecha de Nacimiento</th>
                            <th scope="col" class="px-6 py-3">Estado Civil</th>
                            <th scope="col" class="px-6 py-3">Genero</th>
                            <th scope="col" class="px-6 py-3">Distrito</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="border-b">
                            <td class="px-6 py-4">{{ data.evaluado.nombres }} {{ data.evaluado.apellido_paterno }} {{ data.evaluado.apellido_materno }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.nacionalidad }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.tipo_documento }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.numero_documento }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.fecha_nacimiento }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.estado_civil }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.genero }}</td>
                            <td class="px-6 py-4">{{ data.evaluado.distrito }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Dirección Vivienda</th>
                          <th scope="col" class="px-6 py-3">Lugar de Nacimiento</th>
                          <th scope="col" class="px-6 py-3">Teléfono</th>
                          <th scope="col" class="px-6 py-3">Email</th>
                          <th scope="col" class="px-6 py-3">Brevete</th>
                          <th scope="col" class="px-6 py-3">Tipo Vivienda</th>
                          <th scope="col" class="px-6 py-3">Otro Tipo Vivienda</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">{{ data.evaluado.direccion }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.lugar_nacimiento }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.telefono }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.email }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.brevete ?? 'No registra' }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.tipo_vivienda }}</td>
                          <td class="px-6 py-4">{{ data.evaluado.otro_tipo_vivienda ?? 'No registra' }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="1" @click="obtenerParentescos">
              <AccordionHeader>
                <i class="pi pi-building !text-2xl"/>Parentescos
              </AccordionHeader>
              <AccordionContent>
                <div class="relative overflow-x-auto" >
                  <table class="w-full text-md text-left">
                    <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                    <tr>
                      <th scope="col" class="px-6 py-3">Dirección Vivienda</th>
                      <th scope="col" class="px-6 py-3">Lugar de Nacimiento</th>
                      <th scope="col" class="px-6 py-3">Teléfono</th>
                      <th scope="col" class="px-6 py-3">Email</th>
                      <th scope="col" class="px-6 py-3">Brevete</th>
                      <th scope="col" class="px-6 py-3">Tipo Vivienda</th>
                      <th scope="col" class="px-6 py-3">Otro Tipo Vivienda</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-b">
                      <td class="px-6 py-4">{{ data.evaluado.direccion }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.lugar_nacimiento }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.telefono }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.email }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.brevete ?? 'No registra' }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.tipo_vivienda }}</td>
                      <td class="px-6 py-4">{{ data.evaluado.otro_tipo_vivienda ?? 'No registra' }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="2">
              <AccordionHeader>Header III</AccordionHeader>
              <AccordionContent>
                <p class="m-0">
                  At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                  deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non
                  provident, similique sunt in culpa
                  qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis
                  est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil
                  impedit quo minus.
                </p>
              </AccordionContent>
            </AccordionPanel>
          </Accordion>
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
