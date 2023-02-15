<template>
    <header id="page-topbar">
        <b-navbar toggleable="lg" type="light" variant="light">
            <b-navbar-brand href="#">
                POSGRADO
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
                <inertia-link :href="route('roles.iniciar')" v-if="$permissions.can([{ name: 'Listar Roles' }])">
                  <b-nav-item :href="route('roles.iniciar')" :class="{ active: path == 'roles' }">
                    Roles
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('usuarios.iniciar')" v-if="$permissions.can([{ name: 'Listar Usuarios' }])">
                  <b-nav-item :href="route('usuarios.iniciar')" :class="{ active: path == 'usuarios' }">
                    Usuarios
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('tickets.formulario')" v-if="$permissions.can([{ name: 'Registrar Uniformes' }])">
                  <b-nav-item :href="route('tickets.formulario')" :class="{ active: path == 'tickets' }">
                    Tickets
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('uniformes.listarMisTickets')" v-if="$permissions.can([{ name: 'Ver-Propios Tickets' }])">
                  <b-nav-item :href="route('uniformes.listarMisTickets')" :class="{ active: path == 'tickets-atendidos' }">
                    Tickets atendidos
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('uniformes.iniciar')" v-if="$permissions.can([{ name: 'Ver-Reporte Tickets' }])">
                  <b-nav-item :href="route('uniformes.iniciar')" :class="{ active: path == 'tickets-reporte' }">
                    Reporte
                  </b-nav-item>
                </inertia-link>
                <!-- <inertia-link :href="route('mis-denuncias.listar')" v-if="$permissions.can([{ name: 'Ver-Propias Denuncias' }])">
                  <b-nav-item :href="route('mis-denuncias.listar')" :class="{ active: path == 'mis-denuncias' }">
                    Mis denuncias
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('recibidas')" v-if="$permissions.can([{ name: 'Ver-Recibidas Denuncias' }])">
                  <b-nav-item :href="route('recibidas')" :class="{ active: path == 'recibidas' }">
                    Recibidas
                  </b-nav-item>
                </inertia-link>
                <inertia-link :href="route('atendidas')" v-if="$permissions.can([{ name: 'Ver-Atendidas Denuncias' }])">
                  <b-nav-item :href="route('atendidas')" :class="{ active: path == 'atendidas' }">
                    Atendidas
                  </b-nav-item>
                </inertia-link> -->
            </b-navbar-nav>

            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">
                <b-nav-item-dropdown right>
                    <!-- Using 'button-content' slot -->
                    <template #button-content>
                        <img
                            class="rounded-circle header-profile-user"
                            :src="$page.props.user.profile_photo_path"
                            alt="Header Avatar"
                        />
                        <span
                            class="d-none d-xl-inline-block ml-1 font-weight-medium font-size-15"
                            >{{ $page.props.user.email }}</span
                        >
                        <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                    </template>

                    <b-dropdown-item @click="logout">
                        <i class="uil uil-sign-out-alt font-size-18 align-middle mr-1 text-muted"></i>
                        Cerrar sesión
                    </b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </header>
</template>
<script>
export default {
  data() {
    return {
      app_url: this.$root.app_url,
      menuUsuario: false,
      menuNotificaciones: false,
    };
  },
  computed: {
    path() {      
      let url = window.location.pathname.split("/");      
      return url[url.length - 1];
    },
  },
  methods: {
    mostrarMenuUsuario() {
      this.menuUsuario = !this.menuUsuario;
    },
    mostrarNotificaciones() {
      this.menuNotificaciones = !this.menuNotificaciones;
    },
    logout() {
      this.$inertia.post(route("logout"));
    },
    mostrarOcultarBarraLateral() {
      let sitebody = document.body;
      let show_sidebar = !this.$store.getters.getShowSideBar;

      if (show_sidebar) {
        sitebody.classList.remove("vertical-collpsed");
      } else {
        sitebody.classList.add("vertical-collpsed");
      }

      this.$store.dispatch("setShowSideBar", show_sidebar);
    },
  },
};
</script>
<style scoped>
  .active {
    background-color: rgba(92, 28, 36, 0.9);
    
    border-radius: 10px;
  }
  .active a {
    color: white !important;
    /*pointer-events: auto;*/
  }
  a {
    color: blue !important;
  }
</style>
