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
                        label="programa"
                        id="programa"
                        :options="data_pensiones"
                        v-model="program_selected"
                        placeholder="Búsqueda por nombre del programa de estudios"
                    ></v-select>
                </b-form-group>

                <b-form-group
                    id="input-group-2"
                    label="Especialidad:"
                    label-for="especialidad"
                    v-if="program_selected"
                >
                    <v-select
                        label="especialidad"
                        id="especialidad"
                        :options="program_selected.data_espes"
                        v-model="especialidad_selected"
                        placeholder="Búsqueda por nombre de la especialidad"
                    ></v-select>
                </b-form-group>

                <b-form-group
                    id="input-group-3"
                    label="Año de Ingreso:"
                    label-for="anho"
                    v-if="especialidad_selected"
                >
                    <b-form-select
                        id="anho"
                        v-model="pensiones_monto"
                        :options="especialidad_selected.data_anhos"
                        text-field="anho"
                        value-field="pensiones_monto"
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
                    id="input-group-4"
                    label="Pension:"
                    label-for="pension"
                    v-if="pensiones_monto"
                >
                    <b-form-select
                        id="pension"
                        v-model="form.pension"
                        @change="pensionNumberChanged"
                        required
                    >
                        <option :value="null" disabled
                            >-- Porfavor seleccione una opcion --</option
                        >
                        <option
                            v-for="option in pensiones_monto"
                            :value="option"
                        >
                            {{ option.cant_pens }}
                        </option>
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
import { loadPosgradoStudents } from "@/Api/loadStudents";

export default {
    name: "asistencias",
    props: ["data_pensiones"],
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
            program_selected: null,
            especialidad_selected: null,
            pensiones_monto: null,
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
        pensionNumberChanged(programa_pension) {
            if (!programa_pension) return;
            console.log(
                loadPosgradoStudents(
                    programa_pension.anho,
                    programa_pension.nues,
                    programa_pension.espe
                )
            );
        },
        save() {
            this.saving = true;
            console.log("Guardando datos...");
            this.saving = false;
        }
    }
};
</script>

<style lang="scss" scoped></style>
