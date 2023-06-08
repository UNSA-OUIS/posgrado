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
                            <b-form-group
                                id="input-group-4"
                                label="Rol (es):"
                                label-for="input-4"
                            >
                                <b-form-select
                                    id="input-4"
                                    v-model="formData.roles_seleccionados"
                                    :options="roles"
                                    multiple
                                    :select-size="4"
                                    :disabled="accion == 'Mostrar'"
                                >
                                </b-form-select>
                                <div
                                    v-if="
                                        $page.props.errors.roles_seleccionados
                                    "
                                    class="text-danger"
                                >
                                    {{
                                        $page.props.errors
                                            .roles_seleccionados[0]
                                    }}
                                </div>
                            </b-form-group>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="8">
                            <b-form-group
                                id="input-group-1"
                                label="Programas autorizados para el usuario:"
                                label-for="programa"
                            >
                                <v-select
                                    label="nesc"
                                    id="programa"
                                    :options="programas"
                                    v-model="programa_seleccionado"
                                    :disabled="accion == 'Mostrar'"
                                    placeholder="Búsqueda por nombre del programa de estudios"
                                ></v-select>
                            </b-form-group>
                            <b-form-group
                                id="input-group-2"
                                label="Especialidad:"
                                label-for="especialidad"
                                v-show="especialidades.length > 0"
                            >
                                <v-select
                                    label="nomesp"
                                    id="especialidad"
                                    :options="especialidades"
                                    v-model="especialidad_seleccionada"
                                    placeholder="Búsqueda por nombre de la especialidad"
                                    :disabled="accion == 'Mostrar'"
                                ></v-select>
                            </b-form-group>
                            <b-button
                                class="my-2"
                                variant="success"
                                @click="addProgramaToUsuario"
                                :disabled="accion == 'Mostrar'"
                                >Autorizar programa seleccionado</b-button
                            >

                            <b-table
                                striped
                                hover
                                head-variant="dark"
                                :items="user_programas"
                                :fields="fields"
                            >
                                <template v-slot:cell(acciones)="row">
                                    <b-button
                                        variant="danger"
                                        size="sm"
                                        @click="
                                            modal_eliminar_programa(row.item)
                                        "
                                        :disabled="accion == 'Mostrar'"
                                    >
                                        <b-icon
                                            icon="trash"
                                        ></b-icon> </b-button
                                ></template>
                            </b-table>
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
import axios from "axios";

export default {
    name: "usuarios.mostrar",
    props: ["usuario", "roles", "user_programas", "programas"],
    components: {
        AppLayout
    },
    remember: "formData",
    data() {
        return {
            accion: "",
            formData: this.usuario,
            fields: [
                { key: "nues", label: "Nues" },
                { key: "programa.nesc", label: "Programa" },
                { key: "especialidad.nomesp", label: "Especialidad" },
                { key: "acciones", label: "Acciones", class: "text-center" }
            ],
            programa_seleccionado: null,
            especialidad_seleccionada: null,
            especialidades: []
        };
    },
    watch: {
        programa_seleccionado(newValue, oldValue) {
            this.especialidad_seleccionada = null;
            this.loadEspecialidades(newValue.nues);
        }
    },
    created() {
        if (!this.usuario.id) {
            this.accion = "Crear";
        } else {
            this.accion = "Mostrar";
        }
    },
    methods: {
        enviar() {
            if (this.accion == "Crear") {
                this.registrar();
            } else if (this.accion == "Mostrar") {
                this.accion = "Editar";
            } else if (this.accion == "Editar") {
                this.actualizar();
            }
        },
        registrar() {
            this.$inertia.post(route("usuarios.registrar"), this.formData);
        },
        actualizar() {
            this.$inertia.post(
                route("usuarios.actualizar", [this.formData.id]),
                this.formData
            );
        },
        modal_eliminar_programa(item) {
            this.$bvModal
                .msgBoxConfirm(
                    "¿Esta seguro de querer eliminar este programa del usuario?",
                    {
                        title: "Eliminar",
                        okVariant: "danger",
                        okTitle: "SI",
                        cancelTitle: "NO",
                        centered: true
                    }
                )
                .then(value => {
                    if (value) {
                        this.$inertia.delete(
                            route("usuarios.programas.destroy", [item.id])
                        );
                    }
                });
        },
        loadEspecialidades(nues) {
            axios
                .get(route("programa.especialidades", [nues]))
                .then(response => {
                    this.especialidades = response.data;
                })
                .catch(error => console.log(error));
        },
        addProgramaToUsuario() {
            if (!this.programa_seleccionado) return;
            this.$inertia.put(route("usuarios.programas.store"), {
                nues: this.programa_seleccionado.nues,
                espe: this.especialidad_seleccionada?.numesp
            });
        }
    }
};
</script>
<style>
.categoria {
    width: 175px;
    font-weight: bold;
}
</style>
