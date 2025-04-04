<script setup>
import { useForm, Head } from "@inertiajs/vue3";
import { ref } from "vue";
import Alert from "@/Components/Alert.vue";

let documentoVacio = ref("");

const props = defineProps({
    openError: Boolean,
    errorMessage: String,
});

const form = useForm({
    documento: "",
});

const submit = () => {
    if (form.documento === "") {
        documentoVacio.value = "Debes ingresar tu número de documento";
    } else {
        documentoVacio.value = "";

        form.post(route("formato.validar.acceso"), {
            onSuccess: () => form.reset(),
            onFinish: () => form.reset(),
        });
    }
};
</script>

<template>
    <Head title="Validación de acceso" />
    <div
        class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden"
    >
        <div class="flex flex-col items-center justify-center">
            <div
                style="
                    border-radius: 56px;
                    padding: 0.3rem;
                    background: linear-gradient(
                        180deg,
                        #ff0000 10%,
                        rgba(33, 150, 243, 0) 30%
                    );
                "
            >
                <div
                    class="w-full bg-surface-0 dark:bg-surface-900 py-20 px-8 sm:px-20"
                    style="border-radius: 53px"
                >
                    <div class="flex justify-center mb-4">
                        <img
                            src="/demo/images/logo.png"
                            alt="logo-grupo-alfa"
                        />
                    </div>
                    <div class="flex justify-center mb-5">
                        <span
                            class="text-muted-color font-medium text-xl text-center"
                            >Ingresa tu número de documento para continuar</span
                        >
                    </div>

                    <div
                        class="bg-red-50 border-s-4 border-red-500 p-4 dark:bg-red-800/30"
                        role="alert"
                        tabindex="-1"
                        aria-labelledby="hs-bordered-red-style-label"
                        v-if="props.openError"
                    >
                        <alert :show="props.openError">
                            <template #titleAlert>
                                Ooops!!
                            </template>
                            <template #messageAlert>
                                {{ props.errorMessage}}
                            </template>
                        </alert>

                    </div>

                    <div class="mt-5">
                        <form @submit.prevent="submit">
                            <label
                                for="documento"
                                class="block text-surface-900 dark:text-surface-0 text-xl font-medium mb-2"
                                >Número de documento</label
                            >
                            <InputText
                                id="documento"
                                type="text"
                                placeholder="Documento"
                                class="w-full md:w-[30rem] mb-4"
                                v-model="form.documento"
                            />
                            <Message
                                v-if="form.errors.documento"
                                class="mb-4"
                                severity="error"
                                >{{ form.errors.documento }}
                            </Message>
                            <Message
                                v-if="documentoVacio"
                                class="mb-4"
                                severity="error"
                                >{{ documentoVacio }}
                            </Message>

                            <div class="flex items-center justify-between gap-8">
                            </div>
                            <button type="submit" class="w-full bg-[#B00202] text-white p-2 rounded-md hover:bg-[#BC2727]">Validar Documento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:focus {
    border: 1px solid #b00202 !important;
}
</style>
