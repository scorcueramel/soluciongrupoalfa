<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import {usePrimeVue} from "primevue/config";

import VueDrawingCanvas from "vue-drawing-canvas";

const props = defineProps({
  empresas: Array,
  cargos: Array,
  distritos: Object,
  tiposviviendas: Object,
  tiposparentescos: Object,
  gradosinstrucciones: Object,
  entidadesbancarias: Object,
});

const distritosList = ref([]);
const tiposViviendasList = ref([]);
const otrotipovivienda = ref(false);
const tipoParentescoList = ref([]);
const agregarParentescoActive = ref(true);
const datosConyuge = ref(false);
const cantidadHijos = ref(false);
const datosHijos = ref(false);
const cantidadDeHijosIndicada = ref(0);
const datosHermanos = ref(false);
const cantidadDeHermanosIndicada = ref(0);
const cantidadHermanos = ref(false);
const gradosInstruccionesList = ref([]);
const entidadesBancariasList = ref([]);
const nombresCompletosPiePagina = ref("");
const image = ref("");

const form = useForm({
  empresa: "",
  cargo: "",
  nombres: "",
  paterno: "",
  materno: "",
  tipoDocumento: "",
  numeroDocumento: "",
  fechaNacimiento: "",
  lugarNacimiento: "",
  estadoCivil: "",
  genero: "",
  distrito: "",
  direccion: "",
  tipoVivienda: "",
  otroTipoVivienda: "",
  telefono: "",
  email: "",
  tipoParentescoPadre: 4,
  nombrespadre: "",
  edadpadre: 0,
  nombresocupacionpadre: "",
  mismoInmueblePadre: false,
  tipoParentescoMadre: 5,
  nombresmadre: "",
  edadmadre: 0,
  nombresocupacionmadre: "",
  mismoInmuebleMadre: false,
  otroPatentesco: "",
  tipoParentescoConyuge: 0,
  nombresConyuge: "",
  edadConyuge: 0,
  nombreOcupacionConyuge: "",
  mismoInmuebleConyuge: false,
  tipoParentescoHijos: 0,
  cantidadHijos: 0,
  nombresHijos: [],
  edadesHijos: [],
  nombreOcupacionesHijos: [],
  mismoInmuebleHijos: [],
  tipoParentescoHermanos: 0,
  cantidadHermanos: 0,
  nombresHermanos: [],
  edadesHermanos: [],
  nombreOcupacionesHermanos: [],
  mismoInmuebleHermanos: [],
  gradoInstruccionUno: 0,
  centroEstudioUno: "",
  especialidadesFacultadUno: "",
  inicioEstudiosUno: "",
  terminoEstudiosUno: "",
  situacionUno: false,
  gradoInstruccionDos: 0,
  centroEstudioDos: "",
  especialidadesFacultadDos: "",
  inicioEstudiosDos: "",
  terminoEstudiosDos: "",
  situacionDos: false,
  gradoInstruccionTres: 0,
  centroEstudioTres: "",
  especialidadesFacultadTres: "",
  inicioEstudiosTres: "",
  terminoEstudiosTres: "",
  situacionTres: false,
  empresaUno: "",
  fechaIngresoUno: "",
  fechaSalidaUno: "",
  sueldoUno: 0,
  cargoDesempenioUno: "",
  motivoSalidaUno: "",
  empresaDos: "",
  fechaIngresoDos: "",
  fechaSalidaDos: "",
  sueldoDos: 0,
  cargoDesempenioDos: "",
  motivoSalidaDos: "",
  empresaTres: "",
  fechaIngresoTres: "",
  fechaSalidaTres: "",
  sueldoTres: 0,
  cargoDesempenioTres: "",
  motivoSalidaTres: "",
  amonestacionesEmpleos: false,
  solicitudRenuncia: false,
  explicacion: "",
  tienesPrestamos: false,
  entidadBancaria: "",
  montoDeuda: 0,
  otroIngreso: false,
  preciseMonto: 0,
  origenOtroIngreso: "",
  tienePropiedades: false,
  detallePropiedades: "",
  reportadoEnCentralesDeRiesgo: false,
  preciseDeSerAfirmativo: "",
  entidadDeuda: "",
  motivoCentralDeRiesgo: "",
  tiempoMora: "",
  montoDeuraMora: 0,
  fecuenciaConsumoBebidasAlcoholicas: "",
  queBebidasConsume: "",
  tratamientoAlcholismo: false,
  trabajoEbrio: false,
  expliqueLlegoEbrio: "",
  marihuana: false,
  pbc: false,
  cocaina: false,
  heroina: false,
  lcd: false,
  extasis: false,
  tiempoUltimaVez: false,
  cantidadUltimoConsumo: 0,
  familiaresEnDrogas: false,
  roboHurtoFraude: false,
  homicidioInvoluntario: false,
  asalto: false,
  planesDanioFisico: false,
  secuestro: false,
  violacion: false,
  muerteLesionPersona: false,
  traficoIlicitoDrogas: false,
  traficoArmas: false,
  castigadoConCarcel: false,
  explicacionCastigadoLey: "",
  sicarios: false,
  asaltantes: false,
  traficantesDrogas: false,
  estafadores: false,
  terroristas: false,
  secuestradores: false,
  extorsionadores: false,
  otrasPersonasMargenLey: false,
  familiaresSentenciadosPenales: false,
  planeaCausarDanioPersonaEmpresa: false,
  planeaObtenerBeneficioIlegal: false,
  familiaresMismaEmpresa: false,
  pasoAntesExamenPoligrafo: false,
  explicacionExamenAnterior: "",
  empresaPostuloAntes: "",
  fechaExamenAnterior: "",
  motivoPasoAntesExamen: "",
});

