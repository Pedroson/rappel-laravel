<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-group" v-bind:class="{ 'has-error': error && serverErrors.name && !name  }">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" name="name" v-model="name" v-validate="'required|email'">
                <span class="help-block" v-if="error && serverErrors.name && !name">{{ tidyError(serverErrors.name) }}</span>
                <span class="help-block" v-if="errors.has('name')">{{ errors.first('name') }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && serverErrors.email && !email }">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email">
                <span class="help-block" v-if="error && serverErrors.email && !email">{{ tidyError(serverErrors.email) }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': error && serverErrors.password && !password }">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" v-model="password">
                <span class="help-block" v-if="error && serverErrors.password && !password">{{ tidyError(serverErrors.password) }}</span>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name: '',
                email: '',
                password: '',
                error: false,
                serverErrors: {},
                success: false
            };
        },
        methods: {
            register(){
                var app = this
                this.$auth.register({
                    data: {
                        name: app.name,
                        email: app.email,
                        password: app.password
                    },
                    success: function () {
                        app.success = true
                    },
                    error: function (resp) {
                        app.error = true;
                        app.serverErrors = resp.response.data.errors;
                    },
                    redirect: null
                });
            },
            tidyError(error) {
                return error[0];
            }
        }
    }
</script>