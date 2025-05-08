<script setup>
import { useForm } from "@inertiajs/vue3";
import {ref, watchEffect} from "vue";

const props = defineProps({
  title: String,
  show: Boolean,
});

const emit = defineEmits(["close"]);

const form = useForm({
  cargo: "",
});

const create = () => {
  form.post(route("cargos.store"), {
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
    :header="'Nuevo ' + props.title"
    :style="{ width: '30rem' }"
    :closable="false"
  >
    <form @submit.prevent="create">
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          <label for="cargo">Cargo</label>
          <InputText
            id="cargo"
            v-model="form.cargo"
            class="flex-auto"
            autocomplete="off"
            placeholder="Nombre para el cargo"
          />
          <small v-if="form.errors.cargo" class="text-red-500">{{
            form.errors.cargo
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
