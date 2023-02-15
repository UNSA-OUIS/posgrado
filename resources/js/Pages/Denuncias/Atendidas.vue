<template>
  <app-layout>    
    <div class="card">
      <div class="card-header d-flex align-items-center text-light bg-secondary">                
        <span class="font-weight-bold">LISTA DE DENUNCIAS ATENDIDAS</span>        
      </div>               
      <div class="card-body">
        <b-alert
          show
          dismissible
          variant="success"
          v-if="$page.props.successMessage"
          >{{ $page.props.successMessage }}</b-alert
        >
        <b-alert
          show
          dismissible
          variant="danger"
          v-if="$page.props.errorMessage"
          >{{ $page.props.errorMessage }}</b-alert
        >
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
              <b-form-select
                v-model="perPage"
                id="perPageSelect"
                size="sm"
                :options="pageOptions"
              ></b-form-select>
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
                ></b-form-input>
                <b-input-group-append>
                  <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
        <b-table
          ref="tbl_atendidas"
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
          empty-text="No hay denuncias atendidas para mostrar"
          empty-filtered-text="No hay denuncias atendidas que coincidan con su búsqueda."
        >
          <template v-slot:cell(apn)="row">
            {{ row.item.administrado.apellidos }}, {{ row.item.administrado.nombres }}
          </template>        
           <template v-slot:cell(fecha_creacion)="row">
            {{ row.item.created_at | moment }}
          </template> 
           <template v-slot:cell(fecha_actualizacion)="row">
            {{ row.item.updated_at | moment }}
          </template>          
          <template v-slot:cell(acciones)="row">
            <inertia-link               
              class="btn btn-primary btn-sm"
              :href="route('denuncias.consultar', row.item.id)"
            >
              Mostrar
            </inertia-link>              
            <b-button
              v-if="!row.item.deleted_at"
              variant="warning"
              size="sm"
              title="Reenviar"
              @click="reenviar(row.item)"
            >
              Reenviar
            </b-button>
           
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
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

import moment from 'moment'

export default {
  name: "denuncias.atendidas.listar",
  components: {
    AppLayout,
    moment,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      conceptos: [],
      fields: [
        { key: "id", label: "ID", sortable: true, class: "text-center" },
        { key: "codigo", label: "Código", sortable: true, class: "text-center" },
        { key: "apn", label: "Administrado", sortable: true, class: "text-left" },        
        { key: "administrado.email", label: "Email", sortable: true, class: "text-left" },        
        { key: "fecha_creacion", label: "Fecha creación", class: "text-center" },        
        { key: "fecha_actualizacion", label: "Fecha respuesta", class: "text-center" },        
        { key: "acciones", label: "Acciones", class: "text-center" },
      ],
      index: 1,
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 20, 30],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
    };
  },
  filters: {
    moment: function (date) {      
      return moment(date).format("DD-MM-YYYY, hh:mm A")      
    }
  },
  created() {
    moment.locale('es')
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_conceptos.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.get(`${this.app_url}/denuncias_atendidas${params}`);

      return promise.then((response) => {
        const conceptos = response.data.data;
        this.totalRows = response.data.total;

        return conceptos || [];
      });
    },
    eliminar(concepto) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer desactivar este concepto?", {
          title: "Desactivar concepto",
          okVariant: "danger",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.delete(route("conceptos.eliminar", [concepto.id]));
            this.refreshTable();
          }
        });
    },
    reenviar(denuncia){
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer reenviar este correo a " + denuncia.administrado.nombres + " " + denuncia.administrado.apellidos + "?", {
          title: "Reenviar respuesta",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            axios
        .get(`${this.app_url}/reenviarRespuesta`, {
          params: {
            denuncia_id: denuncia.id,
          },
        })
        .then((response) => {
          var success = response.data.successMessage;
          if (!response.data.error){
            this.$bvToast.toast(response.data.successMessage, {
              title: `Correo reenviado`,
              variant: "success",
              solid: true,
            });
          }
          else{
            this.$bvToast.toast(response.data.errorMessage, {
              title: `Correo no reenviado`,
              variant: "danger",
              solid: true,
            });
          }
          //console.log(success);
        })
        .catch(function (error) {
          console.log(error);
        });
          }
        });
      
    },
    async restaurar(concepto) {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer restaurar este concepto?", {
          title: "Restaurar concepto",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          centered: true,
        })
        .then((value) => {
          if (value) {
            this.$inertia.post(route("conceptos.restaurar", [concepto.id]));
            this.refreshTable();
          }
        });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
  },
};
</script>
<style scoped>
    .breadcrumb li a {
        color: blue;
    }
    .breadcrumb {
        margin-bottom: 0;
        background-color: white;
    }
</style>