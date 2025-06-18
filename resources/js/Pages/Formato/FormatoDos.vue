<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import VueDrawingCanvas from "vue-drawing-canvas";
import {onMounted, ref} from "vue";

const props = defineProps({
  datosEvaluados: Object,
});

const mesesListCalendatio = ref(["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",]);
const numeroMesExamen = ref(0);
const nombreMesExamen = ref("");
const diaMesExamen = ref(0);
const anioExamen = ref(0);
const anioNacimiento = ref(0);
const edadEvaluado = ref(0);

const form = useForm({
  imagenFirma: "",
})

onMounted(() => {

  numeroMesExamen.value = getDate.getMonth() + 1;
  nombreMesExamen.value = mesesListCalendatio.value[numeroMesExamen.value];
  diaMesExamen.value = (getDate.getMonth() + 1).toString().padStart(2, "0");
  anioExamen.value = getDate.getFullYear();

  anioNacimiento.value = parseInt(props.datosEvaluados.edad.slice(0, 4));

  edadEvaluado.value = parseInt(anioExamen.value) - anioNacimiento.value;
});

onbeforeunload = (event) => {
  event.preventDefault();
};

const getDate = new Date();
const getCoordinate = (event) => {
  let coordinates = this.$refs.VueDrawingCanvas.getCoordinates(event);
  this.x = coordinates.x;
  this.y = coordinates.y;
}

