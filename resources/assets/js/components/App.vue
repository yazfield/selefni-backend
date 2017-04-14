<template>
    <div :class="{'clean-template': !headerTemplate, 'header-template': headerTemplate}" style="flex:1">
        <md-progress v-if="pending" md-indeterminate></md-progress>
        <Toolbar v-if="headerTemplate"></Toolbar>
        <div class="page-content">
            <SideBar v-if="headerTemplate"></SideBar>
            <div class="main-content">
                <md-layout md-align="center">
                    <router-view></router-view>
                </md-layout>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .main-content {
        margin: 0 15%;
    }
</style>

<script>
    import {mapGetters} from 'vuex';
import {templates} from '../constants';
    import Toolbar from './Toolbar';
    import SideBar from './Items/SideBar';

export default {
    components: {
        Toolbar,
        SideBar
    },
    computed: {
        headerTemplate: function() {
            return this.$store.state.template === templates.dashboard;
        },
        pending: function () {
            return this.$store.getters.isLoggingIn;
        }
    },
}
</script>