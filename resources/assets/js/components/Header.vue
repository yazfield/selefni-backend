<template>
    <md-toolbar class="md-large header">
        <div class="md-toolbar-container">
            <md-button class="md-icon-button">
                <md-icon>menu</md-icon>
            </md-button>

            <h1 class="md-headline">Selefni app</h1>

            <form action="" class="search" style="flex: 4">
                <md-input-container md-inline >
                    <md-button class="md-icon-button">
                        <md-icon>search</md-icon>
                    </md-button>
                    <label>Search</label>
                    <md-input></md-input>
                </md-input-container>
            </form>
            <span style="flex: 2"></span>

            <md-button class="md-icon-button">
                <md-icon>filter_list</md-icon>
            </md-button>
        </div>

        <div class="md-toolbar-container">
            <nav class="main-navigation md-layout md-align-center">
                <ul v-if="isLoggedIn" class="list-inline">
                    <router-link :to="{ name: 'dashboard' }" class="md-button">Items</router-link>
                    <router-link :to="{ name: 'logout' }" class="md-button">Logout</router-link>
                </ul>
                <ul v-if="!isLoggedIn" class="list-inline">
                    <router-link :to="{ name: 'home' }" class="md-button">Home</router-link>
                    <router-link :to="{ name: 'login' }" class="md-button">Login</router-link>
                </ul>
            </nav>
        </div>
    </md-toolbar>
</template>

<script>
export default {
    computed: {
        isLoggedIn: function() {
            return this.$store.getters.isLoggedIn;
        }
    }
}
</script>