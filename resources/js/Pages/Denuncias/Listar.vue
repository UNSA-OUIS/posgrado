<template>
  <app-layout>
    <div class="card">
      <div
        class="card-header d-flex align-items-center text-light bg-secondary"
      >
        <span class="font-weight-bold">LISTA DE DENUNCIAS REALIZADAS</span>
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
                  <b-button :disabled="!filter" @click="filter = ''"
                    >Limpiar</b-button
                  >
                </b-input-group-append>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
        <b-table
          ref="tbl_denuncias"
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
          empty-text="No hay denuncias para mostrar"
          empty-filtered-text="No hay denuncias que coincidan con su búsqueda."
        >
          <template v-slot:cell(descripcion)="row">
            {{ row.item.descripcion }}
            <a
              v-if="row.item.url_archivo"
              :href="`${app_url}/archivo?url_archivo=${row.item.url_archivo}`"
              target="_blank"
            >
              <b-badge variant="secondary">Archivo</b-badge>
            </a>
          </template>
          <template v-slot:cell(estado)="row">
            <b-badge v-if="row.item.estado == 'RECIBIDO'" variant="warning"
              >Recibido</b-badge
            >
            <b-badge v-else-if="row.item.estado == 'ATENDIDO'" variant="success"
              >Atendido</b-badge
            >
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

export default {
  name: "denuncias.listar",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      fields: [
        { key: "codigo", label: "Codigo", class: "text-center" },
        { key: "descripcion", label: "Denuncia" },
        { key: "respuesta", label: "Respuesta" },
        { key: "estado", label: "Estado", class: "text-center" },
      ],
      index: 1,
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50],
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
    };
  },
  methods: {
    refreshTable() {
      this.$refs.tbl_denuncias.refresh();
    },
    myProvider(ctx) {
      let params = "?page=" + ctx.currentPage + "&size=" + ctx.perPage;

      if (ctx.filter !== "" && ctx.filter !== null) {
        params += "&filter=" + ctx.filter;
      }

      if (ctx.sortBy !== "" && ctx.sortBy !== null) {
        params += "&sortby=" + ctx.sortBy + "&sortdesc=" + ctx.sortDesc;
      }

      const promise = axios.post(`${this.app_url}/denuncias/listar${params}`);

      return promise.then((response) => {
        const denuncias = response.data.data;
        this.totalRows = response.data.total;

        return denuncias || [];
      });
    },
    onFiltered(filteredItems) {
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    visualizar(url_archivo) {
      window.open(
        `${this.app_url}/archivo?url_archivo=${url_archivo}`,
        "_blanck"
      );
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
