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
const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
  },
  evaluado: null,
});
const dataResponse = reactive({
  objects: {
    acercaPoligrafo: Object,
    comisionDelitos: Object,
    consumoBebidasAlcoholicas: Object,
    detallesExperienciasLaborales: Object,
    experienciasLaborales: Object,
    formacionesAcademicas: Object,
    implicaonesDrogas: Object,
    informacionesFinancieras: Object,
    motivacionesPostulacion: Object,
    parentescos: Object,
    personas: Object,
    personasMargenLeyes: Object,
    entidadesBancarias: Object,
  }
});
const bancoPrestamo = ref("");
const bancoReportado = ref("");
const isDisabled = ref(false);
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

const obtenerDetallePersona = () => {
  axios.get(route("evaluados.detalle", data.evaluado?.personaId))
    .then((response) => {
      dataResponse.objects.personas = response.data.personas;
      dataResponse.objects.parentescos = response.data.parentescos;
      dataResponse.objects.formacionesAcademicas = response.data.formacionesAcademicas;
      dataResponse.objects.experienciasLaborales = response.data.experienciasLaborales;
      dataResponse.objects.detallesExperienciasLaborales = response.data.detallesExperienciasLaborales;
      dataResponse.objects.informacionesFinancieras = response.data.informacionesFinancieras;
      dataResponse.objects.consumoBebidasAlcoholicas = response.data.consumoBebidasAlcoholicas;
      dataResponse.objects.implicaonesDrogas = response.data.implicaonesDrogas;
      dataResponse.objects.comisionDelitos = response.data.comisionDelitos;
      dataResponse.objects.personasMargenLeyes = response.data.personasMargenLeyes;
      dataResponse.objects.motivacionesPostulacion = response.data.motivacionesPostulacion;
      dataResponse.objects.acercaPoligrafo = response.data.acercaPoligrafo;
      dataResponse.objects.entidadesBancarias = response.data.entidadesBancarias;
    })
    .catch((error) => {
      console.log(error);
    })
    .finally(() => {
      dataResponse.objects.entidadesBancarias.map((e) => {
        if (dataResponse.objects.informacionesFinancieras[0].tiene_prestamo) {
          if (dataResponse.objects.informacionesFinancieras[0].entidad_bancaria_prestamo_id === e.id) {
            bancoPrestamo.value = e.nombre_entidad;
          }
        } else {
          bancoPrestamo.value = 'No registra';
        }
      });

      dataResponse.objects.entidadesBancarias.map((e) => {
        if (dataResponse.objects.informacionesFinancieras[0].reportado_centrar_riesgos) {
          if (dataResponse.objects.informacionesFinancieras[0].entidad_bancaria_reporto_id === e.id) {
            bancoReportado.value = e.nombre_entidad;
          }
        } else {
          bancoReportado.value = 'No registra';
        }
      });

      detailDialog.value = true
    });
}

