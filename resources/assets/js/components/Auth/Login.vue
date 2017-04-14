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
                    error: null
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
    <div style="flex: 1; margin: 0 30%">
        <md-layout md-align="center">
            <h1 class="logo"><img src="images/logo.png" alt="Reja3li"/></h1>
        </md-layout>
        <md-layout md-align="center">
            <form @submit.stop.prevent="login" style="flex: 1">
                <md-input-container :class="{ 'md-input-invalid': form.error }">
                    <label for="username">Email</label>
                    <md-input type="email" id="username" name="username"
                              v-model="form.credentials.username" required></md-input>
                    <span class="md-error" v-if="form.error">{{ form.error.message }}</span>
                </md-input-container>
                <md-input-container md-has-password :class="{ 'md-input-invalid': form.error }">
                    <label for="password">Password</label>
                    <md-input type="password" id="password" name="password"
                              v-model="form.credentials.password" required></md-input>
                    <span class="md-error" v-if="form.error">{{ form.error.message }}</span>
                </md-input-container>
                <md-layout>
                    <md-button :disabled="isLoggingIn" style="flex:1" type="submit" class="md-raised md-primary">Login
                    </md-button>
                </md-layout>
            </form>
        </md-layout>
        <md-layout md-align="center">
            <p>
                <router-link :to="{ name: 'forgotPassword' }">Forgot your password?</router-link>
            </p>
        </md-layout>
        <md-layout md-align="center">
            <router-link :to="{ name: 'home' }">Back to front page</router-link>
        </md-layout>
    </div>
</template>