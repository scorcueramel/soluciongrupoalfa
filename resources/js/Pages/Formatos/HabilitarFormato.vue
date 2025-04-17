<script setup>
import { useForm } from "@inertiajs/vue3";
import {ref, watchEffect} from "vue";
import RadioButton from 'primevue/radiobutton';

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(["close"]);

const form = useForm({
  numero_documento:"",
  acceder_formato: true
});

const create = () => {
  form.post(route("formatos.crear.acceso"), {
    preserveScroll: true,
    onSuccess: () => {
      emit("close");
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

watchEffect(() => {
  if (props.show) {
    form.errors = {};
  }
});

</script>

<template>
    <Dialog
      v-model:visible="props.show"
      header="Habilitar Formulario"
      position="top"
      modal
      :style="{ width: '30rem' }"
      :closable="false"
    >
    <form @submit.prevent="create">
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          <label for="numero_documento">Número de Documento</label>
          <InputText
            id="numero_documento"
            v-model="form.numero_documento"
            class="flex-auto"
            autocomplete="off"
            placeholder="Número de Documento"
          />
          <small v-if="form.errors.numero_documento" class="text-red-500">{{
              form.errors.numero_documento
            }}</small>
        </div>
        <div class="flex flex-wrap gap-2">
          <label class="w-full">Acceder a Formato</label>
          <div class="flex items-center gap-2">
            <RadioButton v-model="form.acceder_formato" inputId="acceder_formatoSi" name="acceder_formato" :value="false"  />
            <label for="acceder_formatoSi" class="text-sm">Si</label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton v-model="form.acceder_formato" inputId="acceder_formatoNo" name="acceder_formato" :value="true" />
            <label for="acceder_formatoNo">No</label>
          </div>
        </div>
        <div class="flex justify-end gap-2">
          <Button
            type="button"
            label="Cancelar"
            severity="secondary"
            @click="emit('close')"
          ></Button>
          <Button type="submit" label="Guardar"></Button>
        </div>
      </div>
    </form>
  </Dialog>
</template>
