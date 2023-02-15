<template>
  <app-layout>
    <div>
      <b-card
        overlay
        class="text-center"
        img-src="https://picsum.photos/900/250/?image=3"
        img-alt="Card Image"
      >
        <b-card-title style="color: white; font-size: 50px"
          >Denuncias App</b-card-title
        >
        <b-card-text style="color: white; font-size: 30px">
          Realice su denuncia y reciba su respuesta de forma inmediata
        </b-card-text>
      </b-card>
    </div>
    <b-container>
      <b-row>
        <b-card
          border-variant="light"
          header="Registrar denuncia"
          class="text-center"
          style="width: 100%"
        >
          <b-card-text
            >En la seccion de formulario deberia registrar sus datos personales
            como los detalles de la denuncia que se desea registrar</b-card-text
          >
        </b-card>
      </b-row>

      <br />

      <b-row>

        <b-card
          border-variant="light"
          header="Seguimiento de denuncia"
          class="text-center"
          style="width: 100%"
        >
          <b-card-text
            >En la seccion de mis denuncias podra hacerle seguimiento a todas sus denuncias realizadas y en que estado se encuentren, si estan recibidas o atendidas</b-card-text
          >
        </b-card>
      </b-row>
    </b-container>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
};
</script>
