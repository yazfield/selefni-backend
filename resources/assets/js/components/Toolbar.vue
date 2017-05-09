<template>
    <v-toolbar fixed>
        <v-toolbar-side-icon></v-toolbar-side-icon>
        <v-toolbar-title class="hidden-sm-and-down">
            <router-link class="white--text headline" :to="{ name: 'home' }"
                         style="text-decoration: none;">{{ $t('app.name') }}</router-link>
        </v-toolbar-title>
        <v-text-field prepend-icon="search" label="Search..." hide-details single-line dark></v-text-field>

        <v-spacer></v-spacer>

        <v-toolbar-items>
            <v-btn icon dark v-if="isLoggedIn">
                <v-icon>replay</v-icon>
            </v-btn>
            <app-notifications v-if="isLoggedIn"></app-notifications>
            <profile-card @show-profile="$emit('show-profile')" v-if="isLoggedIn" :logout="logout"
                          :user="user"></profile-card>
        </v-toolbar-items>

    </v-toolbar>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ProfileCard from './ProfileCard';
    import Notifications from './Notifications/Notifications';

    export default {
        computed: {
            ...mapGetters(['isLoggedIn', 'user'])
        },
        components: {
            ProfileCard,
            'app-notifications': Notifications
        },
        methods: {
            logout(){
                this.$store.dispatch('logout').then(() => {
                    this.$router.replace({name: 'login'});
                })
            }
        }
    }
</script>