/*onbeforeunload = (event) => {
    event.preventDefault();
};*/

onMounted(() => {
  changeToSpanish();

  props.distritos.map((e) => {
    distritosList.value.push({
      name: e.distrito,
      code: e.id,
    });
  });

  props.tiposviviendas.map((e) => {
    tiposViviendasList.value.push({
      name: e.tipo_vivienda,
      code: e.id,
    });
  });

  props.tiposparentescos.map((e) => {
    if (e.id === 1) {
      tipoParentescoList.value.push({
        name: props.tiposparentescos[0].tipo_parentesco,
        code: props.tiposparentescos[0].id,
      });
    }
    if (e.id === 2) {
      tipoParentescoList.value.push({
        name: props.tiposparentescos[1].tipo_parentesco,
        code: props.tiposparentescos[1].id,
      });
    }
    if (e.id === 3) {
      tipoParentescoList.value.push({
        name: props.tiposparentescos[2].tipo_parentesco,
        code: props.tiposparentescos[2].id,
      });
    }
  });

  props.gradosinstrucciones.map((e) => {
    gradosInstruccionesList.value.push({
      name: e.grado_instruccion,
      code: e.id,
    });
  });

  props.entidadesbancarias.map((e) => {
    entidadesBancariasList.value.push({
      name: e.nombre_entidad,
      code: e.id,
    });
  });
});

const datosPiePagina = () => {
  nombresCompletosPiePagina.value = form.nombres + " " + form.paterno + " " + form.materno
}

const changeToSpanish = () => {
  const primevue = usePrimeVue();
  primevue.config.locale.dayNamesMin = [
    "Dom",
    "Lun",
    "Mar",
    "Mie",
    "Jue",
    "Vie",
    "Sab",
  ];
  primevue.config.locale.monthNames = [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ];
  primevue.config.locale.monthNamesShort = [
    "Ene",
    "Feb",
    "Mar",
    "Abr",
    "May",
    "Jun",
    "Jul",
    "Ago",
    "Sep",
    "Oct",
    "Nov",
    "Dic",
  ];
};

const otroTipoVivienda = () => {
  if (parseInt(form.tipoVivienda) === 5) {
    otrotipovivienda.value = true;
  } else {
    otrotipovivienda.value = false;
    form.otroTipoVivienda = "";
  }
};

