<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import {onMounted, ref, watchEffect} from "vue";
import {usePrimeVue} from "primevue/config";
import {loadToast} from '@/composables/loadToast';
import {validateForm} from '@/composables/validateForm';

import VueDrawingCanvas from "vue-drawing-canvas";

loadToast();

const props = defineProps({
  show: Boolean,
  cargos: Array,
  distritos: Object,
  entidadesbancarias: Object,
  estadosciviles: Object,
  generos: Object,
  gradosinstrucciones: Object,
  nacionalidades: Object,
  razonessociales: Object,
  tiposviviendas: Object,
  tiposparentescos: Object,
  tiposdocumentos: Object,
  datosevaluado: Object,
});

const emit = defineEmits(["close"]);

const otrotipovivienda = ref(false);
const agregarParentescoActive = ref(true);
const datosConyuge = ref(false);
const cantidadHijos = ref(false);
const datosHijos = ref(false);
const cantidadDeHijosIndicada = ref(0);
const datosHermanos = ref(false);
const cantidadDeHermanosIndicada = ref(0);
const cantidadHermanos = ref(false);
const nombresCompletosPiePagina = ref("");
const image = ref("");
const tiposViviendasList = ref([]);
const distritosList = ref([]);
const tipoParentescoList = ref([]);
const entidadesBancariasList = ref([]);
const gradosInstruccionesList = ref([]);
const empresasList = ref([]);
const tiposdocumentosList = ref([]);
const generosList = ref([]);
const estadoscivilesList = ref([]);
const nacionalidadesList = ref([]);
const cargosLista = ref([]);
const errors = ref(false);
const errorsList = ref([]);
const mesesListCalendatio = ref(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",]);
const numeroMesExamen = ref(0);
const nombreMesExamen = ref("");
const diaMesExamen = ref(0);
const anioExamen = ref(0);
const usuarioId = ref(props.datosevaluado.poligrafista_id);
const nroEvals = ref(props.datosevaluado.numero_evaluaciones);

const getDate = new Date();
const foto = ref(null)
const errorFoto = ref(true)
const fotoError = ref("")

const form = useForm({
  empresa: "",
  cargo: "",
  nombres: "",
  paterno: "",
  materno: "",
  nacionalidad: 0,
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
  brevete: "",
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
  tieneConyuge: false,
  tipoParentescoConyuge: 0,
  nombresConyuge: "",
  edadConyuge: 0,
  nombreOcupacionConyuge: "",
  mismoInmuebleConyuge: false,
  tieneHijos: false,
  tipoParentescoHijos: 0,
  cantidadHijos: 0,
  nombresHijos: [],
  edadesHijos: [],
  nombreOcupacionesHijos: [],
  mismoInmuebleHijos: [],
  tieneHermanos: false,
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
  cuotaMensual: 0,
  otroIngreso: false,
  preciseMonto: 0,
  origenOtroIngreso: "",
  tienePropiedades: false,
  detallePropiedades: "",
  reportadoEnCentralesDeRiesgo: false,
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
  pandilleros: false,
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
  imagenFirma: "",
  ciudadExamen: "",
  usuarioId: 0,
  consentimiento: false,
  codigoPoligrafista: "",
  numeroEvaluaciones: "",
  foto: "",
});

onbeforeunload = (event) => {
  event.preventDefault();
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

  props.razonessociales.map((e) => {
    empresasList.value.push({
      name: e.nombre_comercial,
      code: e.id
    })
  });

  props.tiposdocumentos.map((e) => {
    tiposdocumentosList.value.push({
      name: e.tipo_documento,
      code: e.id,
    })
  });

  props.generos.map((e) => {
    generosList.value.push({
      name: e.genero,
      code: e.id,
    })
  });

  props.estadosciviles.map((e) => {
    estadoscivilesList.value.push({
      name: e.estado_civil,
      code: e.id,
    })
  });

  props.nacionalidades.map((e) => {
    nacionalidadesList.value.push({
      name: e.nacionalidad,
      code: e.id,
    })
  });

  props.cargos.map((e) => {
    cargosLista.value.push({
      name: e.cargo,
      code: e.id,
    })
  })


  numeroMesExamen.value = getDate.toLocaleDateString().slice(2, -4).replaceAll('/', '') - 1;
  nombreMesExamen.value = mesesListCalendatio.value[numeroMesExamen.value];
  diaMesExamen.value = (getDate.getMonth() + 1).toString().padStart(2, "0");
  anioExamen.value = getDate.getFullYear();
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
  primevue.config.locale.monthNames = mesesListCalendatio.value;
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
        confirmButtonColor: "#10B981",
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
  if (form.cantidadHijos === 0) {
    Swal.fire({
      title: "Ooops!",
      text: "Debe indicar la cantidad de hijos que tiene",
      icon: "info",
      draggable: true,
      confirmButtonColor: "#10B981",
      confirmButtonText: "Entendido",
    });
  } else {
    datosHijos.value = true;
    cantidadDeHijosIndicada.value = parseInt(form.cantidadHijos);
    cantidadHijos.value = false;
  }
};

const agregarCantidadHermanos = () => {
  datosHermanos.value = true;
  cantidadDeHermanosIndicada.value = parseInt(form.cantidadHermanos);
  cantidadHermanos.value = false;
};

const removerDatosConyuge = () => {
  Swal.fire({
    title: "¿Eliminar?",
    text: "¿Quieres eliminar los datos del conyuge?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#10B981",
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
        confirmButtonColor: "#10B981",
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
  form.cantidadHijos -= 1;
};

const removerDatoHermano = (index) => {
  $(`#${index}`).html("");
  cantidadDeHermanosIndicada.value === 0
    ? (datosHermanos.value = false)
    : (datosHermanos.value = true);
  form.cantidadHermanos -= 1;
};

watchEffect(() => {
  if (props.show) {
    form.errors = {};
  }
});

const getCoordinate = (event) => {
  let coordinates = this.$refs.VueDrawingCanvas.getCoordinates(event);
  this.x = coordinates.x;
  this.y = coordinates.y;
}

const handleFile = (e) => {
  foto.value = e.target.files[0];
  const maxSizeMB = 3;
  const maxSizeBytes = maxSizeMB * 1024 * 1024 * 1024;

  if (foto.value.size > maxSizeBytes) {
    fotoError.value = `El archivo supera los ${maxSizeMB} MB, es muy pesado`;
    errorFoto.value = true;
    event.target.value = '';
  }else{
    fotoError.value = "";
    errorFoto.value = false;
  }
}

const registrarFormato = () => {
  form.foto = foto.value;
  form.tieneConyuge = datosConyuge.value;
  form.tieneHijos = datosHijos.value;
  form.tieneHermanos = datosHermanos.value;
  form.usuarioId = usuarioId.value;
  form.numeroEvaluaciones = nroEvals.value;
  validateForm(form, errors, errorsList);

  if (!errors.value) {
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
        if (!errors.value) {
          Swal.fire({
            icon: 'info',
            html: "Espere un momento porfavor ...",
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          form.post(route('formato.store'), {
            headers: {
              'Content-Type': 'multipart/form-data'
            },
            preserveScroll: true,
            onSuccess: () => {
              emit("close");
              // form.reset();
              Swal.fire({
                title: "Formato Registrado!",
                text: "Tu formato 1 fue registrado correctamente!",
                icon: "success",
                allowOutsideClick: false,
                confirmButtonColor: "#10B981",
                confirmButtonText: "Siguiente Formato",
              }).then((result) => {
                if (result.isConfirmed) {
                  form.codigoPoligrafista = props.datosevaluado.codigo_poligrafista + "" + props.datosevaluado.numero_evaluaciones
                  //redireccionar al siguiente formato enviando los datos necesarios para evitar el rellenado de datos erroneos
                  form.post(route('formato.dos'), {
                    preventScroll: true,
                    onSuccess: () => null,
                    onError: () => null,
                    onFinish: () => null,
                  });
                }
              });
            },
            onError: () => null,
            onFinish: () => null,
          })
        }
      }
    });
  }
};

</script>

<template>
  <Head
    title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO"
  />

  <div class="px-0 xl:px-10 lg:px-10 md:px-10">
    <form @submit.prevent="registrarFormato">
      <Card class="my-6">
        <template #content>
          <div class="my-4">
            <div class="grid grid-cols-1 xl:grid-col-4 lg:grid-col-4 md:grid-cols-4">
              <div class="border-none flex justify-center items-center">
                <img
                  src="/demo/images/ESCUDO-RGB.png"
                  width="130"
                  alt="logo"
                  class="p-8"
                />
              </div>
              <div
                class="col-span-2 border-none text-center flex justify-center items-center w-full px-10 py-4 xl:py-0 lg:py-0"
              >
                <h4 class="text-lg font-bold">
                  FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE -
                  EMPLEO
                </h4>
              </div>
              <div class="border-none text-end flex justify-center items-center w-full py-4 xl:py-0 lg:py-0 md:px-2">
                <div class="grid grid-cols-2 py-4">
                  <div class="text-start">
                    <p class="font-bold">Código:</p>
                  </div>
                  <div class="text-end">
                    <p>{{ props.datosevaluado.codigo_poligrafista }}{{ props.datosevaluado.numero_evaluaciones }}</p>
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
                    <p>{{ getDate.toLocaleDateString() }}</p>
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
          </div>
        </template>
      </Card>

      <Card class="my-6">
        <template #content>
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
                        :options="empresasList"
                        filter
                        optionLabel="name"
                        placeholder="Seleccionar razón social"
                        emptyMessage="Opciones no disponibles"
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
                    </div>
                    <div class="mt-2 flex flex-col gap-2 ms-0 xl:ms-2 lg:ms-2 md:ms-2 me-2">
                      <label for="cargo">Cargo</label>
                      <Select
                        v-model="form.cargo"
                        :options="cargosLista"
                        filter
                        optionLabel="name"
                        placeholder="Seleccionar cargo"
                        emptyMessage="Opciones no disponibles"
                      />
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
                  <div class="grid grid-cols-1 xl:grid-cols-5 lg:grid-cols-3 sm:grid-cols-3">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="nombres">Nombres</label>
                      <InputText
                        id="nombres"
                        v-model="form.nombres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Nombres"
                      />

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

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="nacionalidad">Nacionalidad</label>
                      <Select
                        v-model="form.nacionalidad"
                        :options="nacionalidadesList"
                        filter
                        optionLabel="name"
                        placeholder="Seleccionar nacionalidad"
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


                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="tipoDocumento">Tipo de Documento de Identidad</label>
                      <Select
                        v-model="form.tipoDocumento"
                        :options="tiposdocumentosList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar tipo de documento"
                        emptyMessage="Opciones no disponibles"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="numeroDocumento">Número de Documento</label>
                      <InputText
                        id="numeroDocumento"
                        v-model="form.numeroDocumento"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Número de documento"
                        @focus="datosPiePagina"
                        @click="datosPiePagina"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechaNacimiento">Fecha de Nacimiento</label>
                      <input
                        type="date"
                        id="fechaNacimiento"
                        v-model="form.fechaNacimiento"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha de nacimiento"
                        :manualInput="false"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="lugarNacimiento">Lugar de Nacimiento</label>
                      <InputText
                        id="lugarNacimiento"
                        v-model="form.lugarNacimiento"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Lugar de nacimiento"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="estadoCivil">Estado Civil</label>
                      <Select
                        v-model="form.estadoCivil"
                        :options="estadoscivilesList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar estado civil"
                        emptyMessage="Opciones no disponibles"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="genero">Genero</label>
                      <Select
                        v-model="form.genero"
                        :options="generosList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar genero"
                        emptyMessage="Opciones no disponibles"
                      />

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

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="file_input">Subir Foto</label>
                      <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none h-11"
                        id="file_input"
                        type="file"
                        @change="handleFile"
                        accept="image/*">
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2" v-if="errorFoto">
                      <p>{{fotoError}}</p>
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
                  <!--Datos del padre-->
                  <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 mt-4 text-xl">
                    <p class="font-bold text-lg">Datos del Padre</p>
                  </div>

                  <div class="grid grid-cols-1 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 gap-2">
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

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="edadpadre">Edad</label>
                      <input
                        type="number"
                        id="edadpadre"
                        v-model="form.edadpadre"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Edad"
                        :min="0"
                        :max="120"
                      />

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

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="mismoinmueblepadre">Vive en el mismo inmueble</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
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

                  <!--Datos de la madre-->
                  <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 mt-4 text-xl">
                    <p class="font-bold text-lg">Datos de la Madre</p>
                  </div>

                  <div class="grid grid-cols-1 xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 ">
                      <label for="nombresmadre">Nombres y Apellidos</label>
                      <InputText
                        id="nombresmadre"
                        v-model="form.nombresmadre"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Nombres y apellidos"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="edadmadre">Edad</label>
                      <input
                        type="number"
                        id="edadmadre"
                        v-model="form.edadmadre"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Edad"
                        :min="0"
                        :max="120"
                      />

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

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="mismoinmueblepadre">Vive en el mismo inmueble</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
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

                  <!--Datos de familiares adicionales-->
                  <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-3 mt-4 text-xl">
                    <p class="font-bold text-lg">Otros Parentesco</p>
                  </div>

                  <div class="grid grid-cols-2 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-4 gap-2">
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-1 xl:col-span-1 lg:col-span-1 md:col-span-1 w-full lg:w-auto">
                      <label for="parentesco">Selecciona Parentesco</label>
                      <Select
                        :options="tipoParentescoList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar parentescos"
                        emptyMessage="Opciones no disponibles"
                        v-model="form.otroPatentesco"
                        @change="agregarParentescoActive = false"
                      />
                      <!---->
                    </div>

                    <div
                      class="flex flex-col xl:mt-10 mt-10 lg:mt-10 md:mt-10 sm:mt-10 col-span-1 xl:col-span-1 lg:col-span-1 md:col-span-1 w-1/3">
                      <Button
                        icon="pi pi-plus"
                        @click="agregarParentesco(form.otroPatentesco)"
                        :rounded="true"
                        :disabled="agregarParentescoActive"
                      />
                    </div>

                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-1 my-0 xl:my-6 lg:my-6 md:my-6 w-full lg:w-auto"
                         v-if="cantidadHijos">
                      <label for="cantidad">¿Cúantos Hijos Tiene?</label>
                      <InputText
                        id="cantidad"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cantidad"
                        v-model="form.cantidadHijos"
                      />
                    </div>

                    <div class="flex flex-col xl:mt-14 mt-10 lg:mt-14 md:mt-14 sm:mt-10 col-span-1 w-1/2"
                         v-if="cantidadHijos">
                      <Button
                        icon="pi pi-plus"
                        :rounded="true"
                        :disabled="agregarParentescoActive"
                        @click="agregarCantidadHijos"
                      />
                    </div>

                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-1 my-0 xl:my-6 lg:my-6 md:my-2 w-full lg:w-auto"
                         v-if="cantidadHermanos">
                      <label for="cantidad">¿Cúantos Hermanos Tiene?</label>
                      <InputText
                        id="cantidad"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cantidad"
                        v-model="form.cantidadHermanos"
                      />
                    </div>

                    <div class="flex flex-col xl:mt-14 mt-10 lg:mt-14 md:mt-10 sm:mt-10 col-span-1 w-1/2"
                         v-if="cantidadHermanos">
                      <Button
                        icon="pi pi-plus"
                        :rounded="true"
                        :disabled="agregarParentescoActive"
                        @click="agregarCantidadHermanos"
                      />
                    </div>
                  </div>

                  <!--Datos familiares adicionales-->
                  <!--datos del conyuge-->
                  <div v-if="datosConyuge" class="my-4">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-4">
                      <p class="font-bold text-lg">Datos del Conyuge</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 gap-2">
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
                        <label for="nombresconyuge">Nombres y Apellidos</label>
                        <InputText
                          id="nombresconyuge"
                          v-model="form.nombresConyuge"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Nombres y apellidos"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-0 md:me-4 col-span-1">
                        <label for="edadconyuge">Edad</label>
                        <InputNumber
                          id="edadconyuge"
                          v-model="form.edadConyuge"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Edad"
                          :min="0"
                          :max="120"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-0 md:me-4 col-span-1">
                        <label for="ocupacionconyuge">Ocupación</label>
                        <InputText
                          id="ocupacionconyuge"
                          v-model="form.nombreOcupacionConyuge"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Ocupación"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label for="mismoinmuebleconyuge"
                        >Vive en el mismo inmueble</label
                        >

                        <ul
                          class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                        >
                          <li class="w-full">
                            <div class="flex items-center">
                              <input
                                id="mismoInmuebleConyugeSi"
                                type="radio"
                                :value="true"
                                name="mismoInmuebleConyuge"
                                v-model="form.mismoInmuebleConyuge"
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
                                :value="false"
                                name="mismoInmuebleConyuge"
                                v-model="form.mismoInmuebleConyuge"
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
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <button
                          type="button"
                          class="bg-red-900 ms-4 rounded-full py-2.5 w-1/6 mt-8"
                          @click="removerDatosConyuge"
                        >
                          <i class="pi pi-eraser text-white"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!--datos de los hijos-->
                  <div v-if="datosHijos" class="my-4">
                    <div class="flex flex-col gap-2 me-4 col-span-4">
                      <p class="font-bold text-lg">Datos de los Hijos</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 gap-2"
                         v-for="item in cantidadDeHijosIndicada" :key="item" :id="'hijosid_' + item">
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
                        <label :for="'nombreshijos' + item">Nombres y Apellidos</label>
                        <InputText
                          :inputId="'nombreshijos' + item"
                          v-model="form.nombresHijos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Nombres y apellidos"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label :for="'edadchijos' + item">Edad</label>
                        <InputNumber
                          :id="'edadchijos' + item"
                          v-model="form.edadesHijos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Edad"
                          :min="0"
                          :max="120"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label :for="'ocupacioneshijos' + item">Ocupación</label>
                        <InputText
                          :id="'ocupacioneshijos' + item"
                          v-model="form.nombreOcupacionesHijos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Ocupación"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label for="mismoinmuebleconyuge"
                        >Vive en el mismo inmueble</label
                        >

                        <ul
                          class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                        >
                          <li class="w-full">
                            <div class="flex items-center">
                              <input
                                :id="'mismoInmuebleHijosSi' + item"
                                type="radio"
                                :value="true"
                                :name="'mismoInmuebleHijos' + item"
                                v-model="form.mismoInmuebleHijos[item]"
                                class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                              />
                              <label
                                :for="'mismoInmuebleHijosSi' + item"
                                class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >Si</label
                              >
                            </div>
                          </li>
                          <li class="w-full">
                            <div class="flex items-center">
                              <input
                                :id="'mismoInmuebleHijosNo' + item"
                                type="radio"
                                :value="false"
                                :name="'mismoInmuebleHijos' + item"
                                v-model="form.mismoInmuebleHijos[item]"
                                class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                              />
                              <label
                                :for="'mismoInmuebleHijosNo' + item"
                                class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >No</label
                              >
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <button
                          type="button"
                          class="bg-red-900 ms-4 rounded-full py-2.5 w-1/6 mt-8"
                          @click="removerDatoHijo('hijosid_' + item)"
                        >
                          <i class="pi pi-eraser text-white"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <!--datos de los hermanos-->
                  <div v-if="datosHermanos" class="my-4">
                    <div class="flex flex-col gap-2 me-4 col-span-4">
                      <p class="font-bold text-lg">Datos de los Hermanos</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 gap-2"
                         v-for="item in cantidadDeHermanosIndicada" :key="item" :id="'hermanosid_' + item">
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-2">
                        <label :for="'nombreshermanos' + item">Nombres y Apellidos</label>
                        <InputText
                          :inputId="'nombreshermanos' + item"
                          v-model="form.nombresHermanos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Nombres y apellidos"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label :for="'edadhermanos' + item">Edad</label>
                        <InputNumber
                          :id="'edadhermanos' + item"
                          v-model="form.edadesHermanos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Edad"
                          :min="0"
                          :max="120"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label :for="'ocupacioneshermanos' + item">Ocupación</label>
                        <InputText
                          :id="'ocupacioneshermanos' + item"
                          v-model="form.nombreOcupacionesHermanos[item]"
                          class="flex-auto"
                          autocomplete="off"
                          placeholder="Ocupación"
                        />

                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <label for="mismoinmueblehermanos"
                        >Vive en el mismo inmueble</label
                        >

                        <ul
                          class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                        >
                          <li class="w-full">
                            <div class="flex items-center">
                              <input
                                :id="'mismoInmuebleHermanosSi' + item"
                                type="radio"
                                :value="true"
                                :name="'mismoInmuebleHermanos' + item"
                                v-model="form.mismoInmuebleHermanos[item]"
                                class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                              />
                              <label
                                :for="'mismoInmuebleHermanosSi' + item"
                                class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >Si</label
                              >
                            </div>
                          </li>
                          <li class="w-full">
                            <div class="flex items-center">
                              <input
                                :id="'mismoInmuebleHermanosNo' + item"
                                type="radio"
                                :value="false"
                                :name="'mismoInmuebleHermanos' + item"
                                v-model="form.mismoInmuebleHermanos[item]"
                                class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                              />
                              <label
                                :for="'mismoInmuebleHermanosNo' + item"
                                class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                              >No</label
                              >
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div class="mt-2 flex flex-col gap-2 me-4 col-span-1">
                        <button
                          type="button"
                          class="bg-red-900 ms-4 rounded-full py-2.5 w-1/6 mt-8"
                          @click="removerDatoHermano('hermanosid_' + item)"
                        >
                          <i class="pi pi-eraser text-white"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('2')"/>
                  <Button label="Siguiente" @click="activateCallback('4')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="4">
              <Step>FORMACIÓN ACADÉMICA</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <p class="me-4">Mencione los 3 últimos estudios realizados o que cursa actualmente</p>

                  <!--datos de la formacion academica-->
                  <p class="mt-5 text-lg font-bold">Estudios 1</p>
                  <div class="grid grid-cols-2 xl:grid-cols-9 lg:grid-cols-7 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="gradoInstruccionuno">Grado de Instrucción</label>
                      <Select
                        v-model="form.gradoInstruccionUno"
                        :options="gradosInstruccionesList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar grado de instruccion"
                        emptyMessage="Opciones no disponibles"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="centroestudiosuno">Centro de Estudios</label>
                      <InputText
                        id="centroestudios"
                        v-model="form.centroEstudioUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Centro de estudios"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="especialidadfacultaduno">Especialidad/Facultad</label>
                      <InputText
                        id="especialidadfacultad"
                        v-model="form.especialidadesFacultadUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Especialidad/Facultad"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="inicioestudiouno">Inicio</label>
                      <input
                        type="date"
                        id="inicioestudiouno"
                        v-model="form.inicioEstudiosUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Inicio"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="terminoestudiouno">Término</label>
                      <input
                        type="date"
                        id="terminoestudiouno"
                        v-model="form.terminoEstudiosUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Termino"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="situacionuno">Situación</label>

                      <ul
                        class="block items-center w-full text-sm font-medium text-gray-900 bg-white"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionUnoSi"
                              type="radio"
                              :value="true"
                              name="situacionUno"
                              v-model="form.situacionUno"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionUnoSi"
                              class="w-full ms-2 text-sm font-medium text-gray-900"
                            >Completa</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionUnoNo"
                              type="radio"
                              :value="false"
                              name="situacionUno"
                              v-model="form.situacionUno"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionUnoNo"
                              class="w-full py-1 ms-2 text-sm font-medium text-gray-900"
                            >Incompleta</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <p class="mt-5 text-lg font-bold">Estudios 2</p>
                  <div class="grid grid-cols-2 xl:grid-cols-9 lg:grid-cols-7 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="gradoInstrucciondos">Grado de Instrucción</label>
                      <Select
                        v-model="form.gradoInstruccionDos"
                        :options="gradosInstruccionesList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar grado de instruccion"
                        emptyMessage="Opciones no disponibles"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="centroestudiosdos">Centro de Estudios</label>
                      <InputText
                        id="centroestudiosdos"
                        v-model="form.centroEstudioDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Centro de estudios"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="especialidadfacultaddos">Especialidad/Facultad</label>
                      <InputText
                        id="especialidadfacultaddos"
                        v-model="form.especialidadesFacultadDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Especialidad/Facultad"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="inicioestudiodos">Inicio</label>
                      <input
                        type="date"
                        id="inicioestudiodos"
                        v-model="form.inicioEstudiosDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Inicio"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="terminoestudiodos">Término</label>
                      <input
                        type="date"
                        id="terminoestudiodos"
                        v-model="form.terminoEstudiosDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Termino"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="situaciondos">Situación</label>

                      <ul
                        class="block items-center w-full text-sm font-medium text-gray-900 bg-white"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionDosSi"
                              type="radio"
                              :value="true"
                              name="situacionDos"
                              v-model="form.situacionDos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionDosSi"
                              class="w-full ms-2 text-sm font-medium text-gray-900"
                            >Completa</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionDosNo"
                              type="radio"
                              :value="false"
                              name="situacionDos"
                              v-model="form.situacionDos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionDosNo"
                              class="w-full py-1 ms-2 text-sm font-medium text-gray-900"
                            >Incompleta</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <p class="mt-5 text-lg font-bold">Estudios 3</p>
                  <div class="grid grid-cols-2 xl:grid-cols-9 lg:grid-cols-7 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="gradoInstruccionTres">Grado de Instrucción</label>
                      <Select
                        v-model="form.gradoInstruccionTres"
                        :options="gradosInstruccionesList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar grado de instruccion"
                        emptyMessage="Opciones no disponibles"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="centroestudiostres">Centro de Estudios</label>
                      <InputText
                        id="centroestudiostres"
                        v-model="form.centroEstudioTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Centro de estudios"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="especialidadfacultadtres"
                      >Especialidad/Facultad</label
                      >
                      <InputText
                        id="especialidadfacultadtres"
                        v-model="form.especialidadesFacultadTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Especialidad/Facultad"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="inicioestudiotres">Inicio</label>
                      <input
                        type="date"
                        id="inicioestudiotres"
                        v-model="form.inicioEstudiosTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Inicio"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="terminoestudiotres">Término</label>
                      <input
                        type="date"
                        id="terminoestudiotres"
                        v-model="form.terminoEstudiosTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Termino"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="situaciontres">Situación</label>

                      <ul
                        class="block items-center w-full text-sm font-medium text-gray-900 bg-white"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionTresSi"
                              type="radio"
                              :value="true"
                              name="situacionTres"
                              v-model="form.situacionTres"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionTresSi"
                              class="w-full ms-2 text-sm font-medium text-gray-900"
                            >Completa</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="situacionTresNo"
                              type="radio"
                              :value="false"
                              name="situacionTres"
                              v-model="form.situacionTres"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="situacionTresNo"
                              class="w-full py-1 ms-2 text-sm font-medium text-gray-900"
                            >Incompleta</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>

                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('3')"/>
                  <Button label="Siguiente" @click="activateCallback('5')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="5">
              <Step>EXPERIENCIA LABORAL</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <p class="me-4">Indique los 3 últimos empleos, empezando por el más reciente</p>

                  <!--datos de la experencia laboral-->
                  <p class="mt-5 text-lg font-bold">Empleo 1</p>
                  <div class="grid grid-cols-2 xl:grid-cols-8 lg:grid-cols-5 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="experencialaboraluno">Empresa</label>
                      <InputText
                        id="experencialaboraluno"
                        v-model="form.empresaUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Experiencia laboral"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechaingresouno">Fecha Ingreso</label>
                      <input
                        type="month"
                        id="fechaingresouno"
                        v-model="form.fechaIngresoUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha ingreso"
                      />
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechasalidauno">Fecha Salida</label>
                      <input
                        type="month"
                        id="fechasalidauno"
                        v-model="form.fechaSalidaUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha salida"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="sueldouno">Sueldo</label>
                      <input
                        type="number"
                        id="sueldouno"
                        v-model="form.sueldoUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Sueldo"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="cargodesempeniauno">Cargo Desempeña</label>
                      <InputText
                        id="cargodesempeniauno"
                        v-model="form.cargoDesempenioUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cargo desempeña"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="motivosalidauno">Motivo De Su Salida</label>
                      <Textarea
                        id="motivosalidauno"
                        v-model="form.motivoSalidaUno"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Motivo de su salida"
                        rows="1" cols="30"
                      />

                    </div>
                  </div>
                  <p class="mt-5 text-lg font-bold">Empleo 2</p>
                  <div class="grid grid-cols-2 xl:grid-cols-8 lg:grid-cols-5 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="experencialaboraldos">Empresa</label>
                      <InputText
                        id="experencialaboraldos"
                        v-model="form.empresaDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Experiencia laboral"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechaingresodos">Fecha Ingreso</label>
                      <input
                        type="month"
                        id="fechaingresodos"
                        v-model="form.fechaIngresoDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha ingreso"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechasalidados">Fecha Salida</label>
                      <input
                        type="month"
                        id="fechasalidados"
                        v-model="form.fechaSalidaDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha salida"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="sueldodos">Sueldo</label>
                      <input
                        type="number"
                        id="sueldodos"
                        v-model="form.sueldoDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Sueldo"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="cargodesempeniados">Cargo Desempeña</label>
                      <InputText
                        id="cargodesempeniados"
                        v-model="form.cargoDesempenioDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cargo desempeña"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="motivosalidados">Motivo De Su Salida</label>
                      <Textarea
                        id="motivosalidados"
                        v-model="form.motivoSalidaDos"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Motivo de su salida"
                        rows="1" cols="30"
                      />

                    </div>
                  </div>
                  <p class="mt-5 text-lg font-bold">Empleo 3</p>
                  <div class="grid grid-cols-2 xl:grid-cols-8 lg:grid-cols-5 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="experencialaboraltres">Empresa</label>
                      <InputText
                        id="experencialaboraltres"
                        v-model="form.empresaTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Experiencia laboral"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechaingresotres">Fecha Ingreso</label>
                      <input
                        type="month"
                        id="fechaingresotres"
                        v-model="form.fechaIngresoTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha ingreso"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechasalidatres">Fecha Salida</label>
                      <input
                        type="month"
                        id="fechasalidatres"
                        v-model="form.fechaSalidaTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha salida"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="sueldotres">Sueldo</label>
                      <input
                        type="number"
                        id="sueldotres"
                        v-model="form.sueldoTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Sueldo"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="cargodesempeniatres">Cargo Desempeña</label>
                      <InputText
                        id="cargodesempeniatres"
                        v-model="form.cargoDesempenioTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cargo desempeña"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="motivosalidatres">Motivo De Su Salida</label>
                      <Textarea
                        id="motivosalidatres"
                        v-model="form.motivoSalidaTres"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Motivo de su salida"
                        rows="1" cols="30"
                      />

                    </div>
                  </div>

                  <!--en sus empleos usted-->
                  <div class="mt-5 flex flex-col gap-2 me-4 col-span-4">
                    <p class="font-bold text-lg">En Sus Empleos, Usted:</p>
                  </div>

                  <div class="grid grid-cols-4 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-6 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-2">
                      <label for="amonestacionesEmpleosSi"
                      >Ha recibido amonestaciones escritas:
                      </label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="amonestacionesEmpleosSi"
                              type="radio"
                              :value="true"
                              name="amonestacionesEmpleos"
                              v-model="form.amonestacionesEmpleos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="amonestacionesEmpleosSi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="amonestacionesEmpleosNo"
                              type="radio"
                              :value="false"
                              name="amonestacionesEmpleos"
                              v-model="form.amonestacionesEmpleos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="amonestacionesEmpleosNo"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-2">
                      <label for="solicitudrenuncia"
                      >Le han solicitado su carta de renuncia:
                      </label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="solicitudRenunciaSi"
                              type="radio"
                              :value="true"
                              name="solicitudRenuncia"
                              v-model="form.solicitudRenuncia"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="solicitudRenunciaSi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="solicitudRenunciaNo"
                              type="radio"
                              :value="false"
                              name="solicitudRenuncia"
                              v-model="form.solicitudRenuncia"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="solicitudRenunciaNo"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-4 xl:col-span-4 lg:col-span-4 md:col-span-2"
                         v-if="true">
                      <label for="explicacion"
                      >En caso de respuesta afirmativa, explique:
                      </label>
                      <Textarea
                        id="explicacion"
                        v-model="form.explicacion"
                        rows="5"
                        cols="30"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Explique"/>

                    </div>
                  </div>

                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('4')"/>
                  <Button label="Siguiente" @click="activateCallback('6')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="6">
              <Step>INFORMACIÓN FINANCIERA</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div class="grid grid-cols-2 xl:grid-cols-5 lg:grid-cols-3 md:grid-cols-3 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="tieneprestamosi">¿Tiene Prestamos?</label>

                      <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex ">
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tieneprestamosi"
                              type="radio"
                              :value="true"
                              name="tienesPrestamos"
                              v-model="form.tienesPrestamos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tieneprestamosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tieneprestamosno"
                              type="radio"
                              :value="false"
                              name="tienesPrestamos"
                              v-model="form.tienesPrestamos"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tieneprestamosno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.tienesPrestamos">
                      <label for="motoprestamo">Precise Monto Deuda S/.</label>
                      <InputNumber
                        id="motoprestamo"
                        v-model="form.montoDeuda"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Monto de deuda"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.tienesPrestamos">
                      <label for="solicitudrenuncia">Entidad</label>

                      <Select
                        v-model="form.entidadBancaria"
                        :options="entidadesBancariasList"
                        filter
                        optionLabel="name"
                        placeholder="Seleccionar entidad"
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
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.tienesPrestamos">
                      <label for="cuotamensual">Cuota Mensual S/.</label>
                      <InputNumber
                        id="cuotamensual"
                        v-model="form.cuotaMensual"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Monto de cuota"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="otroingreso">Actualmente recibe otro ingreso: </label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="otroingresosi"
                              type="radio"
                              :value="true"
                              name="tieneOtroIngreso"
                              v-model="form.otroIngreso"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="otroingresosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="otroingresono"
                              type="radio"
                              :value="false"
                              name="tieneOtroIngreso"
                              v-model="form.otroIngreso"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="otroingresono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.otroIngreso">
                      <label for="precisemonto">Precise Monto S/.</label>
                      <InputNumber
                        id="precisemonto"
                        v-model="form.preciseMonto"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Precise el monto"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2"
                         v-if="form.otroIngreso">
                      <label for="origen">Origen</label>
                      <Textarea
                        id="origen"
                        v-model="form.origenOtroIngreso"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Origen"
                        rows="1" cols="10"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="tieneprestamosi">¿Tiene Propiedades?</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tienepropiedadessi"
                              type="radio"
                              :value="true"
                              name="tienePropiedades"
                              v-model="form.tienePropiedades"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tienepropiedadessi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tienepropiedadesno"
                              type="radio"
                              :value="false"
                              name="tienePropiedades"
                              v-model="form.tienePropiedades"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tienepropiedadesno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2"
                         v-if="form.tienePropiedades">
                      <label for="detalle">Detalle</label>
                      <Textarea
                        id="detalle"
                        v-model="form.detallePropiedades"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Detalle"
                        rows="1" cols="10"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="reportadoencentralesderiesgosi">Esta Reportado en Centrales de Riesgo</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="reportadoencentralesderiesgosi"
                              type="radio"
                              :value="true"
                              name="reportadoEnCentralesDeRiesgo"
                              v-model="form.reportadoEnCentralesDeRiesgo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="reportadoencentralesderiesgosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="reportadoencentralesderiesgono"
                              type="radio"
                              :value="false"
                              name="reportadoEnCentralesDeRiesgo"
                              v-model="form.reportadoEnCentralesDeRiesgo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="reportadoencentralesderiesgono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>

                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.reportadoEnCentralesDeRiesgo">
                      <label for="entidaddeuda">Entidad Deuda</label>

                      <Select
                        v-model="form.entidadDeuda"
                        :options="entidadesBancariasList"
                        filter
                        optionLabel="name"
                        placeholder="Seleccionar entidad deuda"
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
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.reportadoEnCentralesDeRiesgo">
                      <label for="motivocentralriesgo">Motivo</label>
                      <InputText
                        id="motivocentralriesgo"
                        v-model="form.motivoCentralDeRiesgo"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Motivo"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.reportadoEnCentralesDeRiesgo">
                      <label for="tiempomora">Tiempo de Mora</label>
                      <InputText
                        id="tiempomora"
                        v-model="form.tiempoMora"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Tiempo de mora"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"
                         v-if="form.reportadoEnCentralesDeRiesgo">
                      <label for="motodeuda">Monto de Deuda S/.</label>
                      <InputNumber
                        id="motodeuda"
                        v-model="form.montoDeuraMora"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Monto de deuda"
                      />

                    </div>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('5')"/>
                  <Button label="Siguiente" @click="activateCallback('7')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="7">
              <Step>CONSUMO DE BEBIDAS ALCOHOLICAS</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-4 gap-2">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="frecuenciaconsumosbebidas"
                      >Con Qué Frecuencia Consume Bebidas Alcohólicas:</label
                      >
                      <InputText
                        id="frecuenciaconsumosbebidas"
                        v-model="form.fecuenciaConsumoBebidasAlcoholicas"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Frecuencias de consumo"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="quebebidasconsume">Que Bebidas Consume</label>
                      <InputText
                        id="quebebidasconsume"
                        v-model="form.queBebidasConsume"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Bebidas que consume"
                      />

                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="tratamientoalcholismosi"
                      >Ha recibido tratamiento por alcoholismo</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tratamientoalcholismosi"
                              type="radio"
                              :value="true"
                              name="tratamientoAlcholismo"
                              v-model="form.tratamientoAlcholismo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tratamientoalcholismosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="tratamientoalcholismono"
                              type="radio"
                              :value="false"
                              name="tratamientoAlcholismo"
                              v-model="form.tratamientoAlcholismo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="tratamientoalcholismono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2">
                      <label for="trabajoebriosi">Ha Llegado a Trabajar Ebrio</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="trabajoebriosi"
                              type="radio"
                              :value="true"
                              name="trabajoEbrio"
                              v-model="form.trabajoEbrio"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="trabajoebriosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="trabajoebriono"
                              type="radio"
                              :value="false"
                              name="trabajoEbrio"
                              v-model="form.trabajoEbrio"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="trabajoebriono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 me-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2"
                         v-if="form.trabajoEbrio">
                      <label for="explicacionllegoebrio"
                      >En Caso De Respuesta Afirmativa, Explique</label
                      >
                      <Textarea
                        id="explicacionllegoebrio"
                        v-model="form.expliqueLlegoEbrio"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Explicación"
                        rows="1" cols="10"
                      />

                    </div>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('6')"/>
                  <Button label="Siguiente" @click="activateCallback('8')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="8">
              <Step>IMPLICACIÓN EN DROGAS ILEGALES</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <p>¿Cúales drogas ilegales ha probado o consumido alguna vez?</p>
                  <div
                    class="grid grid-cols-4 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-3 gap-2 xl:divide-x-2 xl:divide-[#B00202] lg:divide-x-2 lg:divide-[#B00202] md:divide-x-2 md:divide-[#B00202] py-4">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="marihuanasi" class="font-black">Marihuana</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="marihuanasi"
                              type="radio"
                              :value="true"
                              name="marihuana"
                              v-model="form.marihuana"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="marihuanasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="marihuanano"
                              type="radio"
                              :value="false"
                              name="marihuana"
                              v-model="form.marihuana"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="marihuanano"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="pbc" class="font-black">PBC</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="pbc"
                              type="radio"
                              :value="true"
                              name="pbc"
                              v-model="form.pbc"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="pbc"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="pbcno"
                              type="radio"
                              :value="false"
                              name="pbc"
                              v-model="form.pbc"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="pbcno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="cocainasi" class="font-black">Cocaína</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="cocainasi"
                              type="radio"
                              :value="true"
                              name="cocaina"
                              v-model="form.cocaina"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="cocainasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="cocainano"
                              type="radio"
                              :value="false"
                              name="cocaina"
                              v-model="form.cocaina"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="cocainano"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="heroinasi" class="font-black">Heroína</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="heroinasi"
                              type="radio"
                              :value="true"
                              name="heroina"
                              v-model="form.heroina"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="heroinasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="heroinano"
                              type="radio"
                              :value="false"
                              name="heroina"
                              v-model="form.heroina"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="heroinano"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="lcdsi" class="font-black">LCD</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="lcdsi"
                              type="radio"
                              :value="true"
                              name="lcd"
                              v-model="form.lcd"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="lcdsi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="lcdno"
                              type="radio"
                              :value="false"
                              name="lcd"
                              v-model="form.lcd"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="lcdno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="extasissi" class="font-black">Éxtasis</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="extasissi"
                              type="radio"
                              :value="true"
                              name="extasis"
                              v-model="form.extasis"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="extasissi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="extasisno"
                              type="radio"
                              :value="false"
                              name="extasis"
                              v-model="form.extasis"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="extasisno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-4 xl:col-span-2 lg:col-span-2 md:col-span-2 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="tiempo">¿Cuándo fue la última vez que probo o consumió alguna droga ilegal?</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="dias"
                              type="radio"
                              value="dias"
                              name="tiempoUltimaVez"
                              v-model="form.tiempoUltimaVez"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="dias"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Días</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="meses"
                              type="radio"
                              value="meses"
                              name="tiempoUltimaVez"
                              v-model="form.tiempoUltimaVez"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="meses"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Meses</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="anios"
                              type="radio"
                              value="anios"
                              name="tiempoUltimaVez"
                              v-model="form.tiempoUltimaVez"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="anios"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Años</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-4 xl:col-span-1 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="cantidad">Cantidad</label>
                      <InputNumber
                        id="cantidad"
                        v-model="form.cantidadUltimoConsumo"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Cantidad"
                      />

                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-4 xl:col-span-2 lg:col-span-2 md:col-span-2 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="familiaresendogras"
                      >¿Tiene algún familiar implicado en consumo de drogas?</label
                      >
                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaresendograssi"
                              type="radio"
                              :value="true"
                              name="familiaresEnDrogas"
                              v-model="form.familiaresEnDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaresendograssi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaresendograsno"
                              type="radio"
                              :value="false"
                              name="familiaresEnDrogas"
                              v-model="form.familiaresEnDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaresendograsno"
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
                  <Button label="Anterior" severity="secondary" @click="activateCallback('7')"/>
                  <Button label="Siguiente" @click="activateCallback('9')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="9">
              <Step>COMISIÓN DE DELITOS</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <p class="me-4">¿Ha cometido, planeado, encubierto o participado alguno de estos delitos?</p>
                  <div
                    class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-3 gap-2 xl:divide-x-2 xl:divide-[#B00202] lg:divide-x-2 lg:divide-[#B00202] md:divide-x-2 md:divide-[#B00202] py-4">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="robohurtofraude"
                      >Robo - Hurto - Fraude</label
                      >

                      <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2">
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="robohurtofraudesi"
                              type="radio"
                              :value="true"
                              name="robohurtofraude"
                              v-model="form.roboHurtoFraude"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="robohurtofraudesi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="robohurtofraudeno"
                              type="radio"
                              :value="false"
                              name="robohurtofraude"
                              v-model="form.roboHurtoFraude"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="robohurtofraudeno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="homicidioinvoluntario">Homicidio involuntario</label>

                      <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2">
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="homicidioinvoluntario"
                              type="radio"
                              :value="true"
                              name="homicidioinvoluntario"
                              v-model="form.homicidioInvoluntario"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="homicidioinvoluntario"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="homicidioinvoluntariono"
                              type="radio"
                              :value="false"
                              name="homicidioinvoluntario"
                              v-model="form.homicidioInvoluntario"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="homicidioinvoluntariono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="asaltosi">Asalto</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="asaltosi"
                              type="radio"
                              :value="true"
                              name="asalto"
                              v-model="form.asalto"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="asaltosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="asaltono"
                              type="radio"
                              :value="false"
                              name="asalto"
                              v-model="form.asalto"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="asaltono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="planesdaniofisicosi">Planes para causar daño físico a unindividuo</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planesdaniofisicosi"
                              type="radio"
                              :value="true"
                              name="planesdaniofisico"
                              v-model="form.planesDanioFisico"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planesdaniofisicosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planesdaniofisicono"
                              type="radio"
                              :value="false"
                              name="planesdaniofisico"
                              v-model="form.planesDanioFisico"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planesdaniofisicono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="secuestrosi">Secuestro</label>
                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="secuestrosi"
                              type="radio"
                              :value="true"
                              name="secuestro"
                              v-model="form.secuestro"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="secuestrosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="secuestrono"
                              type="radio"
                              :value="false"
                              name="secuestro"
                              v-model="form.secuestro"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="secuestrono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="violacionsi">Violación</label>
                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="violacionsi"
                              type="radio"
                              :value="true"
                              name="violacion"
                              v-model="form.violacion"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="violacionsi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="violacionno"
                              type="radio"
                              :value="false"
                              name="violacion"
                              v-model="form.violacion"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="violacionno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="muertelesionpersonasi">Cualquier delito que causara muerte o lesión a
                        otra persona</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="muertelesionpersonasi"
                              type="radio"
                              :value="true"
                              name="muertelesionpersona"
                              v-model="form.muerteLesionPersona"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="muertelesionpersonasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="muertelesionpersonano"
                              type="radio"
                              :value="false"
                              name="muertelesionpersona"
                              v-model="form.muerteLesionPersona"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="muertelesionpersonano"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="traficoilicitodrogassi">Tráfico ilícito de drogas</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficoilicitodrogassi"
                              type="radio"
                              :value="true"
                              name="traficoilicitodrogas"
                              v-model="form.traficoIlicitoDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficoilicitodrogassi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficoilicitodrogasno"
                              type="radio"
                              :value="false"
                              name="traficoilicitodrogas"
                              v-model="form.traficoIlicitoDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficoilicitodrogasno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="traficodearmassi">Tráfico de armas</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficodearmassi"
                              type="radio"
                              :value="true"
                              name="traficodearmas"
                              v-model="form.traficoArmas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficodearmassi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficodearmasno"
                              type="radio"
                              :value="false"
                              name="traficodearmas"
                              v-model="form.traficoArmas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficodearmasno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="puedesercastigadoporlaleysi">Cualquier acto. conspiración o solicitud en los
                        cuales usted puede ser castigado con
                        cárcel</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="puedesercastigadoporlaleysi"
                              type="radio"
                              :value="true"
                              name="puedesercastigadoporlaley"
                              v-model="form.castigadoConCarcel"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="puedesercastigadoporlaleysi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="puedesercastigadoporlaleyno"
                              type="radio"
                              :value="false"
                              name="puedesercastigadoporlaley"
                              v-model="form.castigadoConCarcel"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="puedesercastigadoporlaleyno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div v-if="form.castigadoConCarcel"
                         class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="explicacioncastigadoley">En caso de respuesta afirmativa,
                        explique:</label
                      >
                      <Textarea
                        id="explicacioncastigadoley"
                        v-model="form.explicacionCastigadoLey"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Explique"
                        rows="1" cols="10"
                      />

                    </div>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('8')"/>
                  <Button label="Siguiente" @click="activateCallback('10')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="10">
              <Step>CONOCE ALGUNA PERSONA AL MARGEN DE LA LEY</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div
                    class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-3 gap-2 xl:divide-x-2 xl:divide-[#B00202] lg:divide-x-2 lg:divide-[#B00202] md:divide-x-2 md:divide-[#B00202] py-4">
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="pandilleros">Pandilleros</label>

                      <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2">
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="pandillerossi"
                              type="radio"
                              :value="true"
                              name="pandilleros"
                              v-model="form.pandilleros"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="pandillerossi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="pandillerosno"
                              type="radio"
                              :value="false"
                              name="pandilleros"
                              v-model="form.pandilleros"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="pandillerosno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="sicarios">Sicarios</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="sicarios"
                              type="radio"
                              :value="true"
                              name="sicarios"
                              v-model="form.sicarios"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="sicarios"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="sicariosno"
                              type="radio"
                              :value="false"
                              name="sicarios"
                              v-model="form.sicarios"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="sicariosno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="asaltantessi">Asaltantes</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="asaltantessi"
                              type="radio"
                              :value="true"
                              name="asaltantes"
                              v-model="form.asaltantes"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="asaltantessi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="asaltantesno"
                              type="radio"
                              :value="false"
                              name="asaltantes"
                              v-model="form.asaltantes"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="asaltantesno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="traficantesdrogassi">Traficante de drogas</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficantesdrogassi"
                              type="radio"
                              :value="true"
                              name="traficantesdrogas"
                              v-model="form.traficantesDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficantesdrogassi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="traficantesdrogasno"
                              type="radio"
                              :value="false"
                              name="traficantesdrogas"
                              v-model="form.traficantesDrogas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="traficantesdrogasno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="estafadoressi">Estafadores</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="estafadoressi"
                              type="radio"
                              :value="true"
                              name="estafadores"
                              v-model="form.estafadores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="estafadoressi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="estafadoresno"
                              type="radio"
                              :value="false"
                              name="estafadores"
                              v-model="form.estafadores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="estafadoresno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="terroristassi">Terroristas</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="terroristassi"
                              type="radio"
                              :value="true"
                              name="terroristas"
                              v-model="form.terroristas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="terroristassi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="terroristasno"
                              type="radio"
                              :value="false"
                              name="terroristas"
                              v-model="form.terroristas"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="terroristasno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="secuestradoressi">Secuestradores</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="secuestradoressi"
                              type="radio"
                              :value="true"
                              name="secuestradores"
                              v-model="form.secuestradores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="secuestradoressi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="secuestradoresno"
                              type="radio"
                              :value="false"
                              name="secuestradores"
                              v-model="form.secuestradores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="secuestradoresno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="extorsionadoressi">Extorsionadores</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="extorsionadoressi"
                              type="radio"
                              :value="true"
                              name="extorsionadores"
                              v-model="form.extorsionadores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="extorsionadoressi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="extorsionadoresno"
                              type="radio"
                              :value="false"
                              name="extorsionadores"
                              v-model="form.extorsionadores"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="extorsionadoresno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="otraspersonamargenleysi">Otros</label>

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="otraspersonamargenleysi"
                              type="radio"
                              :value="true"
                              name="otraspersonamargenley"
                              v-model="form.otrasPersonasMargenLey"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="otraspersonamargenleysi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="otraspersonamargenleyno"
                              type="radio"
                              :value="false"
                              name="otraspersonamargenley"
                              v-model="form.otrasPersonasMargenLey"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="otraspersonamargenleyno"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-2 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="familiaressentenciadosopenalessi"
                      >Tiene familiares sentenciados en penales:</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaressentenciadosopenalessi"
                              type="radio"
                              :value="true"
                              name="familiaressentenciadosopenales"
                              v-model="form.familiaresSentenciadosPenales"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaressentenciadosopenalessi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaressentenciadosopenalesno"
                              type="radio"
                              :value="false"
                              name="familiaressentenciadosopenales"
                              v-model="form.familiaresSentenciadosPenales"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaressentenciadosopenalesno"
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
                  <Button label="Anterior" severity="secondary" @click="activateCallback('9')"/>
                  <Button label="Siguiente" @click="activateCallback('11')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="11">
              <Step>MOTIVACIONES POR QUE POSTULA A LA EMPRESA</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div
                    class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-3 gap-2 xl:divide-x-2 xl:divide-[#B00202] lg:divide-x-2 lg:divide-[#B00202] md:divide-x-2 md:divide-[#B00202] py-4">
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="planeacausardaniopersonaempresa"
                      >Tiene planeado ingresar a la empresa que postula, para causar
                        algún daño a personas o la empresa</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planeacausardaniopersonaempresasi"
                              type="radio"
                              :value="true"
                              name="planeacausardaniopersonaempresa"
                              v-model="form.planeaCausarDanioPersonaEmpresa"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planeacausardaniopersonaempresasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planeacausardaniopersonaempresano"
                              type="radio"
                              :value="false"
                              name="planeacausardaniopersonaempresa"
                              v-model="form.planeaCausarDanioPersonaEmpresa"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planeacausardaniopersonaempresano"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="planeaobtenesbenefioilegal"
                      >Tiene planeado ingresar a la empresa que postula, para obtener
                        algún beneficio ilegal:</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planeaobtenesbenefioilegal"
                              type="radio"
                              :value="true"
                              name="planeaobtenesbenefioilegal"
                              v-model="form.planeaObtenerBeneficioIlegal"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planeaobtenesbenefioilegal"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="planeaobtenesnobenefioilegal"
                              type="radio"
                              :value="false"
                              name="planeaobtenesbenefioilegal"
                              v-model="form.planeaObtenerBeneficioIlegal"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="planeaobtenesnobenefioilegal"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div
                      class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1 ps-0 xl:ps-3 lg:ps-3 md:ps-3">
                      <label for="familiaresenlamismaempresasi"
                      >Tiene familiares o amigos que trabajan o trabajaron en la
                        empresa que postula:</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaresenlamismaempresasi"
                              type="radio"
                              :value="true"
                              name="familiaresenlamismaempresa"
                              v-model="form.familiaresMismaEmpresa"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaresenlamismaempresasi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="familiaresenlamismaempresano"
                              type="radio"
                              :value="false"
                              name="familiaresenlamismaempresa"
                              v-model="form.familiaresMismaEmpresa"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="familiaresenlamismaempresano"
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
                  <Button label="Anterior" severity="secondary" @click="activateCallback('10')"/>
                  <Button label="Siguiente" @click="activateCallback('12')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="12">
              <Step>ACERCA DEL POLIGRAFO</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div class="grid grid-cols-2 xl:grid-cols-6 lg:grid-cols-6 md:grid-cols-3 gap-2 py-4">
                    <div class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="hapasadoexamendepoligrafo"
                      >Alguna vez ha pasado exámen de polígrafo</label
                      >

                      <ul
                        class="items-center w-full text-sm font-medium text-gray-900 bg-white sm:flex mt-2"
                      >
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="hapasadoexamendepoligrafosi"
                              type="radio"
                              :value="true"
                              name="hapasadoexamendepoligrafo"
                              v-model="form.pasoAntesExamenPoligrafo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="hapasadoexamendepoligrafosi"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >Si</label
                            >
                          </div>
                        </li>
                        <li class="w-full">
                          <div class="flex items-center">
                            <input
                              id="hapasadoexamendepoligrafono"
                              type="radio"
                              :value="false"
                              name="hapasadoexamendepoligrafo"
                              v-model="form.pasoAntesExamenPoligrafo"
                              class="w-4 h-4 text-[#B00202] bg-gray-100 border-gray-300 focus:ring-[#B00202] focus:ring-2"
                            />
                            <label
                              for="hapasadoexamendepoligrafono"
                              class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                            >No</label
                            >
                          </div>
                        </li>
                      </ul>
                    </div>
                    <div v-if="form.pasoAntesExamenPoligrafo"
                         class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="explicacionpasoexamenantes"
                      >En caso de respuesta afirmativa, explique:
                      </label>
                      <Textarea
                        id="explicacionpasoexamenantes"
                        v-model="form.explicacionExamenAnterior"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Explique"
                        rows="1" cols="10"
                      />

                    </div>
                    <div v-if="form.pasoAntesExamenPoligrafo"
                         class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="empresparalaquepostulo">Empresa </label>
                      <InputText
                        id="empresparalaquepostulo"
                        v-model="form.empresaPostuloAntes"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Empres"
                      />

                    </div>
                    <div v-if="form.pasoAntesExamenPoligrafo"
                         class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <label for="fechapasoexamenanterior">Fecha</label>
                      <input
                        type="date"
                        id="fechapasoexamenanterior"
                        v-model="form.fechaExamenAnterior"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Fecha"
                      />

                    </div>
                    <div v-if="form.pasoAntesExamenPoligrafo"
                         class="mt-2 flex flex-col gap-2 me-4 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1">
                      <label for="motivopasoantesexamen">Motivo </label>
                      <Textarea
                        id="motivopasoantesexamen"
                        v-model="form.motivoPasoAntesExamen"
                        class="flex-auto"
                        autocomplete="off"
                        placeholder="Motivo"
                        rows="1" cols="10"
                      />

                    </div>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('11')"/>
                  <Button label="Siguiente" @click="activateCallback('13')"/>
                </div>
              </StepPanel>
            </StepItem>
            <StepItem value="13">
              <Step>FINAL DEL FORMATO</Step>
              <StepPanel v-slot="{ activateCallback }">
                <div class="flex flex-col mt-4">
                  <div class="grid grid-cols-1">
                    <div class="my-4 text-justify col-span-1">
                      <div class="me-4 p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <span class="font-medium text-lg">
                      El presente formulario es de uso exclusivo para la realización del
                      examen de polígrafo, cualquier información adicional a sus
                      respuestas; por favor, realizarla al poligrafista durante la
                      entrevista en el examen. Gracias.
                    </span>
                      </div>
                    </div>
                  </div>

                  <div class="grid grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-1 gap-2 py-4">
                    <div class="mt-2 flex flex-col gap-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1"></div>
                    <div class="mt-2 flex flex-col gap-2 col-span-2 xl:col-span-1 lg:col-span-1 md:col-span-1">
                      <p>
                        Ciudad de,
                        <input
                          id="ciudadExamen"
                          v-model="form.ciudadExamen"
                          class="flex-auto w-1/4"
                          autocomplete="off"
                          placeholder="Ciudad"
                        />
                        ,
                        <input type="text"
                               id="diaActualExamen"
                               v-model="diaMesExamen"
                               class="flex-auto w-14"
                               autocomplete="off"
                               placeholder="Día"
                               maxlength="2"
                               readonly
                        />
                        de
                        <input type="text"
                               id="mesActualExamen"
                               v-model="nombreMesExamen"
                               class="flex-auto w-1/6"
                               autocomplete="off"
                               placeholder=" Mes"
                               maxlength="9"
                               readonly
                        />
                        <input type="text"
                               id="anioActualExamen"
                               v-model="anioExamen"
                               class="flex-auto w-20 ms-2"
                               autocomplete="off"
                               placeholder="Año"
                               maxlength="4"
                               readonly
                        />
                      </p>
                    </div>
                  </div>

                  <div class="grid grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-1 gap-2 py-4">
                    <div class="mt-2 flex flex-col gap-2 col-span-2 xl:col-span-1 lg:col-span-2 md:col-span-1">
                      <p>
                        Nombres y Apellidos
                        <InputText
                          class="flex-auto w-1/2 ms-4"
                          autocomplete="off"
                          placeholder="Nombres y apellidos"
                          :value="nombresCompletosPiePagina"
                          :disabled="true"
                          id="nombresApellidosPie"
                        />
                      </p>
                      <p>
                        DNI
                        <InputText
                          class="flex-auto w-1/2 ms-4"
                          autocomplete="off"
                          placeholder="Número de documento"
                          :value="form.numeroDocumento"
                          :disabled="true"
                          id="numeroDocumentoPie"
                        />
                      </p>
                    </div>
                    <div class="mt-2 flex flex-col gap-2 col-span-2 xl:col-span-2 lg:col-span-2 md:col-span-1"></div>
                  </div>

                  <div class="mt-2 flex flex-col gap-2">
                    <p class="text-center mt-16 text-lg">Firma</p>
                    <div class="flex justify-center mt-4">
                      <vue-drawing-canvas
                        ref="VueCanvasDrawing"
                        @mousemove="getCoordinate($event)"
                        :lock="false"
                        :lineWidth="1"
                        saveAs="png"
                        v-model:image="form.imagenFirma"
                        style="border: 1px solid gray"
                        :width="280"
                        :height="180"
                      />
                      <div class="hidden">
                        <img :src="form.imagenFirma" alt="imagen de la firma">
                      </div>
                    </div>
                    <p class="text-center mt-4">
                      <button type="button" @click.prevent="$refs.VueCanvasDrawing.reset()"
                              class="bg-red-500 p-2 rounded-lg text-white">Corregir Firma
                      </button>
                    </p>
                  </div>
                </div>
                <div class="flex pt-4 mb-6 gap-2">
                  <Button label="Anterior" severity="secondary" @click="activateCallback('12')"/>
                  <Button type="submit" label="Terminar" @click="datosPiePagina"/>
                </div>
              </StepPanel>
            </StepItem>
          </Stepper>
        </template>
      </Card>
    </form>
  </div>

  <Dialog v-model:visible="errors" :style="{ width: '650px' }" :modal="true">
    <template #header>
      <h3 class="text-xl font-black text-red-500">Ooops! verifica los siguientes campos</h3>
    </template>
    <div class="flex items-center">
      <ul>
        <li v-for="(error, index) in errorsList" :key="index" class="my-3">
          <Message severity="error">{{ error }}</Message>
        </li>
      </ul>
    </div>
    <template #footer>
      <Button label="Entendido" icon="pi pi-check" text @click="errors = false; errorsList = []"/>
    </template>
  </Dialog>
</template>

<style scoped lang="scss">
#ciudadExamen,
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
}

input[type="month"], input[type="date"], input[type="number"] {
  border: 1px solid #CBD5E1;
  border-radius: 5px;
  outline-offset: 0;
  outline: 0;
  --tw-ring-color: transparent;

  &:focus {
    outline: none;
    border: 0;
    --tw-ring-color: #10B981;
  }

  &:active {
    outline: none;
    border: 0;
    --tw-ring-color: #10B981;
  }
}
</style>







