<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  empresa: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  id:0,
  nombre_comercial: "",
  razon_social: "",
});

const update = () => {
  form.put(route("empresas.update", props.empresa?.id), {
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
    form.id = props.empresa?.id;
    form.nombre_comercial = props.empresa?.nombre_comercial;
    form.razon_social = props.empresa?.razon_social;
  }
});

</script>

<template>
  <Dialog
    v-model:visible="props.show"
    position="top"
    modal
    :header="'Editar ' + props.title"
    :style="{ width: '30rem' }"
    :closable="false"
  >
    <form @submit.prevent="update">
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
            placeholder="Razon Social"
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
            @click="emit('close')"
          ></Button>
          <Button type="submit" label="Actualizar"></Button>
        </div>
      </div>
    </form>
  </Dialog>
</template>