const generarFormato = () =>{
  isDisabled.value = true;
  axios.get(route("evaluados.formatouno.descargar", data.evaluado?.personaId,{responseType: 'Blob' }))
    .then((response)=>{
      const fileLink = document.createElement('a');
      fileLink.href = response.config.url;
      document.body.appendChild(fileLink);
      fileLink.click();
    })
    .catch((error)=>{console.log(error)})
    .finally(() => {
      isDisabled.value = false;
    });
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
      <DataTable lazy :value="evaluados.data" paginator :rows="evaluados.per_page" :totalRecords="evaluados.total" :first="(evaluados.current_page - 1) * evaluados.per_page" @page="onPageChange" tableStyle="min-width: 50rem">
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
        <Column field="tipo_documento" header="Tipo Documento"></Column>
        <Column field="numero_documento" header="N° Documento"></Column>
        <Column header="Nombres y Apellidos">
          <template #body="slotProps">
            {{ slotProps.data.nombres }} {{ slotProps.data.apellido_paterno }} {{ slotProps.data.apellido_materno }}
          </template>
        </Column>
        <Column :exportable="false" header="Acciones" style="min-width: 12rem">
          <template #body="slotProps">
            <div class="inline-flex rounded-md shadow-xs" role="group">
              <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]" @click="data.evaluado = slotProps.data; obtenerDetallePersona()">
                <div class="flex items-center justify-center">
                  <i class="pi pi-eye me-1"></i> Detalles
                </div>
              </button>

              <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-e border-gray-200 hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]" @click="data.evaluado = slotProps.data; generarFormato()" :disabled='isDisabled'>
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1" v-if="!isDisabled"></i> <i class="pi pi-spin pi-spinner me-2" style="font-size: 1rem" v-else></i> Formato Uno
                </div>
              </button>

              <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b  border-gray-200 hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]">
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1"></i> Consentimiento
                </div>
              </button>

              <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]" v-if="slotProps.data.informe_final">
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1"></i> Informe Final
                </div>
              </button>
              <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]" v-else>
                <div class="flex items-center justify-center">
                  <i class="pi pi-file-edit me-1"></i> Generar Informe Final
                </div>
              </button>
            </div>
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
                <div>
                  <i class="pi pi-building !text-2xl pe-4"/> Empresa y Cargo que postula
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Empresa</th>
                          <th scope="col" class="px-6 py-3">Cargo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].nombre_comercial }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].cargo }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="1">
              <AccordionHeader>
                <div>
                  <i class="pi pi-id-card !text-2xl pe-4"/> Datos personales
                </div>
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
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].nombres }}
                            {{ dataResponse.objects.personas[0].apellido_paterno }}
                            {{ dataResponse.objects.personas[0].apellido_materno }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].nacionalidad }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].tipo_documento }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].numero_documento }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].fecha_nacimiento }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].estado_civil }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].genero }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].distrito }}
                          </td>
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
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].direccion }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].lugar_nacimiento }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].telefono }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].email }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].brevete ?? 'No registra' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].tipo_vivienda }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.personas[0].otro_tipo_vivienda ?? 'No registra' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="2">
              <AccordionHeader>
                <div>
                  <i class="pi pi-users !text-2xl pe-4"/> Datos Familiares
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tipo Parentesco</th>
                          <th scope="col" class="px-6 py-3">Nombres y Apellidos</th>
                          <th scope="col" class="px-6 py-3">Edad</th>
                          <th scope="col" class="px-6 py-3">Ocupación</th>
                          <th scope="col" class="px-6 py-3">Mismo Inmueble</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b" v-for="parentesco in dataResponse.objects.parentescos">
                          <td class="px-6 py-4">
                            {{ parentesco.tipo_parentesco }}
                          </td>
                          <td class="px-6 py-4">
                            {{ parentesco.nombres_apellidos }}
                          </td>
                          <td class="px-6 py-4">
                            {{ parentesco.edad }}
                          </td>
                          <td class="px-6 py-4">
                            {{ parentesco.ocupacion }}
                          </td>
                          <td class="px-6 py-4">
                            {{ parentesco.mismo_inmueble === true ? 'SI' : 'NO' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="3">
              <AccordionHeader>
                <div>
                  <i class="pi pi-bookmark !text-2xl pe-4"/> Formación Académica
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Grado de Instrucción</th>
                          <th scope="col" class="px-6 py-3">Centro de Estudios</th>
                          <th scope="col" class="px-6 py-3">Fecha Inicio</th>
                          <th scope="col" class="px-6 py-3">Fecha Termino</th>
                          <th scope="col" class="px-6 py-3">Especialidad</th>
                          <th scope="col" class="px-6 py-3">Situación</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b" v-for="formacionAcademica in dataResponse.objects.formacionesAcademicas">
                          <td class="px-6 py-4">
                            {{ formacionAcademica.grado_instruccion }}
                          </td>
                          <td class="px-6 py-4">
                            {{ formacionAcademica.centro_estudios }}
                          </td>
                          <td class="px-6 py-4">
                            {{ formacionAcademica.fecha_inicio }}
                          </td>
                          <td class="px-6 py-4">
                            {{ formacionAcademica.fecha_termino }}
                          </td>
                          <td class="px-6 py-4">
                            {{ formacionAcademica.especialidad_facultad }}
                          </td>
                          <td class="px-6 py-4">
                            {{ formacionAcademica.situacion === true ? 'Completo' : 'Incompleto' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="4">
              <AccordionHeader>
                <div>
                  <i class="pi pi-briefcase !text-2xl pe-4"/> Experiencias Laborales
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Empresa</th>
                          <th scope="col" class="px-6 py-3">Fecha Ingreso</th>
                          <th scope="col" class="px-6 py-3">Fecha Salida</th>
                          <th scope="col" class="px-6 py-3">Sueldo</th>
                          <th scope="col" class="px-6 py-3">Cargo Desempeñado</th>
                          <th scope="col" class="px-6 py-3">Motivo de su Salida</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b" v-for="experienciaLaboral in dataResponse.objects.experienciasLaborales">
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.empresa }}
                          </td>
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.fecha_ingreso }}
                          </td>
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.fecha_salida }}
                          </td>
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.sueldo_percibido }}
                          </td>
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.cargo_desempenado }}
                          </td>
                          <td class="px-6 py-4">
                            {{ experienciaLaboral.motivo_salida }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Amonestaciones</th>
                          <th scope="col" class="px-6 py-3">Solicitud Renuncia</th>
                          <th scope="col" class="px-6 py-3">Explicación</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{
                              dataResponse.objects.detallesExperienciasLaborales[0].recibio_amonestaciones === true ? 'SI' : 'NO'
                            }}
                          </td>
                          <td class="px-6 py-4">
                            {{
                              dataResponse.objects.detallesExperienciasLaborales[0].solicitud_renuncia === true ? 'SI' : 'NO'
                            }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.detallesExperienciasLaborales[0].explicacion ?? 'No registra' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="5">
              <AccordionHeader>
                <div>
                  <i class="pi pi-wallet !text-2xl pe-4"/> Información Financiera
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tiene Prestamos</th>
                          <th scope="col" class="px-6 py-3">Monto Prestamo</th>
                          <th scope="col" class="px-6 py-3">Entidad Prestamo</th>
                          <th scope="col" class="px-6 py-3">Cuota Mensual</th>
                          <th scope="col" class="px-6 py-3">Otro Ingreso</th>
                          <th scope="col" class="px-6 py-3">Monto Ingreso</th>
                          <th scope="col" class="px-6 py-3">Origen</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].tiene_prestamo === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_prestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ bancoPrestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].cuota_mensual_prestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].otro_ingreso === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_ingreso }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].origen_ingreso ?? 'No registra' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tiene Propiedades</th>
                          <th scope="col" class="px-6 py-3">Detalle Propiedades</th>
                          <th scope="col" class="px-6 py-3">Reportado Central de Riesgo</th>
                          <th scope="col" class="px-6 py-3">Entidad Deuda</th>
                          <th scope="col" class="px-6 py-3">Motivo</th>
                          <th scope="col" class="px-6 py-3">Tiempo Mora</th>
                          <th scope="col" class="px-6 py-3">Monto Deuda</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{
                              dataResponse.objects.informacionesFinancieras[0].tiene_propiedades === true ? 'SI' : 'NO'
                            }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].detalle_propiedades ?? 'No registra' }}
                          </td>
                          <td class="px-6 py-4">
                            {{
                              dataResponse.objects.informacionesFinancieras[0].reportado_centrar_riesgos === true ? 'SI' : 'NO'
                            }}
                          </td>
                          <td class="px-6 py-4">
                            {{ bancoReportado }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].motivo_reportado }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].tiempo_mora ?? 'No registra' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_deuda }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="6">
              <AccordionHeader>
                <div class="flex">
                  <i class="pi pi-megaphone !text-2xl pe-7 -rotate-90" style="margin-left: -8px; margin-top: -10px"/> <p style="margin-top: 7px">Consumo de Bebidas Alcoholicas</p>
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tiene Prestamos</th>
                          <th scope="col" class="px-6 py-3">Monto Prestamo</th>
                          <th scope="col" class="px-6 py-3">Entidad Prestamo</th>
                          <th scope="col" class="px-6 py-3">Cuota Mensual</th>
                          <th scope="col" class="px-6 py-3">Otro Ingreso</th>
                          <th scope="col" class="px-6 py-3">Monto Ingreso</th>
                          <th scope="col" class="px-6 py-3">Origen</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].tiene_prestamo === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_prestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ bancoPrestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].cuota_mensual_prestamo }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].otro_ingreso === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_ingreso }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].origen_ingreso ?? 'No registra' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tiene Propiedades</th>
                          <th scope="col" class="px-6 py-3">Detalle Propiedades</th>
                          <th scope="col" class="px-6 py-3">Reportado Central de Riesgo</th>
                          <th scope="col" class="px-6 py-3">Entidad Deuda</th>
                          <th scope="col" class="px-6 py-3">Motivo</th>
                          <th scope="col" class="px-6 py-3">Tiempo Mora</th>
                          <th scope="col" class="px-6 py-3">Monto Deuda</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].tiene_propiedades === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].detalle_propiedades ?? 'No registra' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].reportado_centrar_riesgos === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ bancoReportado }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].motivo_reportado ?? 'No registra'}}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].tiempo_mora ?? 'No registra' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.informacionesFinancieras[0].monto_deuda }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="7">
              <AccordionHeader>
                <div>
                  <i class="pi pi-exclamation-triangle !text-2xl pe-4"/> Impliación en Drogas Ilegales
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Marihuana</th>
                          <th scope="col" class="px-6 py-3">PBC</th>
                          <th scope="col" class="px-6 py-3">Cocaína</th>
                          <th scope="col" class="px-6 py-3">Heroína</th>
                          <th scope="col" class="px-6 py-3">LCD</th>
                          <th scope="col" class="px-6 py-3">Éxtasis</th>
                          <th scope="col" class="px-6 py-3">Ultimo Consumo de Droga Ilegal</th>
                          <th scope="col" class="px-6 py-3">Cantidad</th>
                          <th scope="col" class="px-6 py-3">Familiares Implicados en Drogas</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].marihuana === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].pbc === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].cocaina === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].heroina === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].lsd === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].extasis === true ? 'SI' : 'NO' }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].tiempo_transcurrido }}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].ultimo_consumo === "0" ? 'No registra' : dataResponse.objects.implicaonesDrogas[0].ultimo_consumo}}
                          </td>
                          <td class="px-6 py-4">
                            {{ dataResponse.objects.implicaonesDrogas[0].familiar_consumidor === true ? 'SI' : 'NO' }}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="8">
              <AccordionHeader>
                <div>
                  <i class="pi pi-exclamation-circle !text-2xl pe-4"/> Comisión de Delitos
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Robo - Hurto - Fraude</th>
                          <th scope="col" class="px-6 py-3">Homicidio involuntario</th>
                          <th scope="col" class="px-6 py-3">Asalto</th>
                          <th scope="col" class="px-6 py-3">Planes para causar daño físico a un individuo</th>
                          <th scope="col" class="px-6 py-3">Secuestro</th>
                          <th scope="col" class="px-6 py-3">Violación</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].robo_hurto_fraude === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].homicidio_involuntario === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].asalto === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].danio_fisico_individuo === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].secuestro === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].violacion === true ? 'SI' : 'NO'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tráfico ilícito de drogas</th>
                          <th scope="col" class="px-6 py-3">Tráfico de armas</th>
                          <th scope="col" class="px-6 py-3">Cualquier acto. conspiración o solicitud en los cuales usted puede ser castigado con cárcel</th>
                          <th scope="col" class="px-6 py-3">Cualquier delito que causara muerte o lesión a otra persona</th>
                          <th scope="col" class="px-6 py-3">En caso de respuesta afirmativa</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].trafico_ilicito_drogas === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].trafico_armas === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].otros_delitos === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].muerte_lesion_otra_persona === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].explique_otros ?? 'No registra'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="9">
              <AccordionHeader>
                <div>
                  <i class="pi pi-user-minus !text-2xl pe-4"/> Conoce Alguna Persona al Margen de la Ley
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto mb-4">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Pandilleros</th>
                          <th scope="col" class="px-6 py-3">Sicarios</th>
                          <th scope="col" class="px-6 py-3">Asaltantes</th>
                          <th scope="col" class="px-6 py-3">Traficante de drogas</th>
                          <th scope="col" class="px-6 py-3">Estafadores</th>
                          <th scope="col" class="px-6 py-3">Terroristas</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].pandilleros === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].sicarios === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].asaltantes === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].traficantes_drogas === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].estafadores === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].terroristas === true ? 'SI' : 'NO'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Secuestradores</th>
                          <th scope="col" class="px-6 py-3">Extorsionadores</th>
                          <th scope="col" class="px-6 py-3">Otros</th>
                          <th scope="col" class="px-6 py-3">Tiene familiares sentenciados en penales:</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].secuestradores === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].extorsionadores === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].otros === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].familiares_sentenciados === true ? 'SI' : 'NO'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="10">
              <AccordionHeader>
                <div>
                  <i class="pi pi-bolt !text-2xl pe-4"/> Motivaciones por que Postula a la Empresa
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Tiene planeado ingresar a la empresa que postula, para causar algún daño a personas o la empresa</th>
                          <th scope="col" class="px-6 py-3">Tiene planeado ingresar a la empresa que postula, para obtener algún beneficio ilegal</th>
                          <th scope="col" class="px-6 py-3">Tiene familiares o amigos que trabajan o trabajaron en la empresa que postula</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].causar_danio === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].beneficio_ilegal === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.comisionDelitos[0].familiares_en_empresa === true ? 'SI' : 'NO'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
              </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="11">
              <AccordionHeader>
                <div>
                  <i class="pi pi-chart-bar !text-2xl pe-4"/> Acerca del Poligrafo
                </div>
              </AccordionHeader>
              <AccordionContent>
                <Card>
                  <template #content>
                    <div class="relative overflow-x-auto">
                      <table class="w-full text-md text-left">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-300">
                        <tr>
                          <th scope="col" class="px-6 py-3">Alguna vez ha pasado exámen de polígrafo</th>
                          <th scope="col" class="px-6 py-3">En caso de respuesta afirmativa, explique</th>
                          <th scope="col" class="px-6 py-3">Empresa</th>
                          <th scope="col" class="px-6 py-3">Fecha</th>
                          <th scope="col" class="px-6 py-3">Motivo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b">
                          <td class="px-6 py-4">
                            {{dataResponse.objects.acercaPoligrafo[0].paso_antes_examen === true ? 'SI' : 'NO'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.acercaPoligrafo[0].explique_paso_antes ?? 'No registra'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.acercaPoligrafo[0].empresa ?? 'No registra'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.acercaPoligrafo[0].fecha ?? 'No registra'}}
                          </td>
                          <td class="px-6 py-4">
                            {{dataResponse.objects.acercaPoligrafo[0].motivo ?? 'No registra'}}
                          </td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </template>
                </Card>
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
#button-pill{
  background-color: transparent;
  border-color: transparent;
  color: #334155 !important;
}

#button-pill:hover{
  background-color: #F1F5F9;
  border-color: #F1F5F9;
  color: #334155 !important;
}
</style>
