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
                        v-model="monto_pension"
                        @change="anhoProgramChanged"
                        required
                    >
                        <option :value="null" disabled
                            >-- Porfavor seleccione una opción --</option
                        >
                        <option
                            v-for="option in especialidad_selected.data_anhos"
                            :value="option"
                        >
                            {{ option.anho }}
                        </option>
                    </b-form-select>
                </b-form-group>

                <b-form-group
                    id="input-group-4"
                    label="Pensión:"
                    label-for="pension"
                    v-if="monto_pension"
                >
                    <b-form-select
                        id="pension"
                        v-model="pension"
                        @change="pensionNumberChanged"
                        required
                    >
                        <option :value="null" disabled
                            >-- Porfavor seleccione una opción --</option
                        >
                        <option
                            v-for="i in Array(monto_pension.cant_pens).keys()"
                            :value="i + 1"
                            :key="i"
                        >
                            {{ i + 1 }}
                        </option>
                    </b-form-select>
                </b-form-group>
            </div>
            <b-spinner label="loading" v-show="loading"></b-spinner>
            <b-table
                striped
                hover
                outlined
                head-variant="dark"
                class="mt-5 col-10"
                :items="matriculados"
                :fields="fields"
                v-if="!loading && matriculados.length"
            >
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(estado)="data">
                    <b-badge
                        :variant="
                            data.item.estado == null
                                ? 'secondary'
                                : data.item.estado == '2'
                                ? 'success'
                                : 'danger'
                        "
                        >{{
                            data.item.estado == null
                                ? "NO GENERADA"
                                : data.item.estado == "2"
                                ? "PAGADA"
                                : "NO PAGADA"
                        }}</b-badge
                    >
                </template>
                <template #cell(asistencia)="data">
                    <b-form-checkbox
                        :id="'asistencia-' + data.index"
                        v-model="data.item.asistencias[pension - 1]"
                    >
                    </b-form-checkbox>
                </template>
            </b-table>

            <div class="row w-100 my-5 justify-content-center">
                <b-button
                    variant="success"
                    :disabled="!matriculados.length || loading || saving"
                    @click="save"
                    >Guardar</b-button
                >
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { loadPosgradoStudents } from "@/Api/loadStudents";
import { loadAsistencias } from "@/Api/loadAsistencias";
import axios from "axios";

export default {
    name: "asistencias",
    props: ["data_pensiones"],
    components: {
        AppLayout
    },
    data() {
        return {
            program_selected: null,
            especialidad_selected: null,
            monto_pension: null,
            pension: null,
            matriculados: [],
            asistencias: [],
            fields: [
                { key: "index" },
                {
                    key: "cui",
                    sortable: true
                },
                {
                    key: "estudiante.apn",
                    label: "Alumno",
                    sortable: true
                },
                {
                    key: "estado",
                    label: "Estado Deuda"
                },
                {
                    key: "asistencia"
                }
            ],
            loading: false,
            saving: false
        };
    },
    watch: {
        program_selected(newValue, oldValue) {
            this.especialidad_selected = null;
            this.monto_pension = null;
            this.pension = null;
            this.matriculados = [];
            this.asistencias = [];
        },
        especialidad_selected(newValue, oldValue) {
            this.monto_pension = null;
            this.pension = null;
            this.matriculados = [];
            this.asistencias = [];
        }
    },
    methods: {
        async anhoProgramChanged(monto_pension) {
            if (!monto_pension) return;

            this.pension = 1; // Hay que verificar si tiene al menos una pension

            this.loading = true;
            const data = await loadPosgradoStudents(
                monto_pension.anho,
                monto_pension.nues,
                monto_pension.espe
            );

            if (data) {
                this.matriculados = data;
                this.matriculados.forEach(element => {
                    element.asistencias = new Array(
                        monto_pension.cant_pens
                    ).fill(false);
                });

                this.asistencias = await loadAsistencias(
                    monto_pension.anho,
                    monto_pension.nues,
                    monto_pension.espe
                );

                if (this.asistencias)
                    this.fillAsistenciasArrayFromData(this.asistencias);
            }

            this.pensionNumberChanged(true);
            this.loading = false;
        },
        fillAsistenciasArrayFromData(asistencias) {
            asistencias.forEach(asis => {
                let matriculado = this.matriculados.find(
                    m => m.cui == asis.cui
                );
                if (matriculado) {
                    for (let i = 0; i < matriculado.asistencias.length; i++) {
                        if (i >= asis.pension_asistio.length) {
                            break;
                        }
                        if (
                            matriculado.asistencias[i] !=
                            asis.pension_asistio[i]
                        ) {
                            this.$set(
                                matriculado.asistencias,
                                i,
                                asis.pension_asistio[i]
                            );
                        }
                    }
                }
            });
        },
        pensionNumberChanged(programa_pension) {
            if (!programa_pension) return;
            this.loading = true;
            this.getDeudasForProgramaPension().then(data => {
                this.fillInMatriculadosDeudasInfo(data);
            });
            const myTimeout = setTimeout(() => (this.loading = false), 200);
        },
        fillInMatriculadosDeudasInfo(deudas) {
            deudas.forEach(d => {
                const matriculado = this.matriculados.find(m => m.cui == d.cui);
                if (matriculado) {
                    this.$set(matriculado, "estado", d.status);
                }
            });
        },
        save() {
            this.$bvModal
                .msgBoxConfirm("Desea guardar los datos de asistencias?", {
                    title: "Porfavor Confirme",
                    size: "sm",
                    buttonSize: "sm",
                    okVariant: "danger",
                    okTitle: "SI",
                    cancelTitle: "NO",
                    footerClass: "p-2",
                    hideHeaderClose: false,
                    centered: true
                })
                .then(value => {
                    if (value) {
                        this.saveAsistencias();
                    }
                });
        },
        async saveAsistencias() {
            try {
                this.saving = true;

                let response = await axios.put(route("asistencias.save"), {
                    anho: this.monto_pension.anho,
                    matriculados: this.matriculados
                });

                return response.data;
            } catch (error) {
                console.log(error);
                return null;
            } finally {
                this.saving = false;
            }
        },
        async getDeudasForProgramaPension() {
            try {
                let response = await axios.post(route("programa.deudas"), {
                    anho: this.monto_pension.anho,
                    nues: this.monto_pension.nues,
                    espe: this.monto_pension.espe,
                    pension: this.pension
                });

                return response.data;
            } catch (error) {
                console.log(error);
                return null;
            }
        }
    }
};
</script>

<style lang="scss" scoped></style>
