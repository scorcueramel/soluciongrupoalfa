
<script setup>
import AppLayout from "@/sakai/layout/AppLayout.vue";
import Create from "@/Pages/User/Create.vue";
import Edit from "@/Pages/User/Edit.vue";
import { usePage, useForm } from '@inertiajs/vue3';

import { onMounted, reactive, ref, watch, computed } from "vue";
import pkg from "lodash";
import { router } from "@inertiajs/vue3";
const { _, debounce, pickBy } = pkg;
import { loadToast } from '@/composables/loadToast';

const props = defineProps({
    title: String,
    filters: Object,
    users: Object,
    roles: Object,
    perPage: Number,
    tiposdocumentos : Object,
});

const tiposDocumentosList = ref([]);
const usuariosList = ref([]);

loadToast();

const deleteDialog = ref(false);
const form = useForm({});

const data = reactive({
    params: {
        search: props.filters.search,
        field: props.filters.field,
        order: props.filters.order,
        // perPage: props.perPage,
        createOpen: false,
        editOpen: false,
    },
    user: null,
});

const deleteData = () => {
    deleteDialog.value = false;

    form.delete(route("user.destroy", data.user?.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => null,
        onFinish: () => null,
    });
}

const roles = props.roles?.map((role) => ({
    name: role.name,
    code: role.name,
}));

const onPageChange = (event) => {
    router.get(route('user.index'), { page: event.page + 1 }, { preserveState: true });
};

onMounted(() => {
  props.tiposdocumentos.forEach((e) => {
    tiposDocumentosList.value.push({
      name: e.tipo_documento,
      code: e.id,
    });
  });

  props.users.data.forEach((user) => {
    usuariosList.value.push(user);
  });
})

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
</script>

<template>
    <app-layout>
        <div class="card">
            <Create
                :show="data.createOpen"
                @close="data.createOpen = false"
                :roles="roles"
                :title="props.title"
                :tiposdocumentos="tiposDocumentosList"
            />
            <Edit
                :show="data.editOpen"
                @close="data.editOpen = false"
                :roles="roles"
                :user="data.user"
                :title="props.title"
                :tiposdocumentos="tiposdocumentos"
            />
            <Button v-show="can(['create user'])" label="Nuevo usuario" @click="data.createOpen = true" icon="pi pi-plus" />
            <DataTable
              lazy
              :value="usuariosList"
              paginator
              :rows="users.per_page"
              :totalRecords="users.total"
              :first="(users.current_page - 1) * users.per_page"
              @page="onPageChange"
              tableStyle="min-width: 50rem"
              stripedRows
            >
                <template #header>
                    <div class="flex justify-end">
                        <IconField>
                            <InputIcon>
                                <i class="pi pi-search" />
                            </InputIcon>
                            <InputText v-model="data.params.search" placeholder="Buscar Usuario..." />
                        </IconField>
                    </div>
                </template>
                <template #empty> No data found. </template>
                <template #loading> Loading data. Please wait. </template>

                <Column header="No">
                    <template #body="slotProps">
                        {{slotProps.index + 1}}
                    </template>
                </Column>

                <Column field="name" header="Name"></Column>
                <Column field="email" header="Email"></Column>
                <Column header="Role">
                    <template #body="slotProps">
                       {{ slotProps.data.roles[0].name }}
                    </template>
                </Column>
                <Column field="created_at" header="Created"></Column>
                <Column field="updated_at" header="Updated"></Column>
                <Column :exportable="false" style="min-width: 12rem">
                    <template #body="slotProps">
                        <Button v-show="can(['update user'])" icon="pi pi-pencil" outlined rounded class="mr-2"  @click="
                                                    (data.editOpen = true),
                                                        (data.user = slotProps.data)" />
                        <Button v-show="can(['delete user'])" icon="pi pi-trash" outlined rounded severity="danger" @click="deleteDialog = true; data.user = slotProps.data" />
                    </template>
                </Column>
            </DataTable>

            <Dialog v-model:visible="deleteDialog" :style="{ width: '450px' }" header="Eliminar Usuario" :modal="true">
                <div class="flex items-center gap-4">
                    <i class="pi pi-exclamation-triangle !text-3xl" />
                    <span v-if="data.user"
                        >¿Estás seguro de eliminar al usuario <b>{{ data.user.name }}</b
                        >?</span
                    >
                </div>
                <template #footer>
                    <Button label="No" icon="pi pi-times" text @click="deleteDialog = false" />
                    <Button label="Eliminar" icon="pi pi-check" @click="deleteData" />
                </template>
            </Dialog>
        </div>
    </app-layout>
</template>

<style scoped lang="scss">

</style>
