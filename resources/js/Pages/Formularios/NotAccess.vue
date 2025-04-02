<script setup>
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    message: String,
});
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
                    <div class="grid grid-cols-1">
                        <div class="mb-8">
                            <p
                                class="text-muted-color font-medium text-xl text-center"
                            >
                                Usted no puede acceder al formato, comuniquese con un encargado.
                            </p>
                        </div>

                        <div class="w-full flex justify-center">
                            <Link
                                href="/formato"
                                class="bg-[#B00202] text-white p-2 rounded-md hover:bg-[#BC2727]"
                            >
                                Volver atrás
                            </Link>
                        </div>
                    </div>
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