const registrarFormato = () => {
  Swal.fire({
    title: "¿Terminaste?",
    html: "Al presionar el botón <b>Continuar</b> se registrará tu consentimiento",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#10B981",
    cancelButtonColor: "#d33",
    confirmButtonText: "Continuar",
    cancelButtonText: "Revisar",
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: 'info',
        html: "Espere un momento porfavor ...",
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      form.post(route('formato.dos.store'), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            title: "Formato Registrado!",
            text: "Tu formato 2 fue registrado correctamente!",
            icon: "success",
            allowOutsideClick: false,
            confirmButtonColor: "#10B981",
            confirmButtonText: "Terminar",
          }).then((result) => {
            if (result.isConfirmed) {
              form.get(route('formato.index'), {
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
      });
    }
  });
}

</script>
<template>
  <Head title="CONSENTIMIENTO INFORMADO DE EXAMEN DE POLÍGRAFO"/>
  <div class="flex justify-center">
    <div class="w-full xl:w-2/4 lg:w-3/4 md:lg:w-3/4">
      <Card class="my-6">
        <template #content>
          <div class="flex justify-center items-center">
            <div class="grid grid-cols-1 xl:grid-col-4 lg:grid-col-4 md:grid-cols-4 w-full">
              <div class="border-none flex justify-center items-center">
                <img
                  src="/demo/images/logo.png"
                  width="200"
                  alt="logo"
                  class="p-8"
                />
              </div>
              <div
                class="col-span-2 border-none text-center flex justify-center items-center w-full px-10 py-4 xl:py-0 lg:py-0"
              >
                <h4 class="text-lg font-bold">
                  CONSENTIMIENTO INFORMADO DE EXAMEN DE POLÍGRAFO
                </h4>
              </div>
              <div class="border-none text-end flex justify-center items-center w-full py-4 xl:py-0 lg:py-0 md:px-2">
                <div class="grid grid-cols-2 py-4">
                  <div class="text-start">
                    <p class="font-bold">Código:</p>
                  </div>
                  <div class="text-end">
                    <p>{{ props.datosEvaluados.codigo_poligrafista }}{{ props.datosEvaluados.numero_evaluaciones }}</p>
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
          </div>
        </template>
      </Card>
      <Card class="my-6">
        <template #content>
          <form @submit.prevent="registrarFormato">
            <div class="flex justify-center items-center px-10 py-4 xl:py-10 lg:py-10">
              <div class="container grid grid-cols-1 w-full ">
                <div class="px-0 xl:px-10 lg:px-10 md:px-0 mb-6">
                  <p class="text-justify text-lg">
                    Yo, <span class="font-black">{{ props.datosEvaluados.nombres.toUpperCase() }}</span> identificado
                    con DNI N° <span class="font-black">{{ props.datosEvaluados.documento }}</span> de <span
                    class="font-black">{{ edadEvaluado }}</span> años de edad, autorizo voluntariamente para que se me
                    practique un examen de polígrafo.
                  </p>
                </div>
                <div class="px-0 xl:px-10 lg:px-10 md:px-0 mb-6">
                  <p class="text-justify text-lg">
                    He sido informado detalladamente sobre el procedimiento de evaluación en el sistema de
                    polígrafo, funcionamiento, así como los componentes que se utilizan: manga cardiovascular, bandas
                    neumográficas, cable EDA (actividad electrodérmica) y el sensor de movimiento, el procedimiento de
                    evaluación psicofísica y el repaso previo de las preguntas que se me van a realizar durante la
                    evaluación.
                  </p>
                </div>
                <div class="px-0 xl:px-10 lg:px-10 md:px-0 mb-6">
                  <p class="text-justify text-lg">
                    Asimismo, tomo conocimiento que los resultados de la prueba son absolutamente confidenciales y sólo
                    serán entregados por Grupo Alfa Consultores, a la empresa o persona que ha contratado los servicios.
                  </p>
                </div>
                <div class="px-0 xl:px-10 lg:px-10 md:px-0 mb-6">
                  <p class="text-justify text-lg">
                    De igual forma quedo enterado a través del Poligrafista que el examen será grabado o monitoreado en
                    audio y video
                  </p>
                </div>
                <div class="px-0 xl:px-10 lg:px-10 md:px-0 mb-6 grid grid-cols-1">
                  <div
                    class="mt-2 flex flex-col gap-2 col-span-1 xl:col-span-1 lg:col-span-1 md:col-span-1 text-center">
                    <p class="text-center mt-16 text-lg">Firma</p>
                    <div class="flex justify-center mt-4">
                      <vue-drawing-canvas
                        ref="VueCanvasDrawing"
                        @mousemove="getCoordinate($event)"
                        :lock="false"
                        :lineWidth="1"
                        saveAs="jpeg"
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
                  <div
                    class="my-10 flex flex-col gap-2 col-span-1 xl:col-span-1 lg:col-span-1 md:col-span-1 text-center">
                    <p>
                      DNI N°
                      <InputText
                        class="flex-auto w-auto ms-4"
                        autocomplete="off"
                        placeholder="Número de documento"
                        v-model="props.datosEvaluados.documento"
                        :disabled="true"
                        id="numeroDocumentoPie"
                      />
                    </p>
                  </div>
                  <div
                    class="my-6 flex flex-col gap-2 col-span-1 xl:col-span-1 lg:col-span-1 md:col-span-1 text-center">
                    <p>
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
                <div class="my-10 px-0 xl:px-10 lg:px-10 md:px-0 mb-6">
                  <p class="text-justify text-md">
                    De libre voluntad declaro, que he leído y comprendido el contenido de este documento y sin amenaza,
                    coacción, fuerza o recompensa, por lo que manifiesto expresamente que estoy de acuerdo a ser
                    entrevistado, y someterme a la Evaluación Psicofisiológica (Prueba Poligráfica) y como constancia
                    firmo.
                  </p>
                </div>
              </div>
            </div>
            <div class="grid grid-cols-1 mb-4">
              <div class="flex justify-center">
                <Button type="submit" label="Terminar" severity="success"/>
              </div>
            </div>
          </form>
        </template>
      </Card>
    </div>
  </div>
</template>
<style scoped lang="scss">
#diaActualExamen,
#mesActualExamen,
#anioActualExamen,
#numeroDocumentoPie {
  border: none;
  box-shadow: none;
  border-bottom: 1px solid gray;
  border-radius: 0;
  background: transparent;
}
</style>
