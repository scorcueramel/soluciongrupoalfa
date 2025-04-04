<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { usePrimeVue } from "primevue/config";

const props = defineProps({
    empresas: Array,
    cargos: Array,
    distritos: Object,
    tiposviviendas: Object,
});

const distritosList = ref([]);
const tiposViviendasList = ref([]);
const otrotipovivienda = ref(false);
const tipoParentescoList = ref([
    { name: "Padre", code: 1 },
    { name: "Madre", code: 2 },
    { name: "Conyuge", code: 3 },
    { name: "Hijos", code: 4 },
    { name: "Hermanos", code: 5 },
]);
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
    tipoParentesco: "",
});

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
});

const otroTipoVivienda = (tipovivienda) => {
    if (parseInt(form.tipoVivienda) === 5) {
        otrotipovivienda.value = true;
    } else {
        otrotipovivienda.value = false;
        form.otroTipoVivienda = "";
    }
};

const activarBotonAgregar = () =>{
    agregarParentescoActive.value = false;
}

const agregarParentesco = (parentesco) => {
    const test =`
        <div id="inputFormRow">
            <div class="input-group mb-3">
                <input type="text" value="${form.tipoParentesco}" disabled>
                <input type="text" name="title[]" class="form-control m-input" placeholder="Ingrese titulo" autocomplete="off">
            <div class="input-group-append">
                <button id="removeRow" type="button" class="btn btn-danger">Borrar</button>
            </div>
        </div>
    `
    document.getElementById("datosFamiliares").insertAdjacentHTML("afterend",test);
};
</script>

<template>
    <Head
        title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO"
    />
    <div class="card flex justify-center">
        <div class="border border-gray-300 rounded-lg p-4 w-full md:w-9/12">
            <!-- Recordar que el codigo debe ser del poligrafista que esta activando el formato verificar que al momento de activar la opcion para que cliente pueda rellenar el formato se obtenga el codigo del poligrafista para su posterior envio (Revisarlo en el modelo de datos) -->
            <div
                class="grid grid-cols-1 xl:grid-col-4 lg:grid-col-4 md:grid-cols-4"
            >
                <div
                    class="border border-gray-500 flex justify-center items-center"
                >
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
                        FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE
                        PRE - EMPLEO
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
                        agradeceremos responder en forma precisa y con
                        sinceridad en todo el formulario sin excepción,
                        cualquier pregunta realizarla al personal de atención al
                        cliente.
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
                <div class="mt-2 flex flex-col gap-2 ms-2">
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
                    <label for="tipoDocumento"
                        >Tipo de Documento de Identidad</label
                    >
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
                            <div
                                v-if="slotProps.value"
                                class="flex items-center"
                            >
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
                        @change="otroTipoVivienda(form.tipoVivienda)"
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

            <div class="grid grid-cols-2 mb-4">
                <div
                    class="col-span-2 bg-[#B00202] font-black p-2 text-white rounded-md"
                >
                    DATOS FAMILIARES
                </div>
                <div class="mt-2 flex flex-col gap-2 me-2 col-span-1">
                    <label for="parentesco">Parentesco</label>
                    <Select
                        v-model="form.tipoParentesco"
                        :options="tipoParentescoList"
                        optionValue="code"
                        optionLabel="name"
                        placeholder="Seleccionar parentescos"
                        emptyMessage="Opciones no disponibles"
                        @change="activarBotonAgregar"
                    />
                    <small class="text-red-500">errores</small>
                </div>
                <div class="flex flex-col mt-10 w-4/12">
                    <Button
                        label="Agreagar parentesco"
                        icon="pi pi-plus"
                        @click="agregarParentesco(form.tipoParentesco)"
                        :rounded="true"
                        :disabled="agregarParentescoActive"
                    />
                </div>
            </div>
            <div id="datosFamiliares"></div>
        </div>
    </div>
</template>
