<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { usePrimeVue } from "primevue/config";

const props = defineProps({
  empresas: Array,
  cargos: Array,
  distritos: Object,
  tiposviviendas: Object,
  tiposparentescos: Object,
});

const distritosList = ref([]);
const tiposViviendasList = ref([]);
const otrotipovivienda = ref(false);
let tipoParentescoList = ref([]);
const agregarParentescoActive = ref(true);

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
  tipoParentescoPadre: "",
  nombrespadre: "",
  edadpadre: 0,
  nombresocupacionpadre: "",
  mismoInmueblePadre: false,
  tipoParentescoMadre: "",
  nombresmadre: "",
  edadmadre: 0,
  nombresocupacionmadre: "",
  mismoInmuebleMadre: false,
  otroPatentesco: "",
  tipoParentescoConyuge: "",
  nombresconyuge: "",
  edadconyuge: 0,
  nombreocupacionconyuge: "",
  mismoInmuebleConyuge: false,
});

const removerParentescoSeleccionado = (parentescoId) => {
  tipoParentescoList.value.map((e, index) => {
    if (e.code === parentescoId) {
      tipoParentescoList.value.splice(index, 1);
    }
  });

  agregarParentescoActive.value = true;

  return tipoParentescoList;
};

// onbeforeunload = (event) => {
//     event.preventDefault();
// };

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
    tipoParentescoList.value.push({
      name: e.tipo_parentesco,
      code: e.id,
    });
  });


});

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
    $("#datosConyuge").append(`
      <div class="mt-2 flex flex-col gap-2 me-4 col-span-4">
          <p class="font-bold text-lg">Datos del Conyuge <button type="button" class="text-white bg-[#B00202] hover:bg-[#CF6767] font-medium rounded-full text-sm px-3 py-2 text-center ms-4" onclick="alert('quitar conyuge');">Quitar</button>
          </p>
      </div>

      <div class="grid grid-cols-5 gap-2">
          <input type="hidden" id="tipoParentescoConyuge" name="tipoParentescoConyuge" value="3" autocomplete="off">
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
              <label for="nombresconyuge">Nombres y Apellidos</label>
              <input type="text" id="nombresconyuge" name="nombresconyuge" autocomplete="off" placeholder="Nombres y apellidos" class="flex-auto rounded-md border-[#CBD5E1] hover:border-[#919FB4] focus:border-none"/>
              <small class="text-red-500">errores</small>
          </div>
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
              <label for="edadconyuge">Edad</label>
              <input type="number" id="edadconyuge" name="edadconyuge" autocomplete="off" placeholder="Edad" class="flex-auto rounded-md border-[#CBD5E1] hover:border-[#919FB4] focus:border-none" min="1" max="110" aria-controls="none" inputmode="numeric"/>
              <small class="text-red-500">errores</small>
          </div>
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
              <label for="nombreocupacionconyuge">Ocupación</label>
              <input type="text" id="nombreocupacionconyuge" name="nombreocupacionconyuge" autocomplete="off" placeholder="Ocupacion" class="flex-auto rounded-md border-[#CBD5E1] hover:border-[#919FB4] focus:border-none"/>
              <small class="text-red-500">errores</small>
          </div>
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
              <label for="mismoinmuebleconyuge"
                  >Vive en el mismo inmueble</label
              >

              <ul
                  class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
              >
                  <li class="w-full">
                      <div class="flex items-center">
                          <input
                              id="mismoInmuebleConyugeSi"
                              type="radio"
                              value="true"
                              name="mismoInmuebleConyuge"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                          />
                          <label
                              for="mismoInmuebleConyugeSi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >Si</label
                          >
                      </div>
                  </li>
                  <li class="w-full">
                      <div class="flex items-center">
                          <input
                              id="mismoInmuebleConyugeNo"
                              type="radio"
                              value="false"
                              name="mismoInmuebleConyuge"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                          />
                          <label
                              for="mismoInmuebleConyugeNo"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >No</label
                          >
                      </div>
                  </li>
              </ul>
          </div>
      </div>
    `);

    removerParentescoSeleccionado(parentesco);
  }

  if (parentesco === 2) {
    removerParentescoSeleccionado(parentesco);
  }

  if (parentesco === 3) {
    removerParentescoSeleccionado(parentesco);
  }
};

const guardarFormato = () => {
  fomr.tipoParentescoConyuge = $("#tipoParentescoConyuge").val();
  form.nombresconyuge = $("#nombresconyuge").val();
  form.edadconyuge = $("#edadconyuge").val();
  form.nombreocupacionconyuge = $("#nombreocupacionconyuge").val();
  form.mismoInmuebleConyuge =
    $('input[name="mismoInmuebleConyuge"]:checked').prop("value") === "true";

  console.log(form);
};
</script>

