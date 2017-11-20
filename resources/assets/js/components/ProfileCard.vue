<script>
    export default{
        props: ['logout', 'user'],
        methods: {
            showProfile: function () {
                this.$emit('show-profile');
            }
        }
    }
</script>
<template>
    <v-menu offset-y origin="center center" :closeOnContentClick="false">
        <v-btn icon dark slot="activator">
            <v-icon>account_circle</v-icon>
        </v-btn>
        <v-container fluid="fluid" class="profile-card pa-3">
            <div class="loading" v-if="!user">
                <v-progress-circular indeterminate class="primary--text" />
            </div>
            <v-row v-else class="user-card">
                <v-col xs3 style="margin-left: 0">
                    <v-avatar>
                        <img :src="user.avatar" :alt="user.fullName()">
                    </v-avatar>
                </v-col>
                <v-col xs7 class="user-card-info">
                    <v-row><span class="title">{{ user.fullName() }}</span></v-row>
                    <v-row><span class="grey--text text--darken-2">{{ user.email }}</span></v-row>
                    <v-row>
                        <ul class="user-card-links">
                            <li>
                                <v-btn small primary dark
                                       @click.native="showProfile">{{ $t('profile.profile_button') }}</v-btn>
                            </li>
                            <li>
                                <form @submit.stop.prevent="logout">
                                    <v-btn small primary dark type="submit">{{ $t('auth.logout') }}</v-btn>
                                </form>
                            </li>
                        </ul>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-menu>
</template>
<style lang="scss">
    .profile-card {
        min-width: 330px;
        min-height: 100px;
    }

    .user-card {
        &.row {
             margin: 0;
         }
        .avatar {
            width: 56px;
            height: 56px;
            img {
                width: 100%;
                height: 100%;
            }
        }
    }

    .user-card-links {
        list-style: none;
        padding: 0;
        li {
            display: inline;
        }
    }

    .title {
        font-weight: bold;
        margin-bottom: 1px;
        text-transform: capitalize;
        font-size: 16px;
    }


    .loading {
        margin: 10%;
        text-align: center;
    }
</style>
