<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    empresas: Array,
    cargos: Array
});

const form = useForm({
    empresa: "",
    cargo:""
});

const isEnding = ref(false);

// onbeforeunload = (event) => {
//     event.preventDefault();
// };
</script>

<template>
    <Head
        title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO"
    />
    <div class="card flex justify-center">
        <div class="border border-gray-300 rounded-lg p-4 w-full md:w-9/12">
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
                    class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400"
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

            <div>
                <div class="grid grid-cols-2">
                    <div class="col-span-2 bg-gray-300 font-black p-2">
                        EMPRESA A LA QUE POSTULA
                    </div>
                    <div class="p-2 flex flex-col gap-2">
                        <label for="role">Razón Social</label>
                        <Select
                            v-model="form.empresa"
                            :options="props.empresas"
                            optionValue="code"
                            optionLabel="name"
                            placeholder="Seleccionar empresa"
                        />
                        <small class="text-red-500">errores</small>
                    </div>
                    <div class="p-2 flex flex-col gap-2">
                        <label for="role">Cargo</label>
                        <Select
                            v-model="form.cargo"
                            :options="props.cargos"
                            optionValue="code"
                            optionLabel="name"
                            placeholder="Seleccionar cargo"
                        />
                        <small class="text-red-500">errores</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