<template>
  <Head
    title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO"
  />
  <div class="card flex justify-center">
    <div class="border border-gray-300 rounded-lg p-4 w-full md:w-10/12">
      <form @submit.prevent="guardarFormato">
        <!-- Recordar que el codigo debe ser del poligrafista que esta activando el formato verificar que al momento de activar la opcion para que cliente pueda rellenar el formato se obtenga el codigo del poligrafista para su posterior envio (Revisarlo en el modelo de datos) -->
        <div
          class="grid grid-cols-1 xl:grid-col-4 lg:grid-col-4 md:grid-cols-4"
        >
          <div class="border border-gray-500 flex justify-center items-center">
            <img
              src="/demo/images/logo.png"
              width="166"
              alt="logo"
              class="p-8"
            />
          </div>
          <div
            class="col-span-2 border border-gray-500 text-center flex justify-center items-center w-full px-10 py-4 xl:py-0 lg:py-0"
          >
            <h4 class="text-lg font-bold">
              FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE -
              EMPLEO
            </h4>
          </div>
          <div
            class="border border-gray-500 text-end flex justify-center items-center w-full py-4 xl:py-0 lg:py-0 md:px-2"
          >
            <div class="grid grid-cols-2 py-4">
              <div class="text-start">
                <p class="font-bold">Código:</p>
              </div>
              <div class="text-end">
                <p>GAC-F-58</p>
              </div>
              <div class="text-start">
                <p class="font-bold">Versión:</p>
              </div>
              <div class="text-end">
                <p>01</p>
              </div>
              <div class="text-start">
                <p class="font-bold">Fecha:</p>
              </div>
              <div class="text-end">
                <p>03/04/2025</p>
              </div>
              <div class="col-span-2 text-center">
                <p class="font-bold">Informe:</p>
              </div>
              <div class="text-center col-span-2">
                <p>INF 03-2025</p>
              </div>
            </div>
          </div>
        </div>

        <div class="my-4 text-justify">
          <div
            class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50"
            role="alert"
          >
            <span class="font-medium text-lg">
              El presente formulario debe ser llenado en su totalidad;
              agradeceremos responder en forma precisa y con sinceridad en todo
              el formulario sin excepción, cualquier pregunta realizarla al
              personal de atención al cliente.
            </span>
          </div>
        </div>

        <div class="grid grid-cols-2 mb-4">
          <div
            class="col-span-2 bg-[#B00202] font-black p-2 text-white rounded-md"
          >
            EMPRESA A LA QUE POSTULA
          </div>
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
          <div class="mt-2 flex flex-col gap-2 ms-2 me-4">
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

        <div class="grid grid-cols-3">
          <div
            class="col-span-3 bg-[#B00202] font-black p-2 text-white rounded-md"
          >
            DATOS PERSONALES
          </div>
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
            <label for="numeroDocumento">Número de Documento</label>
            <InputText
              id="numeroDocumento"
              v-model="form.numeroDocumento"
              class="flex-auto"
              autocomplete="off"
              placeholder="Número de documento"
            />
            <small class="text-red-500">errores</small>
          </div>
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 col-span-3 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 col-span-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div
            class="mt-2 col-span-2 flex flex-col gap-2 me-4"
            v-if="otrotipovivienda"
          >
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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
          <div class="mt-2 flex flex-col gap-2 me-4">
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

        <div
          class="col-span-4 bg-[#B00202] font-black p-2 text-white rounded-md"
        >
          DATOS FAMILIARES
        </div>

        <div class="mt-2 flex flex-col gap-2 me-4 col-span-4">
          <p class="font-bold text-lg">Datos del Padre</p>
        </div>

        <div class="grid grid-cols-5 gap-2">
          <InputText
            id="padre"
            value="1"
            v-model="form.tipoParentescoPadre"
            autocomplete="off"
            type="hidden"
          />
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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

        <div class="mt-2 flex flex-col gap-2 me-4 col-span-4">
          <p class="font-bold text-lg">Datos de la Madre</p>
        </div>

        <div class="grid grid-cols-5 gap-2">
          <InputText
            id="madre"
            value="2"
            v-model="form.tipoParentescoMadre"
            autocomplete="off"
            type="hidden"
          />
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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
          <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
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

        <div class="grid grid-cols-2 mb-4">
          <div class="mt-2 flex flex-col gap-2 me-4">
            <label for="parentesco">Parentesco</label>
            <Select
              :options="tipoParentescoList"
              optionValue="code"
              optionLabel="name"
              placeholder="Seleccionar parentescos"
              emptyMessage="Opciones no disponibles"
              v-model="form.otroPatentesco"
              @change="agregarParentescoActive = false"
            />
            <!--                        <small class="text-red-500">errores</small>-->
          </div>
          <div class="flex flex-col mt-10 w-1/3">
            <Button
              label="Agreagar parentesco"
              icon="pi pi-plus"
              @click="agregarParentesco(form.otroPatentesco)"
              :rounded="true"
              :disabled="agregarParentescoActive"
            />
          </div>
          <small class="ms-1 mt-2 text-lg text-[#B00202]"
            >Seleccione cuales son los parentescos con los que cuenta.</small
          >
        </div>

        <div id="datosConyuge"></div>

        <Button type="submit" label="Terminar" />
      </form>
    </div>
  </div>
</template>
