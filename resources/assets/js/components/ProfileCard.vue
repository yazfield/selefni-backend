<script>
    export default{
        props: ['user', 'logout']
    }
</script>
<template>
    <md-menu md-size="6" md-align-trigger md-direction="bottom left" ref="menu">
        <md-button class="md-icon-button" md-menu-trigger>
            <md-icon>account_circle</md-icon>
        </md-button>

        <md-menu-content>
            <md-layout class="loading" v-if="!user" md-align="center">
                <md-spinner md-indeterminate></md-spinner>
            </md-layout>
            <md-layout v-else-if="user" class="user-card" :md-gutter="20">
                <md-layout style="flex:1">
                    <md-avatar class="md-large">
                        <img :src="user.avatar" :alt="user.fullName()">
                    </md-avatar>
                </md-layout>
                <md-layout style="flex:3; flex-flow: column wrap;" class="user-card-info">
                    <span class="title">{{ user.fullName() }}</span>
                    <span class="sub-title">{{ user.email }}</span>
                    <ul class="md-list md-dense list-inline user-card-links">
                        <li>
                            <md-button class="md-raised md-primary md-dense">Profile</md-button>
                        </li>
                        <li>
                            <form @submit.stop.prevent="logout">
                                <md-button class="md-raised md-primary md-dense" type="submit">Logout</md-button>
                            </form>
                        </li>
                    </ul>
                </md-layout>
            </md-layout>
        </md-menu-content>
    </md-menu>
</template>
<style lang="scss">
    @import "../../sass/_variables";

    .title {
        font-weight: bold;
        margin-bottom: 1px;
        text-transform: capitalize;
    }

    .sub-title {
        color: $ grey-600;
    }

    .loading {
        margin: 10%;
    }
</style>
