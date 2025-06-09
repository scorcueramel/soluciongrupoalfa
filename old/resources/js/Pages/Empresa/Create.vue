<script setup>
import { useForm } from "@inertiajs/vue3";
import {ref, watchEffect} from "vue";

const props = defineProps({
  title: String,
  show: Boolean,
});

const emit = defineEmits(["close"]);

const form = useForm({
  razon_social: "",
  nombre_comercial:"",
});

const create = () => {
  form.post(route("empresas.store"), {
    preserveScroll: true,
    onSuccess: () => {
      emit("close");
      form.reset();
    },
    onError: () => null,
    onFinish: () => null,
  });
};

const resetForm = () =>{
  form.reset();
}

watchEffect(() => {
  if (props.show) {
    form.errors = {};
  }
});

</script>

<template>
  <Dialog
    v-model:visible="props.show"
    position="top"
    modal
    :header="'Nueva ' + props.title"
    :style="{ width: '30rem' }"
    :closable="false"
  >
    <form @submit.prevent="create">
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          <label for="nombres">Nombre Comercial</label>
          <InputText
            id="nombres"
            v-model="form.nombre_comercial"
            class="flex-auto"
            autocomplete="off"
            placeholder="Nombre Comercial"
          />
          <small v-if="form.errors.nombre_comercial" class="text-red-500">{{
            form.errors.nombre_comercial
          }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="apellidoPaterno">Razón Social</label>
          <InputText
            id="apellidoPaterno"
            v-model="form.razon_social"
            class="flex-auto"
            autocomplete="off"
            placeholder="Razón Social"
          />
          <small v-if="form.errors.razon_social" class="text-red-500">{{
            form.errors.razon_social
          }}</small>
        </div>
        <div class="flex justify-end gap-2">
          <Button
            type="button"
            label="Cancelar"
            severity="secondary"
            @click="emit('close'); resetForm()"
          ></Button>
          <Button type="submit" label="Guardar"></Button>
        </div>
      </div>
    </form>
  </Dialog>
</template>
