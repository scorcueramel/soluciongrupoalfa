<script setup>
import {useForm} from "@inertiajs/vue3";
import {watchEffect} from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  roles: Object,
  tiposdocumentos: Object,
});

const emit = defineEmits(["close"]);

const form = useForm({
  name: "",
  apellido_paterno: "",
  apellido_materno: "",
  codigo_poligrafista: "",
  numero_documento: "",
  telefono: "",
  email: "",
  password: "",
  password_confirmation: "",
  role: "",
  tipo_documento: "",
});

const create = () => {
  form.post(route("user.store"), {
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
    position="top"
    modal
    :header="'Nuevo ' + props.title"
    :style="{ width: '30rem' }"
    :closable="false"
    :tiposdocumentos="props.tiposdocumentos"
  >
    <form @submit.prevent="create">
      <div class="flex flex-col gap-4">
        <div class="flex flex-col gap-2">
          <label for="nombres">Nombres</label>
          <InputText
            id="nombres"
            v-model="form.name"
            class="flex-auto"
            autocomplete="off"
            placeholder="Nombres"
          />
          <small v-if="form.errors.name" class="text-red-500">{{
              form.errors.name
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="apellidoPaterno">Apellido Paterno</label>
          <InputText
            id="apellidoPaterno"
            v-model="form.apellido_paterno"
            class="flex-auto"
            autocomplete="off"
            placeholder="Aplleido paterno"
          />
          <small v-if="form.errors.apellido_paterno" class="text-red-500">{{
              form.errors.apellido_paterno
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="apellidoMaterno">Apellido Materno</label>
          <InputText
            id="apellidoMaterno"
            v-model="form.apellido_materno"
            class="flex-auto"
            autocomplete="off"
            placeholder="Aplleido materno"
          />
          <small v-if="form.errors.apellido_materno" class="text-red-500">{{
              form.errors.apellido_materno
            }}</small>
        </div>
        <div class="flex flex-col gap-2 hidden">
          <label for="codigoPoligrafista">Codigo</label>
          <InputText
            id="codigoPoligrafista"
            v-model="form.codigo_poligrafista"
            class="flex-auto"
            autocomplete="off"
            placeholder="Codigo"
            :disabled="true"
            readonly
          />
          <small v-if="form.errors.codigo_poligrafista" class="text-red-500">{{
              form.errors.codigo_poligrafista
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="tipoDocumento">Tipo de Documento</label>
          <Select
            v-model="form.tipo_documento"
            :options="props.tiposdocumentos"
            optionValue="code"
            optionLabel="name"
            placeholder="Seleccionar tipo de documento"
          />
          <small v-if="form.errors.tipo_documento" class="text-red-500">{{
              form.errors.tipo_documento
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="numeroDocumento">Nùmero de Documento</label>
          <InputText
            id="numeroDocumento"
            v-model="form.numero_documento"
            class="flex-auto"
            autocomplete="off"
            placeholder="Nùmero de documento"
          />
          <small v-if="form.errors.name" class="text-red-500">{{
              form.errors.numero_documento
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="telefono">Telèfono</label>
          <InputText
            id="telefono"
            v-model="form.telefono"
            class="flex-auto"
            autocomplete="off"
            placeholder="Nùmero de teléfono"
          />
          <small v-if="form.errors.name" class="text-red-500">{{
              form.errors.telefono
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="email">Correo Eléctronico</label>
          <InputText
            id="email"
            v-model="form.email"
            class="flex-auto"
            autocomplete="off"
            placeholder="Email"
          />
          <small v-if="form.errors.email" class="text-red-500">{{
              form.errors.email
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="password">Password</label>
          <InputText
            id="password"
            v-model="form.password"
            type="password"
            placeholder="Password"
            autocomplete="off"
          />
          <small v-if="form.errors.password" class="text-red-500">{{
              form.errors.password
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="password_confirmation">Confirmation Password</label>
          <InputText
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirmation Password"
          />
          <small v-if="form.errors.password_confirmation" class="text-red-500">{{
              form.errors.password_confirmation
            }}</small>
        </div>
        <div class="flex flex-col gap-2">
          <label for="role">Role</label>
          <Select
            v-model="form.role"
            :options="props.roles"
            optionValue="code"
            optionLabel="name"
            placeholder="Seleccionar rol"
          />
          <small v-if="form.errors.role" class="text-red-500">{{
              form.errors.role
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
