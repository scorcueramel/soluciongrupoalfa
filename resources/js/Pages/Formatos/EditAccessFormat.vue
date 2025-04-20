<script setup>
import { useForm } from "@inertiajs/vue3";
import {ref, watchEffect} from "vue";
import RadioButton from 'primevue/radiobutton';
import Message from 'primevue/message';

const props = defineProps({
  show: Boolean,
  evaluado: Object
});

const emit = defineEmits(["close"]);

const form = useForm({
  id:0,
  numero_documento:"",
  acceder_formato: false,
});

const update = () => {
  form.put(route("formatos.actualizar.acceso"), {
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

  if (props.show) {
    form.errors = {};
    form.id = props.evaluado?.id;
    form.numero_documento = props.evaluado?.documento_persona;
    form.acceder_formato = props.evaluado?.acceso_formato;
  }
});

</script>

<template>
  <Dialog
    v-model:visible="props.show"
    header="Editar Acceso a Formato"
    position="top"
    modal
    :style="{ width: '30rem' }"
    :closable="false"
  >
    <Message class="mt-1 mb-5"><b>Recuerda,</b> cuando habilitas el acceso al formato, tu conteo de evaluaciones incrementa en 1 a partir de la última evalución realizda</Message>
    <form @submit.prevent="update">
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
            <RadioButton v-model="form.acceder_formato" inputId="acceder_formatoSi" name="acceder_formato" :value="true"  />
            <label for="acceder_formatoSi" class="text-sm">Si</label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton v-model="form.acceder_formato" inputId="acceder_formatoNo" name="acceder_formato" :value="false" />
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
