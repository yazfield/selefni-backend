<script>
    import {mapGetters} from 'vuex';
    import {templates} from '../constants';
    import Toolbar from './Toolbar';
    import SideBar from './Items/SideBar';
    import Profile from './Profile';
    export default {
        data() {
            return {
                showDialog: false,
            }
        },
        computed: {
            appClasses() {
                return {
                    'clean-template': !this.headerTemplate,
                    'header-template': this.headerTemplate
                }
            },
            headerTemplate() {
                return this.$store.state.template === templates.dashboard;
            },
            pending() {
                return this.$store.getters.isLoggingIn;
            }
        },
        components: {
            'app-toolbar': Toolbar,
            'app-sidebar': SideBar,
            'app-profile': Profile
        },
        methods: {
            showProfile() {
                this.showDialog = true;
            }
        }
    }
</script>
<template>
    <v-app :class="appClasses" style="flex:1">
        <v-progress-linear class="ma-0" height="3" :indeterminate="true"></v-progress-linear>
        <app-toolbar @show-profile="showProfile" v-if="headerTemplate"></app-toolbar>
        <div class="page-content">
            <!--<side-bar v-if="headerTemplate"></side-bar>-->
            <div class="main-content">
                <v-container class="text-md-center" fluid="fluid">
                    <router-view></router-view>
                </v-container>
            </div>
        </div>
        <app-profile v-model="showDialog"></app-profile>
    </v-app>
</template>

<style lang="scss" scoped>
    .main-content {
        margin: 0 15%;
        margin-top: 150px;
    }
</style>