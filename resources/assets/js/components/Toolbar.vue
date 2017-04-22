<template>
    <md-toolbar class="md-large header md-whiteframe md-whiteframe-3dp">
        <div class="md-toolbar-container">
            <md-button class="md-icon-button">
                <md-icon>menu</md-icon>
            </md-button>

            <h1 class="md-headline">
                <router-link :to="{ name: 'home' }">Selefni app</router-link>
            </h1>

            <form v-if="isLoggedIn" action="" class="search" style="flex: 4">
                <md-input-container md-inline>
                    <md-button class="md-icon-button">
                        <md-icon>search</md-icon>
                    </md-button>
                    <label>Search</label>
                    <md-input></md-input>
                </md-input-container>
            </form>
            <span style="flex: 2"></span>

            <md-button class="md-icon-button" md-menu-trigger>
                <md-icon>replay</md-icon>
            </md-button>
            <Notifications v-if="isLoggedIn" :notifications="notifications"
                           :loading="loadingNotifications"></Notifications>
            <ProfileCard v-if="isLoggedIn" :logout="logout" :user="user"></ProfileCard>
        </div>

        <div class="md-toolbar-container">
            <nav class="main-navigation md-layout md-align-center">
                <ul v-if="isLoggedIn" class="list-inline">
                    <li>
                        <router-link :to="{ name: 'dashboard' }" class="md-button">Items</router-link>
                    </li>
                </ul>
                <ul v-if="!isLoggedIn" class="list-inline">
                    <router-link :to="{ name: 'home' }" class="md-button">Home</router-link>
                    <router-link :to="{ name: 'login' }" class="md-button">Login</router-link>
                </ul>
            </nav>
        </div>
    </md-toolbar>
</template>

<style lang="scss">
    .md-theme-default .md-headline a:not(.md-button) {
        color: white;
        text-decoration: none;

        &:hover {
            color: white;
            text-decoration: none;
        }
    }
    .md-toolbar.md-whiteframe {
        position: fixed;
        width: 100%;
        z-index: 2;
    }
</style>

<script>
    import {mapGetters} from 'vuex';
    import ProfileCard from './ProfileCard';
    import Notifications from './Notifications';
    export default {
        data() {
            return {
                loadingNotifications: false,
            }
        },
        computed: {
            ...mapGetters(['isLoggedIn', 'user', 'notifications'])
        },
        methods: {
            logout(){
                this.$store.dispatch('logout').then(() => {
                    this.$router.replace({name: 'login'});
                })
            }
        },
        components: {
            ProfileCard,
            Notifications
        },
    }
</script>