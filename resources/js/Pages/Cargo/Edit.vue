<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  cargo: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  id:0,
  cargo: "",
});

const update = () => {
  form.put(route("cargos.update", props.cargo?.id), {
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
    form.id = props.cargo?.id;
    form.cargo = props.cargo?.cargo;
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
            @click="emit('close')"
          ></Button>
          <Button type="submit" label="Guardar"></Button>
        </div>
      </div>
    </form>
  </Dialog>
</template>
