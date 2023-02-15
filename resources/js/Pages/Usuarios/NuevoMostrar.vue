<template>
    <app-layout>
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <span class="font-weight-bold">{{ accion }} usuario</span>
            </div>
            <div class="card-body">
                <b-form @submit.prevent="enviar">
                    <b-row>
                        <b-col>
                            <b-form-group
                                id="input-group-1"
                                label="Nombre:"
                                label-for="input-1"
                            >
                                <b-form-input
                                    id="input-1"
                                    v-model="formData.name"
                                    placeholder="Nombre de usuario"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                                <div
                                    v-if="$page.props.errors.name"
                                    class="text-danger"
                                >
                                    {{ $page.props.errors.name[0] }}
                                </div>
                            </b-form-group>
                        </b-col>
                        <b-col>
                            <b-form-group
                                id="input-group-2"
                                label="Email:"
                                label-for="input-2"
                            >
                                <b-form-input
                                    id="input-2"
                                    v-model="formData.email"
                                    placeholder="Correo eletrónico"
                                    :readonly="accion == 'Mostrar'"
                                ></b-form-input>
                                <div
                                    v-if="$page.props.errors.email"
                                    class="text-danger"
                                >
                                    {{ $page.props.errors.email[0] }}
                                </div>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="6">
                            <b-form-group id="input-group-4" label="Rol (es):" label-for="input-4">
                                <b-form-select
                                    id="input-4"
                                    v-model="formData.roles_seleccionados"
                                    :options="roles"
                                    multiple
                                    :select-size="4"
                                    :disabled="accion == 'Mostrar'"
                                >
                                </b-form-select>
                                <div v-if="$page.props.errors.roles_seleccionados" class="text-danger">
                                    {{ $page.props.errors.roles_seleccionados[0] }}
                                </div>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-button
                        v-if="accion == 'Crear'"
                        @click="registrar"
                        variant="success"
                        >Registrar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Mostrar'"
                        @click="accion = 'Editar'"
                        variant="warning"
                        >Editar</b-button
                    >
                    <b-button
                        v-else-if="accion == 'Editar'"
                        @click="actualizar"
                        variant="success"
                        >Actualizar</b-button
                    >
                </b-form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "usuarios.mostrar",
    props: ["usuario", "roles"],
    components: {
        AppLayout
    },
    remember: 'formData',
    data() {
        return {
            accion: "",
            formData:this.usuario,
        };
    },
    created() {
        if (!this.usuario.id) {
            this.accion = "Crear"
        } else {
            this.accion = "Mostrar"
        }
    },
    methods: {
        enviar() {
            if (this.accion == 'Crear') {
                this.registrar()
            }
            else if (this.accion == 'Mostrar') {
                this.accion = 'Editar'
            }
            else if (this.accion == 'Editar') {
                this.actualizar()
            }
        },
        registrar() {
            this.$inertia.post(
                route("usuarios.registrar"),
                this.formData
            );
        },
        actualizar() {
            this.$inertia.post(
                route("usuarios.actualizar", [this.formData.id]),
                this.formData
            );
        },
    }
};
</script>
<style>
    .categoria {
        width: 175px;
        font-weight: bold;
    }
</style>
