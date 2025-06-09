<template>
  <div>
<!--    <Button label="Ver PDF" icon="pi pi-file-pdf" @click="visible = true" />-->

    <Dialog v-model:visible="props.visible" modal header="Previsualización del Informe" :style="{ width: '80vw' }">
      <PdfViewer :personaId="personaId" @cerrar="visible = false" />
    </Dialog>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import PdfViewer from '@/Pages/Informe/PdfViewer.vue'

const visible = ref(false)


const props = defineProps({
  personaId: 0,
  visible: false
})
</script>
