<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";
import Create from "@/Pages/Formatos/HabilitarFormato.vue";
import Edit from "@/Pages/User/Edit.vue";
import {usePage, useForm, Head} from '@inertiajs/vue3';

import {onMounted, reactive, ref, watch, computed} from "vue";
import pkg from "lodash";
import {router} from "@inertiajs/vue3";

const {_, debounce, pickBy} = pkg;
import {loadToast} from '@/composables/loadToast';

// const props = defineProps({
//   title: String,
//   filters: Object,
//   users: Object,
//   roles: Object,
//   perPage: Number,
//   tiposdocumentos: Object,
// });
//
// const tiposDocumentosList = ref([]);
//
// loadToast();
//
// const deleteDialog = ref(false);
// const detailDialog = ref(false);
// const form = useForm({});
//
const data = reactive({
  params: {
    // search: props.filters.search,
    // field: props.filters.field,
    // order: props.filters.order,
    // perPage: props.perPage,
    allowFormatOpen: false,
    // editOpen: false,
  },
  user: null,
});
//
// const deleteData = () => {
//   deleteDialog.value = false;
//
//   form.delete(route("user.destroy", data.user?.id), {
//     preserveScroll: true,
//     onSuccess: () => {
//       form.reset();
//     },
//     onError: () => null,
//     onFinish: () => null,
//   });
// }
//
// const roles = props.roles?.map((role) => ({
//   name: role.name,
//   code: role.name,
// }));
//
// const onPageChange = (event) => {
//   router.get(route('user.index'), {page: event.page + 1}, {preserveState: true});
// };
//
// onMounted(() => {
//   props.tiposdocumentos.forEach((e) => {
//     tiposDocumentosList.value.push({
//       name: e.tipo_documento,
//       code: e.id,
//     });
//   });
// })
//
// watch(
//   () => _.cloneDeep(data.params),
//   debounce(() => {
//     let params = pickBy(data.params);
//     router.get(route("user.index"), params, {
//       replace: true,
//       preserveState: true,
//       preserveScroll: true,
//     });
//   }, 150)
// );

</script>

<template>
  <app-layout>
    <Head title="Acceso a Evaluados"></Head>
    <div class="card">
            <Create
              :show="data.allowFormatOpen"
              @close="data.allowFormatOpen = false"
            />
      <!--      <Edit-->
      <!--        :show="data.editOpen"-->
      <!--        @close="data.editOpen = false"-->
      <!--        :roles="roles"-->
      <!--        :user="data.user"-->
      <!--        :title="props.title"-->
      <!--        :tiposdocumentos="tiposdocumentos"-->
      <!--      />-->
            <!--Cambiar los permisos correspondientes a create allow format-->
            <Button v-show="can(['create user'])" label="Habilitar Formulario" @click="data.allowFormatOpen = true" icon="pi pi-plus"/>
      <!--      <DataTable-->
      <!--        :value="users.data"-->
      <!--        paginator-->
      <!--        :rows="users.per_page"-->
      <!--        :totalRecords="users.total"-->
      <!--        :first="(users.current_page - 1) * users.per_page"-->
      <!--        @page="onPageChange"-->
      <!--        tableStyle="min-width: 50rem"-->
      <!--        stripedRows-->
      <!--      >-->
      <!--        <template #header>-->
      <!--          <div class="flex justify-end">-->
      <!--            <IconField>-->
      <!--              <InputIcon>-->
      <!--                <i class="pi pi-search"/>-->
      <!--              </InputIcon>-->
      <!--              <InputText v-model="data.params.search" placeholder="Buscar Usuario..."/>-->
      <!--            </IconField>-->
      <!--          </div>-->
      <!--        </template>-->
      <!--        <template #empty> No data found.</template>-->
      <!--        <template #loading> Loading data. Please wait.</template>-->

      <!--        <Column header="No">-->
      <!--          <template #body="slotProps">-->
      <!--            {{ slotProps.index + 1 }}-->
      <!--          </template>-->
      <!--        </Column>-->

      <!--        <Column field="name" header="Nombres"></Column>-->
      <!--        <Column field="apellido_paterno" header="Ap. Paternos"></Column>-->
      <!--        <Column field="apellido_materno" header="Ap. Maternos"></Column>-->
      <!--        <Column field="email" header="Correo"></Column>-->
      <!--        <Column header="Rol">-->
      <!--          <template #body="slotProps">-->
      <!--            {{ slotProps.data.roles[0].name }}-->
      <!--          </template>-->
      <!--        </Column>-->
      <!--        <Column field="created_at" header="Fec. Registro"></Column>-->
      <!--        <Column field="updated_at" header="Fec. Actualización"></Column>-->
      <!--        <Column :exportable="false" style="min-width: 12rem">-->
      <!--          <template #body="slotProps">-->
      <!--            <Button v-show="can(['update user'])" icon="pi pi-pencil" outlined rounded class="mr-2" @click="-->
      <!--                                                    (data.editOpen = true),-->
      <!--                                                        (data.user = slotProps.data)"/>-->
      <!--            <Button v-show="can(['delete user'])" icon="pi pi-trash" outlined rounded severity="danger"-->
      <!--                    @click="deleteDialog = true; data.user = slotProps.data"/>-->
      <!--            <Button icon="pi pi-eye" outlined rounded severity="info" class="ml-2"-->
      <!--                    @click="detailDialog = true; data.user = slotProps.data"/>-->
      <!--          </template>-->
      <!--        </Column>-->
      <!--      </DataTable>-->

      <!--      <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Eliminar Usuario" :modal="true">-->
      <!--        <div class="flex items-center gap-4">-->
      <!--          <i class="pi pi-exclamation-triangle !text-3xl"/>-->
      <!--          <span v-if="data.user"-->
      <!--          >¿Estás seguro de eliminar al usuario <b>{{ data.user.name }}</b-->
      <!--          >?</span-->
      <!--          >-->
      <!--        </div>-->
      <!--        <template #footer>-->
      <!--          <Button label="No" icon="pi pi-times" text @click="deleteDialog = false"/>-->
      <!--          <Button label="Eliminar" icon="pi pi-check" @click="deleteData"/>-->
      <!--        </template>-->
      <!--      </Dialog>-->

      <!--      <Dialog v-model:visible="detailDialog" :style="{ width: '450px' }" header="Datos del usuario" :modal="true">-->
      <!--        <div class="flex items-center gap-4">-->
      <!--          <i class="pi pi-user !text-3xl"/>-->
      <!--          <div class="grig grid-cols-1">-->
      <!--            <div class="col-span-2">-->
      <!--              <span v-if="data.user">Nombres y Apellidos: <b class="ms-2">{{ data.user.name }} {{ data.user.apellido_paterno }} {{ data.user.apellido_materno }}</b></span>-->
      <!--            </div>-->
      <!--          </div>-->
      <!--        </div>-->
      <!--        <template #footer>-->
      <!--          <Button label="Cerrar" text @click="detailDialog = false"/>-->
      <!--        </template>-->
      <!--      </Dialog>-->
    </div>
  </app-layout>
</template>

<style scoped lang="scss">

</style>
