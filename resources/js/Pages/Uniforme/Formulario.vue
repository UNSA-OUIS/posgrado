<template>
  <app-layout>
    <b-container>
      <div class="row justify-content-center mb-1">
        <fieldset class="col-12 col-md-6 px-3">
            <legend>Opciones de búsqueda:</legend>
            <div class="row justify-content-center">
                <b-form class="justify-content-center" inline v-on:submit.prevent="buscarTicket">
                    <b-form-select
                        v-model="opcion_busqueda"
                        size="lg"
                        id="inline-form-custom-select-pref"
                        class="mb-2 mr-sm-2 mb-sm-0"
                        :options="opciones"
                        :value="null"
                        @change="change_select"
                    >
                        <template v-slot:first>
                            <option :value="null" disabled>Elija una opción...</option>
                        </template>
                    </b-form-select>
                    <b-form-input
                        v-model="filtro"
                        :state="validation"
                        size="lg"
                        id="inline-form-input-name"
                        class="mb-2 mr-sm-2 mb-sm-0"
                        trim
                        required
                    ></b-form-input>
                    <b-button v-if="opcion_busqueda === 'APN'" size="sm" variant="primary" type="submit">Buscar</b-button>                   
                </b-form>
                <div class="container">
                  <b-alert :show="validation==false" variant="danger">{{this.form_validation_variables.info_message}}</b-alert>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="row justify-content-center mb-1" v-if="mostrar_resultado">
      <fieldset class="col-12 col-md-6 px-3">
          <legend>Resultados de búsqueda:</legend> 
          <b-alert
            class="mb-2 mr-sm-2 mb-sm-0"
            variant="danger"
            show
            v-show="alerta"
          >
            {{ this.alerta_mensaje }}
          </b-alert>         
          <b-table-simple hover small caption-top stacked v-if="formData !== null && opcion_busqueda == 'DNI'">
            <b-thead head-variant="dark">   
              <b-tr>
                <b-th>DNI:</b-th>
                <b-th>Nro. Orden:</b-th>
                <b-th>Apellidos y Nombres:</b-th>
                <b-th>Tipo Personal:</b-th>     
                <b-th>Estado:</b-th>    
              </b-tr>
            </b-thead>
            <b-tbody class="text-size">
              <b-tr>
                <!--<b-td class="text-big" stacked-heading="Mesa:" v-text="formData.mesa"></b-td>-->
                <b-td stacked-heading="Nro. Orden:" v-text="formData.orden"></b-td>
                <b-td stacked-heading="DNI:" v-text="formData.dni"></b-td>
                <b-th class="txt_center">{{formData.apn}}</b-th>
                <b-td stacked-heading="Tipo Personal:" v-text="formData.tipo_personal"></b-td>                
                <b-td v-if="formData.area !== null" class="text-size1" stacked-heading="Área:" v-text="formData.area"></b-td> 
                <b-td v-else class="text-size1" stacked-heading="Área:">-</b-td>            
                <b-td v-if="formData.recogido == 0">
                  <h2 class="text-center"><b-badge class="mx-auto" variant="success">No Recogido</b-badge></h2>
                </b-td>
                <b-td v-else>
                  <h2 class="text-center"><b-badge class="mx-auto" variant="danger">Recogido</b-badge></h2>
                </b-td>
                <b-td colspan="4"></b-td>
                <b-td colspan="4"></b-td>
                <b-td colspan="4" class="text-center">
                  <b-button block :disabled="formData.recogido == 1" variant="primary" @click="registrar">Registrar</b-button>
                </b-td>
              </b-tr>     
            </b-tbody>
          </b-table-simple>   
          <b-table
            v-if="opcion_busqueda == 'APN'"
            ref="tbl_personal"
            show-empty
            striped
            hover
            bordered
            small
            responsive
            stacked="md"
            :items="items"
            :fields="fields"                      
            empty-text="No hay personal para mostrar"
            empty-filtered-text="No hay personal que coincidan con su búsqueda."            
        >                
            <template v-slot:cell(apn)="row">                
                <a href="#" @click="mostrarData(row.item)">{{ row.item.apn }}</a>                
            </template>                     
        </b-table>       
      </fieldset>
  </div>
    </b-container>
  </app-layout>
</template>

