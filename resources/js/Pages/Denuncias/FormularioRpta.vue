<template>
  <app-layout>
    <div>
      <b-container>
          <b-row>
            <b-col>
                <b-card title="Datos personales">
                    <b-card-body>
                      <table>
                        <tr>
                          <th class="text-right">Tipo documento:</th>
                          <td>{{ administrado.tipo_documento }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Nro. documento:</th>
                          <td>{{ administrado.nro_documento }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Administrado:</th>
                          <td>{{  administrado.apellidos }}, {{  administrado.nombres }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Teléfono:</th>
                          <td>{{  administrado.telefono }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Email personal:</th>
                          <td>{{  administrado.email_personal }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Domicilio:</th>
                          <td>{{  administrado.domicilio }}</td>
                        </tr>
                      </table>
                    </b-card-body>
                </b-card>
            </b-col>
            <b-col>
              <b-card title="Denuncia">
                  <b-card-body>
                      <table>
                        <tr>
                          <th class="text-right">Código:</th>
                          <td>{{ denuncia.codigo }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Descripción:</th>
                          <td>{{ denuncia.descripcion }}</td>
                        </tr>
                        <tr>
                          <th class="text-right">Estado:</th>
                          <td><b-badge variant="warning">{{  denuncia.estado }}</b-badge></td>
                        </tr>
                        <tr v-if="denuncia.url_archivo">
                          <th class="text-right">Archivo Adjunto:</th>
                          <td>
                              <a :href="`${this.app_url}/archivo?url_archivo=${denuncia.url_archivo}`" target="_blank">
                                <b-badge variant="secondary">{{  denuncia.url_archivo }}</b-badge>
                              </a>
                          </td>
                        </tr>
                      </table>
                  </b-card-body>
              </b-card>
            </b-col>
          </b-row>
      </b-container>
      <b-form>
        <b-container>
          <div class="mt-3 mb-3">
            <span class="font-weight-bold"><h2>Respuesta</h2></span>
          </div>
          <b-row class="justify-content-md-center">
            <b-col>
              <label
                >Escriba aqui la respuesta de la denuncia, se le pide ser lo
                más especifico posible.</label
              >
              <b-form-textarea
                no-resize
                rows="8"
                v-model="denuncia.respuesta"
                placeholder="Ingrese aqui la respuesta a la denuncia."
                required
              ></b-form-textarea>
              <div v-if="$page.props.errors.respuesta" class="text-danger">
                {{ $page.props.errors.respuesta[0] }}
              </div></b-col
            >
          </b-row>
        </b-container>
        <b-container fluid class="mt-3">
          <b-row class="justify-content-md-center">
            <b-col md="auto">
              <b-button @click="responder" variant="success"
                >Responder</b-button
              >
              <b-button @click="limpiar" variant="danger"
                >Limpiar</b-button
              >
            </b-col>
          </b-row>
        </b-container>
      </b-form>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "denuncias.formularioRpta",
  props: ["administrado", "denuncia"],
  components: {
    AppLayout,
  },
  data() {
    return {
      app_url: this.$root.app_url,
      tipos_documentos: [
        { text: "Elija un tipo de documento", value: null },
        { text: "DNI", value: "DNI" },
        { text: "PASAPORTE", value: "PASAPORTE" },
        { text: "CARNET DE EXTRANJENRIA", value: "CARNET_EXTRANJERIA" },
      ],
      show: true,
    };
  },
  methods: {
    responder() {
      this.$bvModal
        .msgBoxConfirm("¿Esta seguro de querer responder esta denuncia?", {
          title: "Responder denuncia",
          okVariant: "success",
          okTitle: "SI",
          cancelTitle: "NO",
          cancelVariant: "danger",
          centered: true,
        })
        .then(async (value) => {
          if (value) {
            this.$inertia.post(route("denuncias.actualizar", [this.denuncia.id]), this.denuncia);
          }
        });
    },
    limpiar() {
      this.denuncia.respuesta = "";
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
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}
</style>
<!--<template>
  <app-layout
    ><nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item ml-auto">
          <inertia-link :href="route('dashboard')">Inicio</inertia-link>
        </li>
        <li class="breadcrumb-item active">Formulario</li>
      </ol>
    </nav>
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-center mb-1">
          <fieldset class="col-12 col-md-10 px-3">
            <legend>Datos Personales:</legend>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form inline>
                  <b-form-select
                    size="sm"
                    v-model="formulario.tipo_documento"
                    :options="tipos_documentos"
                  >
                    <template v-slot:first>
                      <option :value="null" disabled>
                        Tipo de documento ..
                      </option>
                    </template>
                  </b-form-select>
                  &nbsp;&nbsp;
                  <b-form-input
                    size="sm"
                    v-model="formulario.nro_documento"
                    placeholder="Número de documento"
                  ></b-form-input>
                </b-form>
              </div>
            </div>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form inline>
                  <b-form-input
                    size="sm"
                    v-model="formulario.nombres"
                    placeholder="Nombres"
                  ></b-form-input>
                  &nbsp;&nbsp;
                  <b-form-input
                    size="sm"
                    v-model="formulario.apellidos"
                    placeholder="Apellidos"
                  ></b-form-input>
                </b-form>
              </div>
            </div>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form-input
                  size="sm"
                  v-model="formulario.telefono"
                  placeholder="Teléfono"
                ></b-form-input>
              </div>
            </div>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form-input
                  size="sm"
                  v-model="formulario.email"
                  placeholder="Email"
                ></b-form-input>
              </div>
            </div>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form-input
                  size="sm"
                  v-model="formulario.domicilio"
                  placeholder="Domicilio"
                ></b-form-input>
              </div>
            </div>
          </fieldset>

          <fieldset class="col-12 col-md-10 px-3">
            <legend>Detalles de la Denuncia:</legend>
            <div class="row justify-content-center mb-1">
              <div class="col col-lg-5">
                <b-form inline>
                  <b-form-textarea
                    v-model="formulario.descripcion"
                    id="textarea-no-resize"
                    placeholder="Fixed height textarea"
                    rows="3"
                    no-resize
                  ></b-form-textarea>
                </b-form>
              </div>
            </div>
          </fieldset>
        </div>
        <br />
        <div class="row justify-content-center mb-1">
          <b-button variant="outline-success" @click="enviar_denuncia()">
            Enviar denuncia <b-icon icon="search"></b-icon>
          </b-button>
          &nbsp;
          <b-button variant="outline-primary" @click="limpiar()">
            Limpiar <b-icon icon="arrow-clockwise"></b-icon>
          </b-button>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  name: "denuncias.formulario",
  components: {
    AppLayout,
  },
  data() {
    return {
      formulario: {
        tipo_documento: null,
        nro_documento: "",
        nombres: "",
        apellidos: "",
        telefono: "",
        email: "",
        domicilio: "",
        descripcion: "",
      },
      tipos_documentos: [
        { value: "DNI", text: "DNI" },
        { value: "PASAPORTE", text: "PASAPORTE" },
        { value: "CARNET_EXTRANJERIA", text: "CARNET DE EXTRANJERIA" },
      ],
      show: true,
    };
  },
  methods: {
    enviar_denuncia() {
      this.$inertia.get(route("denuncias.registrar"), this.formulario);
    },
    limpiar() {
      this.mostrar_boletas = false;
      this.filtrado = false;
      this.alerta = false;
      this.fecha_inicio = "";
      this.fecha_fin = "";
      this.alerta_mensaje = "";
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

.breadcrumb li a {
  color: blue;
}

.breadcrumb {
  margin-bottom: 0;
  background-color: white;
}
</style>-->
