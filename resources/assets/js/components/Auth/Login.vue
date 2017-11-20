<script>
    import {templates} from '../../constants';
    import {mapGetters} from 'vuex';
    export default {
        data() {
            return {
                form: {
                    credentials: {
                        username: '',
                        password: ''
                    },
                    error: null,
                    rules: {
                        email: [],
                        password: []
                    }
                },
            }
        },
        methods: {
            login() {
                this.$store
                    .dispatch('login', this.form.credentials)
                    .then(() => {
                        this.error = null;
                        this.$router.replace({name: 'dashboard'});
                    }).catch(error => {
                    console.log(error);
                    if (error && error.response) {
                        this.form.error = error.response.data;
                    } else {
                        this.form.error = {
                            // TODO: use multi lang
                            message: 'Your email or password was incorrect'
                        };
                    }
                })
            }
        },
        computed: {
            ...mapGetters(['isLoggingIn'])
        },
        mounted() {
            this.$store.dispatch('changeTemplate', templates.clean);
        }
    }
</script>

<template>
    <v-container style="flex: 1; margin: 0 30%">
        <v-row>
            <h1 class="logo"><img src="images/logo.png" alt="Reja3li"/></h1>
        </v-row>
        <v-row>
            <form @submit.stop.prevent="login" style="flex: 1">
                <v-text-field name="username" label="Email" :rules="form.rules.email"
                              v-model="form.credentials.username"></v-text-field>
                <v-text-field name="password" label="Enter your password" hint="At least 8 characters"
                        min="8" append-icon="visibility_off" type="password"
                        v-model="form.credentials.password" :rules="form.rules.password"></v-text-field>
                <v-row>
                    <v-btn :disabled="isLoggingIn" style="flex:1" type="submit" light primary>Login
                    </v-btn>
                </v-row>
            </form>
        </v-row>
        <v-row>
            <p>
                <router-link :to="{ name: 'forgotPassword' }">Forgot your password?</router-link>
            </p>
        </v-row>
        <v-row>
            <router-link :to="{ name: 'home' }">Back to front page</router-link>
        </v-row>
    </v-container>
</template>