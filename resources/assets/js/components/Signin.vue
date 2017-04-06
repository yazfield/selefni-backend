<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <form method="post" :submit="signin">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                    v-model="email" required />
            </div>
            <div class="form-group">
                <label for="password">password</label>
                <input type="password" id="password" name="password" class="form-control" 
                    v-model="password" required />
            </div>
            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div>
</template>

<script>
import auth from '../auth';
export default {
    data() {
        return {
            email: null,
            password: null,
            error: false,
        }
    }, 
    methods: {
        signin(event) {
            event.preventDefault();
            auth.signin(this, this.email, this.password);
        }
    }
}
</script>