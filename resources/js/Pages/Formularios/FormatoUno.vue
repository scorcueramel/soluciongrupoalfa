<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const isEnding = ref(false);

// onbeforeunload = (event) => {
//     event.preventDefault();
// };

</script>

<template>
    <Head title="FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO" />
    <div class="card flex justify-center">
        <div class="border border-gray-300 rounded-lg p-4 w-full md:w-9/12">
            <div class="grid grid-cols-4">
                <div class="border border-gray-500 flex justify-center items-center">
                    <img src="/demo/images/logo.png" width="166" alt="logo" class="p-8">
                </div>
                <div class="col-span-2 border border-gray-500 text-center flex justify-center items-center w-full px-10">
                    <h4>FORMATO DE SOLICITUD DE DATOS PERSONALES PARA PRUEBAS DE PRE - EMPLEO</h4>
                </div>
                <div class="border border-gray-500 text-end flex justify-center items-center w-full">
                    <div class="grid grid-cols-2">
                        <div>1</div>
                        <div>2</div>
                        <div>1</div>
                        <div>2</div>
                        <div>1</div>
                        <div>2</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
