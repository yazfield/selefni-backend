<template>
    <div style="flex:1">
        <Header v-if="headerTemplate"></Header>
        <div class="page-content">
            <SideBar v-if="headerTemplate"></SideBar>
            <div class="main-content" style="margin: 0 20%">
                <md-layout md-align="center">
                    <router-view></router-view>
                </md-layout>
            </div>
        </div>
    </div>
</template>

<script>
import {templates} from '../constants';

export default {
    computed: {
        headerTemplate: function() {
            return this.$store.state.template == templates.dashboard;
        }
    }
}
</script>