<template>
    <app-layout>
        <div class="card row align-items-center">
            <div class="card-body col-10">
                <b-form-group
                    id="input-group-1"
                    label="Programa de estudios:"
                    label-for="programa"
                >
                    <v-select
                        label="name"
                        id="programa"
                        :options="programas"
                        v-model="form.programa_id"
                        :reduce="programa => programa.nues"
                        placeholder="Búsqueda por nombre del programa de estudios"
                    ></v-select>
                </b-form-group>

                <b-form-group
                    id="input-group-2"
                    label="Año de Ingreso:"
                    label-for="input-2"
                >
                    <b-form-select
                        id="input-2"
                        v-model="form.anio"
                        :options="anios"
                        :disabled="!form.programa_id"
                        required
                    >
                        <template #first>
                            <b-form-select-option :value="null" disabled
                                >-- Porfavor seleccione una opcion
                                --</b-form-select-option
                            >
                        </template>
                    </b-form-select>
                </b-form-group>

                <b-form-group
                    id="input-group-3"
                    label="Pension:"
                    label-for="input-3"
                >
                    <b-form-select
                        id="input-3"
                        v-model="form.pension"
                        :options="pensiones"
                        :disabled="!form.programa_id"
                        required
                    >
                        <template #first>
                            <b-form-select-option :value="null" disabled
                                >-- Porfavor seleccione una opcion
                                --</b-form-select-option
                            >
                        </template>
                    </b-form-select>
                </b-form-group>
            </div>
            <b-table
                striped
                hover
                outlined
                head-variant="dark"
                class="mt-5 col-10"
                :items="estudiantes"
            >
                <template #cell(asistencia)="data">
                    <b-form-checkbox
                        :id="'asistencia-' + data.index"
                        v-model="data.item.asistencia"
                    >
                    </b-form-checkbox>
                </template>
            </b-table>

            <div class="row w-100 mb-5 justify-content-center">
                <b-button variant="success" :disabled="saving" @click="save"
                    >Guardar</b-button
                >
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "asistencias",
    components: {
        AppLayout
    },
    data() {
        return {
            programas: [
                {
                    nues: 201,
                    name: "Maestria en sistemas"
                },
                { nues: 202, name: "Maestria en Ciencias de la Computacion" }
            ],
            anios: [2020, 2021, 2022, 2023, 2024],
            pensiones: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            estudiantes: [
                {
                    cui: 20141025,
                    nombre: "Renzo siza tejada",
                    estado: "PAGADO",
                    asistencia: false
                },
                {
                    cui: 20182020,
                    nombre: "Renzo siza tejada",
                    estado: "PAGADO",
                    asistencia: false
                }
            ],
            form: {
                programa_id: null,
                anio: null,
                pension: null,
                estudiantes: null
            },
            saving: false
        };
    },
    methods: {
        save() {
            this.saving = true;
            console.log("Guardando datos...");
            this.saving = false;
        }
    }
};
</script>

<style lang="scss" scoped></style>
