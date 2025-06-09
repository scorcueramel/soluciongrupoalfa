<template>
  <div class="relative">
    <!-- Loader -->
    <div v-if="cargando" class="absolute inset-0 flex items-center justify-center bg-white/80 z-10">
      <ProgressSpinner style="width: 50px; height: 50px;" />
    </div>

    <!-- Error -->
    <div v-if="error" class="p-4 text-red-700 bg-red-100 border border-red-300 rounded mb-3">
      Error al cargar el PDF. Por favor, inténtalo nuevamente.
    </div>

    <!-- Visor PDF -->
    <iframe
      v-show="!error"
      ref="pdfIframe"
      :src="pdfUrl"
      @load="handleLoad"
      @error="handleError"
      style="width: 100%; height: 600px; border: none;"
    ></iframe>

    <!-- Botones -->
    <div class="flex justify-end gap-2 mt-3">
      <Button icon="pi pi-download" label="Descargar" @click="descargarPdf" :disabled="!!error" />
      <Button icon="pi pi-print" label="Imprimir" severity="info" @click="imprimirPdf" :disabled="!!error" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineEmits, defineProps } from 'vue'

const props = defineProps({
  personaId: {
    type: [String, Number],
    required: true
  }
})

const cargando = ref(true)
const error = ref(false)
const pdfIframe = ref(null)

const pdfUrl = computed(() => `/pdf/${props.personaId}`)

function handleLoad() {
  cargando.value = false
}

function handleError() {
  cargando.value = false
  error.value = true
}

function descargarPdf() {
  window.open(`/pdf/${props.personaId}?descargar=1`, '_blank')
}

function imprimirPdf() {
  // Abrimos el PDF en nueva ventana y esperamos que cargue para imprimir
  const printWindow = window.open(pdfUrl.value, '_blank')
  if (printWindow) {
    // Esperamos a que cargue el contenido antes de llamar a print()
    printWindow.onload = () => {
      printWindow.print()
    }
  }
}
</script>
