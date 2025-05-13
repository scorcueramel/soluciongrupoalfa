<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";

import {Head, router, useForm} from '@inertiajs/vue3';

import {onMounted, reactive, ref, watch} from "vue";
import pkg from "lodash";

const {_, debounce, pickBy} = pkg;

import {loadToast} from '@/composables/loadToast';
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
const fotoDialog = ref(false);
const fotoevaluado = ref("");
const generarInformeFinal = ref(false);
const inputs = ref([{valor: ''}]);
const abecdario = ref(["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "ñ", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"]);
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

const form = useForm({
  personaId:0,
  drogasIlegales: "",
  antecedentes: "",
  vinculos: "",
  planesInfiltracion: "",
  proyeccion: "",
  preguntasRelevantes: [],
  conclusion: "",
})

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

      fotoevaluado.value = dataResponse.objects.personas[0].foto;
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

const generarFormato = () => {
  axios.get(route("evaluados.formatouno.descargar", data.evaluado?.personaId, {responseType: 'Blob'}))
    .then((response) => {
      const fileLink = document.createElement('a');
      fileLink.href = response.config.url;
      document.body.appendChild(fileLink);
      fileLink.click();
    })
    .catch((error) => {
      console.log(error)
    })
    .finally(() => {
    });
}

const generarConsentimiento = () => {
  console.log(data.evaluado?.personaId)
  axios.get(route("evaluados.consentimiento.descargar", data.evaluado?.personaId, {responseType: 'Blob'}))
    .then((response) => {
      const fileLink = document.createElement('a');
      fileLink.href = response.config.url;
      document.body.appendChild(fileLink);
      fileLink.click();
    })
    .catch((error) => {
      console.log(error)
    })
    .finally(() => {
    });
}

const agregarInput = () => {
  inputs.value.push({valor: ''});
}
const eliminarInput = (index) => {
  inputs.value.splice(index, 1);
  form.preguntasRelevantes.splice(index, 1);
}