const agregarParentesco = (parentesco) => {
  if (parentesco === 1) {
    if (datosConyuge.value) {
      Swal.fire({
        title: "Ooops!",
        text: "Ya gregaste los campos para el conyuge",
        icon: "info",
        draggable: true,
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido",
      });
    }

    datosConyuge.value = true;
    form.tipoParentescoConyuge = 1;
    cantidadHijos.value = false;
  }

  if (parentesco === 2) {
    cantidadHijos.value = true;
    cantidadHermanos.value = false;
    form.tipoParentescoHijos = 2;
  }

  if (parentesco === 3) {
    cantidadHermanos.value = true;
    cantidadHijos.value = false;
    form.tipoParentescoHermanos = 3;
  }
};

const agregarCantidadHijos = () => {
  datosHijos.value = true;
  cantidadDeHijosIndicada.value = parseInt(form.cantidadHijos);
  cantidadHijos.value = false;
  form.cantidadHijos = 0;
};

const agregarCantidadHermanos = () => {
  datosHermanos.value = true;
  cantidadDeHermanosIndicada.value = parseInt(form.cantidadHermanos);
  cantidadHermanos.value = false;
  form.cantidadHermanos = 0;
};

const removerDatosConyuge = () => {
  Swal.fire({
    title: "¿Eliminar?",
    text: "¿Quieres eliminar los datos del conyuge?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      datosConyuge.value = false;
      form.tipoParentescoConyuge = 0;
      form.nombresConyuge = "";
      form.edadConyuge = 0;
      form.nombreOcupacionConyuge = "";
      form.mismoInmuebleConyuge = false;
      Swal.fire({
        title: "Eliminado!",
        text: "Se retiraron los datos del conyuge",
        icon: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Entendido",
      });
    }
  });
};

const removerDatoHijo = (index) => {
  $(`#${index}`).html("");
  cantidadDeHijosIndicada.value === 0
    ? (datosHijos.value = false)
    : (datosHijos.value = true);
};

const removerDatoHermano = (index) => {
  $(`#${index}`).html("");
  cantidadDeHermanosIndicada.value === 0
    ? (datosHermanos.value = false)
    : (datosHermanos.value = true);
};

const guardarFormato = () => {
  console.log(form);
};

const getCoordinate = (event) => {
  let coordinates = this.$refs.VueCanvasDrawing.getCoordinates(event);
  this.x = coordinates.x;
  this.y = coordinates.y;
}
</script>

