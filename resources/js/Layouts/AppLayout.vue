<template>
    <div>
        <div id="layout-wrapper">
            <top-bar />
            <!--<side-bar />-->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
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
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import JetApplicationMark from "@/Jetstream/ApplicationMark";
import JetBanner from "@/Jetstream/Banner";
import JetDropdown from "@/Jetstream/Dropdown";
import JetDropdownLink from "@/Jetstream/DropdownLink";
import JetNavLink from "@/Jetstream/NavLink";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";

import TopBar from "./TopBar";
import SideBar from "./SideBar";

export default {
    components: {
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
        TopBar,
        SideBar
    },

    data() {
        return {
            showingNavigationDropdown: false
        };
    },

    methods: {
        switchToTeam(team) {
            this.$inertia.put(
                route("current-team.update"),
                {
                    team_id: team.id
                },
                {
                    preserveState: false
                }
            );
        }
    }
};
</script>
