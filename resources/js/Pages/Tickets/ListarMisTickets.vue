<template>
  <app-layout>
    <b-container>
        <div class="card">
            <div class="card-header d-flex align-items-center">                
                <span class="font-weight-bold">Lista de tickets</span>
            </div>    
            <div class="row justify-content-center mb-1">
                <fieldset class="col-12 col-md-6 px-3">
                    <legend>Conteo de tickets:</legend>       
                    <b-table-simple hover small caption-top stacked>
                        <b-thead head-variant="dark">   
                        <b-tr>
                            <b-th>Entregados</b-th> 
                        </b-tr>
                        </b-thead>
                        <b-tbody>
                        <b-tr>
                            <b-th stacked-heading="Entregados" v-text="recogidos"></b-th>
                        </b-tr>     
                        </b-tbody>
                    </b-table-simple>    
                </fieldset>
            </div>        
            <div class="card-body">
                <b-row>
                    <b-col sm="12" md="4" lg="4" class="my-1">
                        <b-form-group
                            label="Registros por página: "
                            label-cols-sm="6"
                            label-align-sm="right"
                            label-size="sm"
                            label-for="perPageSelect"
                            class="mb-0"
                            >
                            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                        </b-form-group>
                    </b-col>
                    <b-col sm="12" offset-md="3" md="5" lg="5" class="my-1">
                        <b-form-group
                        label="Buscar: "
                        label-cols-sm="3"
                        label-align-sm="right"
                        label-size="sm"
                        label-for="filterInput"
                        class="mb-0"
                        >
                            <b-input-group size="sm">
                                <b-form-input
                                    v-model="filter"
                                    type="search"
                                    id="filterInput"
                                    placeholder="Escriba el texto a buscar..."
                                    v-b-tooltip.hover.left title="Escriba el texto a buscar..."
                                ></b-form-input>
                                <b-input-group-append>
                                    <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                                </b-input-group-append>
                            </b-input-group>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-table
                    ref="tbl_particulares"
                    show-empty
                    striped
                    hover
                    bordered
                    small
                    responsive
                    stacked="md"
                    :items="myProvider"  
                    :fields="fields"
                    :current-page="currentPage"
                    :per-page="perPage"
                    :filter="filter"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :sort-direction="sortDirection"
                    @filtered="onFiltered"   
                    empty-text="No hay particulares para mostrar"
                    empty-filtered-text="No hay particulares que coincidan con su búsqueda." 
                >
                    <template v-slot:cell(estado)="row">
                        <b-badge v-if="row.item.recogido" variant="success">Entregado</b-badge>
                        <b-badge v-else variant="warning">Pendiente</b-badge>
                    </template>
                </b-table>
                <b-row>
                    <b-col offset-md="8" md="4" class="my-1">
                        <b-pagination
                            v-model="currentPage"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            align="fill"
                            size="sm"
                            class="my-0"
                        ></b-pagination>
                    </b-col>
                </b-row>
            </div>
        </div> 
    </b-container>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "tickets",
  props: ["recogidos"],
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
                fields: [
                    { key: "orden", label: "Nro. orden", class: "text-center", sortable: true },
                    { key: "dni", label: "DNI", sortable: true },
                    { key: "apn", label: "Apellidos y nombres", sortable: true },
                    { key: "tipo_personal", label: "Tipo personal", class: "text-center" },
                    { key: "area", label: "Área", class: "text-center" },                 
                    { key: "estado", label: "Estado", class: "text-center" },
                ],
                index: 1,
                totalRows: 1,
                currentPage: 1,
                perPage: 15,
                pageOptions: [15, 25, 50],
                sortBy: null,
                sortDesc: false,
                sortDirection: 'asc',
                filter: null,          
            };
  },  
  computed: {
    
  },
  watch: {
    
  },
  methods: {
    refreshTable() {
                this.$refs.tbl_particulares.refresh();
            },     
            myProvider(ctx) {                
                let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

                if (ctx.filter !== "" && ctx.filter !== null) {                    
                    params += "&filter=" + ctx.filter;
                }

                if (ctx.sortBy !== "" && ctx.sortBy !== null) {
                    params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
                }

                const promise = axios.get(`${this.app_url}/tickets-atendidos/listar${params}`)
                
                return promise.then(response => {                       
                    const tickets = response.data.data                                   
                    this.totalRows = response.data.total;

                    return tickets || []
                })
            },  
            
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1;
            },   
  },
};
</script>
<style scoped>
    fieldset {
        border-radius: 4px;
        border: 1px solid #ddd;
        background-color: #fff;
        padding-bottom: 10px;
        height: auto;
    }
    legend {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        padding: 3px 5px 3px 7px;
        width: auto;
    }    
</style>