<script>
const axios = require("axios");
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "uniformes",
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      formData: null,
      items: [],
      opcion_busqueda: 'DNI',
      mostrar_resultado: false,
      opciones: [
          { value: 'DNI', text: 'DNI' },
          { value: 'APN', text: 'Apellidos y nombres' },
      ],
      tipos_documentos: [
        { text: "Elija un tipo de documento", value: null },
        { text: "DNI", value: "DNI" },
        { text: "PASAPORTE", value: "PASAPORTE" },
        { text: "CARNET DE EXTRANJENRIA", value: "CARNET_EXTRANJERIA" },
      ],
      fields: [
          { key: "dni", label: "DNI", class: "text-center" },
          { key: "apn", label: "APELLIDOS Y NOMBRES" },
      ],
      filtro: '',
      form_validation_variables: {
          minsize: 8,
          maxsize: 8,
          only_numbers: true,
          only_letters: false,
          info_message:'',
      },
      show: true,
      alerta: false,
      alerta_mensaje: ''
    };
  },  
  computed: {
    validation() {
      var regLetters = /^[a-zA-ZñÑ\s]*$/;
      if(this.filtro.length=='')return null;
      else if(this.form_validation_variables.only_numbers && isNaN(Number(this.filtro))){
          this.form_validation_variables.info_message="Debe ingresar sólo números."
      }
      else if(this.form_validation_variables.only_letters && !regLetters.test(this.filtro)){
          this.form_validation_variables.info_message="Debe ingresar sólo letras."
      }
      else if(!(this.filtro.length >= this.form_validation_variables.minsize && this.filtro.length <= this.form_validation_variables.maxsize)){
          if(this.form_validation_variables.minsize == this.form_validation_variables.maxsize){
              this.form_validation_variables.info_message = "Debe tener exactamente " + this.form_validation_variables.minsize + " dígitos."
          }else
              this.form_validation_variables.info_message = "Debe ingresar al menos " + this.form_validation_variables.minsize + " caracteres."
      }else{
          this.form_validation_variables.info_message = ''
          return true
      }
      return false
    }
  },
  watch: {
    opcion_busqueda: function (val){
      switch (val) {
        case 'DNI':
            this.filtro = ''
            this.form_validation_variables.minsize=8;
            this.form_validation_variables.maxsize=8;
            this.form_validation_variables.only_numbers=true;
            this.form_validation_variables.only_letters=false;
            break;
        
        case 'APN':
            this.filtro = ''
            this.form_validation_variables.minsize=2;
            this.form_validation_variables.maxsize=99;
            this.form_validation_variables.only_letters=true;
            this.form_validation_variables.only_numbers=false;
            break;
        default:
            break;
      }
    },
    filtro: function (val) {
      if (this.opcion_busqueda == 'DNI' && val.length == 8) {
        this.buscarTicket()
      }
    } 
  },
  methods: {
    async buscarTicket() {
      this.mostrar_resultado = true

      try {
        const response = await axios.get(`${this.app_url}/buscarTicket`, {
                            params: {
                              'filtro': this.filtro,
                              'opcion_busqueda': this.opcion_busqueda
                            }
                          })
        
        if (this.opcion_busqueda == 'DNI') {
          this.formData = response.data 
        }
        else {
          this.items = response.data
        }
        this.limpiar()   
      } catch (error) {
        this.alerta = true;
        this.alerta_mensaje = "No se encontro ningún resultado";    
        this.formData = null    
      }        
    },
    async mostrarData(data) {
      this.mostrar_resultado = true

      try {
        const response = await axios.get(`${this.app_url}/buscarTicket`, {
                            params: {
                              'filtro': data.dni,
                              'opcion_busqueda': 'DNI'
                            }
                          })
        this.opcion_busqueda = 'DNI'
        this.items = []
        this.formData = response.data
        this.limpiar()   
      } catch (error) {
        this.alerta = true;
        this.alerta_mensaje = "No se encontro ningún resultado";    
        this.formData = null    
      }        
    },
    change_select(val){
      this.formData = null;
    },
    registrar() {
      this.$bvModal
        .msgBoxConfirm(`¿Esta seguro de querer registrar el ticket para: ${this.formData.apn}?`, {
          title: "Registrar ticket",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          cancelVariant: "danger",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            console.log(this.formData);
            axios.post(`${this.app_url}/registrarTicket/${this.formData.id}`, this.formData)
              .then((response) => {
                if (!response.data.error) {
                  this.mostrar_resultado = false
                  this.formData = null
                  this.opcion_busqueda = 'DNI'
                  this.$bvToast.toast(
                      response.data.successMessage,
                      {
                          title: "Registro de ticket",
                          variant: "success",
                          toaster: "b-toaster-bottom-right",
                          solid: true
                      }
                  );
                }
                else {
                  this.$bvToast.toast(
                      response.data.errorMessage,
                      {
                          title: "Registro de ticket",
                          variant: "danger",
                          toaster: "b-toaster-bottom-right",
                          solid: true
                      }
                  );
                }
              })
              .catch(function (error) {
                console.log(error);
              });
          }
        });
    },
    limpiar() {
      this.alerta = false
      this.alerta_mensaje = ''  
      this.filtro = ''
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
    .text-size {
      font-size: 1.2em;
    }
    .text-size2 {
      font-size: 1.5em;
    }
    .text-big {
      font-size: 2em;
    }
    .txt_center {
      text-align: center;
      font-size: 1.2em;
    }
    .font-size{
      font-size: 1.2em;
    }

</style>
