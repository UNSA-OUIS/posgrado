<template>
  <app-layout>
    <div class="row">
      <div class="card" style="width: 100%">
        <div class="card-body">
          <h5 class="card-title" style="text-align: center; font-size: 70px">
            Resumen de denuncias
          </h5>

          <hr />

          <div class="mt-10">
            <ul class="list-inline main-chart mb-0" style="text-align: center">
              <li
                style="font-size: 70px"
                class="list-inline-item chart-border-left mr-0 border-0"
              >
                <h3>
                  <span data-plugin="counterup">{{total_recibidas}}</span>&nbsp;<span
                    class="text-warning d-inline-block"
                    >denuncias recibidas</span
                  >
                </h3>
              </li>
              <li
                style="font-size: 70px"
                class="list-inline-item chart-border-left mr-0"
              >
                <h3>
                  <span data-plugin="counterup">{{total_atendidas}}</span>&nbsp;<span
                    class="text-success d-inline-block"
                    >denuncias atendidas</span
                  >
                </h3>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      total_recibidas: "",
      total_atendidas: "",
    }
  },
  async created() {
     try {
      const response = await axios.get(
                `${this.app_url}/totales/`
            );
      this.total_recibidas = response.data.total_recibidas;
      this.total_atendidas = response.data.total_atendidas;
    } catch (error) {
      console.log(error)
    }
    
  }
};
</script>
