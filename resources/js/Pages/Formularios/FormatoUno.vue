<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const isEnding = ref(false);

onbeforeunload = (event) => {
    event.preventDefault();
};

</script>

<template>
    <Head title="Formato 1" />
    <div class="card">
        <h1>Formato 1</h1>
    </div>
</template>
