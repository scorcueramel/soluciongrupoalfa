<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";
import Create from "@/Pages/Formatos/HabilitarFormato.vue";
import Edit from "@/Pages/User/Edit.vue";
import {usePage, useForm, Head} from '@inertiajs/vue3';

import {reactive, ref, watch, computed} from "vue";
import pkg from "lodash";
import {router} from "@inertiajs/vue3";

const {_, debounce, pickBy} = pkg;
import {loadToast} from '@/composables/loadToast';

const props = defineProps({
  filters: Object,
  evaluados: Object,
  perPage: Number,
});

loadToast();

// const deleteDialog = ref(false);
// const detailDialog = ref(false);
const form = useForm({});

const data = reactive({
  params: {
    search: props.filters.search,
    field: props.filters.field,
    order: props.filters.order,
    perPage: props.perPage,
    allowFormatOpen: false,
    editOpen: false,
  },
  evaludado: null,
});

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

const onPageChange = (event) => {
  // router.get(route('user.index'), {page: event.page + 1}, {preserveState: true});
};


watch(
  () => _.cloneDeep(data.params),
  debounce(() => {
    let params = pickBy(data.params);
    router.get(route("user.index"), params, {
      replace: true,
      preserveState: true,
      preserveScroll: true,
    });
  }, 150)
);

const restringirAcceso = (data) => {
  Swal.fire({
    title: "¿Restringir acceso a formato?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      router.get(`/formatos/${data.id}/accesos`, {
          preserveScroll: true,
          onSuccess: () => null,
          onError: () => null,
          onFinish: () => {
            console.log('alerta')
          },
        }
      )
      ;
    }
  });
}

const permitirAcceso = (data) => {
  Swal.fire({
    title: "¿Permitir acceso a formato?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      router.get(`/formatos/${data.id}/accesos`, {
          preserveScroll: true,
          onSuccess: () => null,
          onError: () => null,
          onFinish: () => {
            console.log('alerta')
          },
        }
      )
      ;
    }
  });
}

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
      <Button v-show="can(['create user'])" label="Acceso a Formato" @click="data.allowFormatOpen = true"
              icon="pi pi-plus"/>
      <DataTable
        :value="evaluados.data"
        paginator
        :rows="evaluados.per_page"
        :totalRecords="evaluados.total"
        :first="(evaluados.current_page - 1) * evaluados.per_page"
        @page="onPageChange"
        tableStyle="min-width: 50rem"
        stripedRows
      >
        <template #header>
          <div class="flex justify-end">
            <IconField>
              <InputIcon>
                <i class="pi pi-search"/>
              </InputIcon>
              <InputText v-model="data.params.search" placeholder="Buscar Evaluado..."/>
            </IconField>
          </div>
        </template>
        <template #empty> Sin datos para mostrar.</template>
        <template #loading> Cargando datos. Porfavor espere.</template>

        <Column header="No">
          <template #body="slotProps">
            {{ slotProps.index + 1 }}
          </template>
        </Column>

        <Column field="documento_persona" header="Documento evaluado"></Column>
        <Column>
          <template #header>Acceso a formato</template>
          <template #body="slotProps">
              <Button v-show="slotProps.data.acceso_formato" outlined label="SI" text severity="success"
                      @click="restringirAcceso(slotProps.data)"/>
              <Button v-show="!slotProps.data.acceso_formato" outlined text severity="danger"
                      @click="permitirAcceso(slotProps.data)">NO
              </Button>
          </template>
        </Column>
        <Column>
          <template #header>Cod. Poligrafista</template>
          <template #body="slotProps">
            <div class="text-center">
              {{ slotProps.data.codigo_poligrafista + "" + slotProps.data.numero_evaluaciones }}
            </div>
          </template>
        </Column>
        <Column field="fecha_examen" header="Fecha Evaluación"></Column>

        <!--              <Column :exportable="false" style="min-width: 12rem">-->
        <!--                <template #body="slotProps">-->
        <!--                  <Button v-show="can(['update user'])" icon="pi pi-pencil" outlined rounded class="mr-2" @click="-->
        <!--                                                          (data.editOpen = true),-->
        <!--                                                              (data.user = slotProps.data)"/>-->
        <!--                  <Button v-show="can(['delete user'])" icon="pi pi-trash" outlined rounded severity="danger"-->
        <!--                          @click="deleteDialog = true; data.user = slotProps.data"/>-->
        <!--                  <Button icon="pi pi-eye" outlined rounded severity="info" class="ml-2"-->
        <!--                          @click="detailDialog = true; data.user = slotProps.data"/>-->
        <!--                </template>-->
        <!--              </Column>-->
      </DataTable>

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