const registrarInformeFinal = () => {
  generarInformeFinal.value = false;
  Swal.fire({
    title: "¿Terminaste?",
    html: "Al presionar el botón <b>Continuar</b> se registrará la informarción ingresada",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#10B981",
    cancelButtonColor: "#d33",
    confirmButtonText: "Continuar",
    cancelButtonText: "Revisar",
  }).then((result) => {
    if (result.isConfirmed) {
      /*Swal.fire({
        icon: 'info',
        html: "Espere un momento porfavor ...",
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });*/
      form.personaId = data.evaluado.personaId;
      form.post(route('informes.store'), {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
        preserveScroll: true,
        onSuccess: () => {
          emit("close");
          form.reset();
        },
        onError: () => null,
        onFinish: () => null,
      });
    }else{
      generarInformeFinal.value = true;
    }
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
      <DataTable lazy :value="evaluados.data" paginator :rows="evaluados.per_page" :totalRecords="evaluados.total"
                 :first="(evaluados.current_page - 1) * evaluados.per_page" @page="onPageChange"
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
              <button type="button"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]"
                      @click="data.evaluado = slotProps.data; obtenerDetallePersona()" v-show="can(['read evaluado'])">
                <div class="flex items-center justify-center">
                  <i class="pi pi-eye me-1"></i> Detalles
                </div>
              </button>

              <button type="button"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-e border-gray-200 hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]"
                      @click="data.evaluado = slotProps.data; generarFormato()" v-show="can(['format evaluado'])">
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1"></i> Formato Uno
                </div>
              </button>

              <button type="button"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b  border-gray-200 hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]"
                      @click="data.evaluado = slotProps.data; generarConsentimiento()"
                      v-show="can(['consent evaluado'])">
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1"></i> Consentimiento
                </div>
              </button>

              <button type="button"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]"
                      v-if="slotProps.data.informe_final" v-show="can(['report evaluado'])">
                <div class="flex items-center justify-center">
                  <i class="pi pi-file me-1"></i> Informe Final
                </div>
              </button>
              <button type="button"
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-[#10B981] focus:z-10 focus:ring-2 focus:ring-[#10B981] focus:text-[#10B981]"
                      v-else @click="data.evaluado = slotProps.data; generarInformeFinal = true"
                      v-show="can(['final report evaluado'])">
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
          <div class="mx-10">
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="2" class="px-6 py-3 text-lg text-center">EMPRESA A LA QUE POSTULA</th>
              </tr>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="9" class="px-6 py-3 text-lg text-center">DATOS PERSONALES</th>
              </tr>
              <tr>
                <th scope="col" class="px-6 py-3">Foto</th>
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
                  <button class="underline" @click="fotoDialog = true"
                          v-if="dataResponse.objects.personas[0].foto != null"><i class="pi pi-image"></i> Foto Evaluado
                  </button>
                  <span v-else>No registra</span>
                </td>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="8" class="px-6 py-3 text-lg text-center">DATOS FAMILIARES</th>
              </tr>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="8" class="px-6 py-3 text-lg text-center">FORMACIÓN ACADÉMICA</th>
              </tr>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="8" class="px-6 py-3 text-lg text-center">EXPERIENCIA LABORAL</th>
              </tr>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="8" class="px-6 py-3 text-lg text-center">INFORMACIÓN FINANCIERA</th>
              </tr>
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
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="5" class="px-6 py-3 text-lg text-center">CONSUMO DE BEBIDAS ALCOHOLICAS</th>
              </tr>
              <tr>
                <th scope="col" class="px-6 py-3">Con Qué Frecuencia Consume Bebidas Alcohólicas</th>
                <th scope="col" class="px-6 py-3">Que Bebidas Consume</th>
                <th scope="col" class="px-6 py-3">Ha recibido tratamiento por alcoholismo</th>
                <th scope="col" class="px-6 py-3">Ha Llegado a Trabajar Ebrio</th>
                <th scope="col" class="px-6 py-3">En Caso De Respuesta Afirmativa, Explique</th>
              </tr>
              </thead>
              <tbody>
              <tr class="border-b">
                <td class="px-6 py-4">
                  {{ dataResponse.objects.consumoBebidasAlcoholicas[0].frecuencia_consumo ?? 'No registra' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.consumoBebidasAlcoholicas[0].bebidas_consume ?? 'No registra' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.consumoBebidasAlcoholicas[0].tratamiento_alcoholismo === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.consumoBebidasAlcoholicas[0].trabajo_ebrio === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.consumoBebidasAlcoholicas[0].explicacion ?? 'No registra' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="9" class="px-6 py-3 text-lg text-center">IMPLICACIÓN EN DROGAS ILEGALES</th>
              </tr>
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
                  {{
                    dataResponse.objects.implicaonesDrogas[0].ultimo_consumo === "0" ? 'No registra' : dataResponse.objects.implicaonesDrogas[0].ultimo_consumo
                  }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.implicaonesDrogas[0].familiar_consumidor === true ? 'SI' : 'NO' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="6" class="px-6 py-3 text-lg text-center">COMISIÓN DE DELITOS</th>
              </tr>
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
                  {{ dataResponse.objects.comisionDelitos[0].robo_hurto_fraude === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].homicidio_involuntario === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].asalto === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].danio_fisico_individuo === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].secuestro === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].violacion === true ? 'SI' : 'NO' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th scope="col" class="px-6 py-3">Tráfico ilícito de drogas</th>
                <th scope="col" class="px-6 py-3">Tráfico de armas</th>
                <th scope="col" class="px-6 py-3">Cualquier acto. conspiración o solicitud en los cuales usted puede ser
                  castigado con cárcel
                </th>
                <th scope="col" class="px-6 py-3">Cualquier delito que causara muerte o lesión a otra persona</th>
                <th scope="col" class="px-6 py-3">En caso de respuesta afirmativa</th>
              </tr>
              </thead>
              <tbody>
              <tr class="border-b">
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].trafico_ilicito_drogas === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].trafico_armas === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].otros_delitos === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].muerte_lesion_otra_persona === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].explique_otros ?? 'No registra' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="6" class="px-6 py-3 text-lg text-center">CONOCE ALGUNA PERSONA AL MARGEN DE LA LEY</th>
              </tr>
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
                  {{ dataResponse.objects.comisionDelitos[0].pandilleros === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].sicarios === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].asaltantes === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].traficantes_drogas === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].estafadores === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].terroristas === true ? 'SI' : 'NO' }}
                </td>
              </tr>
              </tbody>
            </table>
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
                  {{ dataResponse.objects.comisionDelitos[0].secuestradores === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].extorsionadores === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].otros === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].familiares_sentenciados === true ? 'SI' : 'NO' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="3" class="px-6 py-3 text-lg text-center">MOTIVACIONES POR QUE POSTULA A LA EMPRESA</th>
              </tr>
              <tr>
                <th scope="col" class="px-6 py-3">Tiene planeado ingresar a la empresa que postula, para causar algún
                  daño a personas o la empresa
                </th>
                <th scope="col" class="px-6 py-3">Tiene planeado ingresar a la empresa que postula, para obtener algún
                  beneficio ilegal
                </th>
                <th scope="col" class="px-6 py-3">Tiene familiares o amigos que trabajan o trabajaron en la empresa que
                  postula
                </th>
              </tr>
              </thead>
              <tbody>
              <tr class="border-b">
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].causar_danio === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].beneficio_ilegal === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.comisionDelitos[0].familiares_en_empresa === true ? 'SI' : 'NO' }}
                </td>
              </tr>
              </tbody>
            </table>
            <table class="w-full text-md text-left">
              <thead class="text-xs text-gray-800 uppercase bg-gray-300">
              <tr>
                <th colspan="5" class="px-6 py-3 text-lg text-center">ACERCA DEL POLIGRAFO</th>
              </tr>
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
                  {{ dataResponse.objects.acercaPoligrafo[0].paso_antes_examen === true ? 'SI' : 'NO' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.acercaPoligrafo[0].explique_paso_antes ?? 'No registra' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.acercaPoligrafo[0].empresa ?? 'No registra' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.acercaPoligrafo[0].fecha ?? 'No registra' }}
                </td>
                <td class="px-6 py-4">
                  {{ dataResponse.objects.acercaPoligrafo[0].motivo ?? 'No registra' }}
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <template #footer>
          <Button label="Cerrar" text @click="detailDialog = false"/>
        </template>
      </Dialog>

      <Dialog
        v-model:visible="fotoDialog"
        :style="{ width: '30%' }"
        position="center"
        :modal="true">
        <div class="w-full">
          <div class="mx-10">
            <img :src="'/storage/fotos/'+fotoevaluado" alt="Foto del evaluado">
          </div>
        </div>
        <template #footer>
          <Button label="Cerrar" text @click="fotoDialog = false"/>
        </template>
      </Dialog>

      <Dialog
        v-model:visible="generarInformeFinal"
        :style="{ width: '60%' }"
        position="top"
        :modal="true"
        :closable="false"
      >
        <div class="w-full">
          <form @submit.prevent="registrarInformeFinal">
            <div class="mx-10">
              <p class="text-xl text-center font-bold">Formulario de Informe Final</p>
              <div class="card">
                <div class="flex flex-col mb-4">
                  <label for="drogas_ilegales">Drogas Ilegales</label>
                  <Textarea
                    id="drogas_ilegales"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Drogas Ilegales"
                    rows="6" cols="30"
                    v-model="form.drogasIlegales"
                  />
                </div>
                <div class="flex flex-col mb-4">
                  <label for="antecedentes">Antecedentes</label>
                  <Textarea
                    id="antecedentes"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Antecedentes"
                    rows="6" cols="30"
                    v-model="form.antecedentes"
                  />
                </div>
                <div class="flex flex-col mb-4">
                  <label for="vinculos">Vínculos</label>
                  <Textarea
                    id="vinculos"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Vínculos"
                    rows="6" cols="30"
                    v-model="form.vinculos"
                  />
                </div>
                <div class="flex flex-col mb-4">
                  <label for="planes_infiltracion">Planes de Infiltración</label>
                  <Textarea
                    id="planes_infiltracion"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Planes de Infiltración"
                    rows="6" cols="30"
                    v-model="form.planesInfiltracion"
                  />
                </div>
                <div class="flex flex-col mb-4">
                  <label for="proyeccion">Proyección de tiempo en la empresa</label>
                  <Textarea
                    id="proyeccion"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Proyección"
                    rows="6" cols="30"
                    v-model="form.proyeccion"
                  />
                </div>
                <div class="flex flex-col mb-4">
                  <p class="font-bold text-lg">Pregunsta Relevantes Realizadas en el Exámen</p>
                </div>
                <div class="mb-5">
                  <div v-for="(input, index) in inputs" :key="index" style="margin-bottom: 10px;"
                       class="flex flex-col mb-4">
                    <Textarea
                      type="text"
                      :placeholder="'Subtest ' + abecdario[(index)].toUpperCase()"
                      v-model="form.preguntasRelevantes[(index)]"
                    />
                    <div class="text-end">
                      <Button type="button" label="Eliminar" severity="danger" @click="eliminarInput(index)"
                              class="mt-2"/>
                    </div>
                  </div>

                  <Button type="button" @click="agregarInput()" label="Agregar Subtest" severity="info" class="mb-4"/>
                </div>
                <div class="flex flex-col mb-4">
                  <label for="conclusion">Conclusión</label>
                  <Textarea
                    id="conclusion"
                    class="flex-auto mt-2"
                    autocomplete="off"
                    placeholder="Conclusión"
                    rows="6" cols="30"
                    v-model="form.conclusion"
                  />
                </div>
                <Button type="submit" label="Guardar Informe" class="bg-[#10B981]"/>
              </div>
            </div>
          </form>
        </div>
        <template #footer>
          <Button label="Cerrar" text @click="generarInformeFinal = false"/>
        </template>
      </Dialog>
    </div>
  </app-layout>
</template>

<style scoped lang="scss">
#button-pill {
  background-color: transparent;
  border-color: transparent;
  color: #334155 !important;
}

#button-pill:hover {
  background-color: #F1F5F9;
  border-color: #F1F5F9;
  color: #334155 !important;
}
</style>