<template>
  <Head
    title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO"
  />

  <div class="px-0 xl:px-10 lg:px-10 md:px-10">
    <form @submit.prevent="guardarFormato">
      <Stepper value="1">
        <StepItem value="1">
          <Step>EMPRESA A LA QUE POSTULA</Step>
          <StepPanel v-slot="{ activateCallback }">
            <div class="flex flex-col mt-4">
              <div class="grid grid-cols-1 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 mb-4">
                <div class="mt-2 flex flex-col gap-2 me-2">
                  <label for="razonSocial">Razón Social</label>
                  <Select
                    v-model="form.empresa"
                    :options="props.empresas"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar razón social"
                    emptyMessage="Opciones no disponibles"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 ms-0 xl:ms-2 lg:ms-2 md:ms-2 me-2">
                  <label for="cargo">Cargo</label>
                  <Select
                    v-model="form.cargo"
                    :options="props.cargos"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar cargo"
                    emptyMessage="Opciones no disponibles"
                  />
                  <small class="text-red-500">errores</small>
                </div>
              </div>
            </div>
            <div class="pt-4 pb-6">
              <Button label="Siguiente" @click="activateCallback('2')"/>
            </div>
          </StepPanel>
        </StepItem>
        <StepItem value="2">
          <Step>DATOS PERSONALES</Step>
          <StepPanel v-slot="{ activateCallback }">
            <div class="flex flex-col mt-4">
              <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-3 sm:grid-cols-3">
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="nombres">Nombres</label>
                  <InputText
                    id="nombres"
                    v-model="form.nombres"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Nombres"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="paterno">Apellido Paterno</label>
                  <InputText
                    id="paterno"
                    v-model="form.paterno"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Apellido paterno"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="materno">Apellido Materno</label>
                  <InputText
                    id="materno"
                    v-model="form.materno"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Apellido materno"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="tipoDocumento">Tipo de Documento de Identidad</label>
                  <Select
                    v-model="form.tipoDocumento"
                    :options="props.tipoDocumento"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar tipo de documento"
                    emptyMessage="Opciones no disponibles"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="numeroDocumento">Número de Documento</label>
                  <InputText
                    id="numeroDocumento"
                    v-model="form.numeroDocumento"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Número de documento"
                    @click="datosPiePagina"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="fechaNacimiento">Fecha de Nacimiento</label>
                  <DatePicker
                    id="fechaNacimiento"
                    v-model="form.fechaNacimiento"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Fecha de nacimiento"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-3 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="lugarNacimiento">Lugar de Nacimiento</label>
                  <InputText
                    id="lugarNacimiento"
                    v-model="form.lugarNacimiento"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Lugar de nacimiento"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="estadoCivil">Estado Civil</label>
                  <Select
                    v-model="form.estadoCivil"
                    :options="props.estadoCivil"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar estado civil"
                    emptyMessage="Opciones no disponibles"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="genero">Genero</label>
                  <Select
                    v-model="form.genero"
                    :options="props.genero"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar cargo"
                    emptyMessage="Opciones no disponibles"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="distrito">Distrito</label>
                  <Select
                    v-model="form.distrito"
                    :options="distritosList"
                    filter
                    optionLabel="name"
                    placeholder="Seleccionar distrito"
                  >
                    <template #value="slotProps">
                      <div v-if="slotProps.value" class="flex items-center">
                        <div>{{ slotProps.value.name }}</div>
                      </div>
                      <span v-else>
                    {{ slotProps.placeholder }}
                  </span>
                    </template>
                    <template #option="slotProps">
                      <div class="flex items-center">
                        <div>{{ slotProps.option.name }}</div>
                      </div>
                    </template>
                  </Select>

                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="direccion">Dirección</label>
                  <InputText
                    id="direccion"
                    v-model="form.direccion"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Dirección"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="role">¿La Casa Donde Vives Es?</label>
                  <Select
                    v-model="form.tipoVivienda"
                    :options="tiposViviendasList"
                    optionValue="code"
                    optionLabel="name"
                    placeholder="Seleccionar tipo de vivienda"
                    emptyMessage="Opciones no disponibles"
                    @change="otroTipoVivienda"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                     v-if="otrotipovivienda">
                  <label for="Otro">Otro Tipo de Vivienda</label>
                  <InputText
                    id="otro"
                    v-model="form.otroTipoVivienda"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Indique otro tipo de vivienda"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="telefono">Teléfono</label>
                  <InputText
                    id="telefono"
                    v-model="form.telefono"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Telefono"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="email">Email</label>
                  <InputText
                    id="email"
                    v-model="form.email"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Email"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="brevete">Brevete</label>
                  <InputText
                    id="brevete"
                    v-model="form.brevete"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Brevete"
                  />
                  <small class="text-red-500">errores</small>
                </div>
              </div>
            </div>
            <div class="flex pt-4 mb-6 gap-2">
              <Button label="Anterior" severity="secondary" @click="activateCallback('1')"/>
              <Button label="Siguiente" @click="activateCallback('3')"/>
            </div>
          </StepPanel>
        </StepItem>
        <StepItem value="3">
          <Step>DATOS FAMILIARES</Step>
          <StepPanel v-slot="{ activateCallback }">
            <div class="flex flex-col">
              <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 mt-4 text-xl">
                <p class="font-bold text-lg">Datos del Padre</p>
              </div>

              <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 gap-2">
                <InputText
                  id="padre"
                  value="1"
                  v-model="form.tipoParentescoPadre"
                  autocomplete="off"
                  type="hidden"
                />
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="nombrespadre">Nombres y Apellidos</label>
                  <InputText
                    id="nombrespadre"
                    v-model="form.nombrespadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Nombres y apellidos"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="edadpadre">Edad</label>
                  <InputNumber
                    id="edadpadre"
                    v-model="form.edadpadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Edad"
                    :min="0"
                    :max="120"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="ocupacionpadre">Ocupación</label>
                  <InputText
                    id="ocupacionpadre"
                    v-model="form.nombresocupacionpadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Ocupación"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="mismoinmueblepadre">Vive en el mismo inmueble</label>

                  <ul
                    class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                  >
                    <li class="w-full">
                      <div class="flex items-center">
                        <input
                          id="mismoInmueblePadreSi"
                          type="radio"
                          :value="true"
                          name="mismoInmueblePadre"
                          v-model="form.mismoInmueblePadre"
                          class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                        />
                        <label
                          for="mismoInmueblePadreSi"
                          class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                        >Si</label
                        >
                      </div>
                    </li>
                    <li class="w-full">
                      <div class="flex items-center">
                        <input
                          id="mismoInmueblePadreNo"
                          type="radio"
                          :value="false"
                          name="mismoInmueblePadre"
                          v-model="form.mismoInmueblePadre"
                          class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                        />
                        <label
                          for="mismoInmueblePadreNo"
                          class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                        >No</label
                        >
                      </div>
                    </li>
                  </ul>
                </div>
              </div>


              <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 mt-4 text-xl">
                <p class="font-bold text-lg">Datos de la Madre</p>
              </div>

              <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 gap-2">
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="nombresmadre">Nombres y Apellidos</label>
                  <InputText
                    id="nombresmadre"
                    v-model="form.nombresmadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Nombres y apellidos"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="edadmadre">Edad</label>
                  <InputNumber
                    id="edadmadre"
                    v-model="form.edadmadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Edad"
                    :min="0"
                    :max="120"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="ocupacionmadre">Ocupación</label>
                  <InputText
                    id="ocupacionmadre"
                    v-model="form.nombresocupacionmadre"
                    class="flex-auto"
                    autocomplete="off"
                    placeholder="Ocupación"
                  />
                  <small class="text-red-500">errores</small>
                </div>
                <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                  <label for="mismoinmueblepadre">Vive en el mismo inmueble</label>

                  <ul
                    class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                  >
                    <li class="w-full">
                      <div class="flex items-center">
                        <input
                          id="mismoInmuebleMadreSi"
                          type="radio"
                          :value="true"
                          name="mismoInmuebleMadre"
                          v-model="form.mismoInmuebleMadre"
                          class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                        />
                        <label
                          for="mismoInmuebleMadreSi"
                          class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                        >Si</label
                        >
                      </div>
                    </li>
                    <li class="w-full">
                      <div class="flex items-center">
                        <input
                          id="mismoInmuebleMadreNo"
                          type="radio"
                          :value="false"
                          name="mismoInmuebleMadre"
                          v-model="form.mismoInmuebleMadre"
                          class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                        />
                        <label
                          for="mismoInmuebleMadreNo"
                          class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                        >No</label
                        >
                      </div>
                    </li>
                  </ul>
                </div>
              </div>

            </div>
            <div class="flex pt-4 mb-6 gap-2">
              <Button label="Anterior" severity="secondary" @click="activateCallback('2')"/>
              <Button label="Siguiente" @click="activateCallback('4')"/>
            </div>
          </StepPanel>
        </StepItem>
      </Stepper>
    </form>
  </div>
</template>
<style scoped lang="scss">
#fechaActualExamen,
#diaActualExamen,
#mesActualExamen,
#anioActualExamen,
#nombresApellidosPie,
#numeroDocumentoPie {
  border: none;
  box-shadow: none;
  border-bottom: 1px solid gray;
  border-radius: 0;
  background: transparent;

  &#diaActualExamen {
    width: 40px;
  }

  &#mesActualExamen {
    width: 140px;
  }

  &#anioActualExamen {
    width: 100px;
  }

  &#nombresApellidosPie {
    width: 400px;
  }

  &#numeroDocumentoPie {
    width: 150px;
  }
}
</style>







