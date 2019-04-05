<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>
        <form id="register" class="rappel-corner" autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
            <div class="form-header">
                <p>Register</p>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (error && serverErrors.name && !name) ||  errors.has('name') }">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control" name="name" v-model="name" v-validate="'required|alpha'">
                <span class="help-block" v-if="error && serverErrors.name && !name">{{ tidyError(serverErrors.name) }}</span>
                <span class="help-block" v-if="errors.has('name')">{{ errors.first('name') }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (error && serverErrors.email && !email) || errors.has('email') }">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" name="email" v-model="email" v-validate="'required|email'">
                <span class="help-block" v-if="error && serverErrors.email && !email">{{ tidyError(serverErrors.email) }}</span>
                <span class="help-block" v-if="errors.has('email')">{{ errors.first('email') }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (error && serverErrors.password && !password) || errors.has('password') }">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control" name="password" v-model="password" v-validate="'required|min:6|max:10'" ref="password">
                <span class="help-block" v-if="error && serverErrors.password && !password">{{ tidyError(serverErrors.password) }}</span>
                <span class="help-block" v-if="errors.has('password')">{{ errors.first('password') }}</span>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (error && serverErrors.password_confirm && !password_confirm) || errors.has('password_confirm') }">
                <label for="password_confirm">Password Confirm</label>
                <input type="password" id="password_confirm" class="form-control" name="password_confirm" v-model="password_confirm" v-validate="'required|min:6|max:10|confirmed:password'" data-vv-as="password">
                <span class="help-block" v-if="error && serverErrors.password_confirm && !password_confirm">{{ tidyError(serverErrors.password_confirm) }}</span>
                <span class="help-block" v-if="errors.has('password_confirm')">{{ errors.first('password_confirm') }}</span>
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
                password_confirm: '',
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
                        password: app.password,
                        password_confirm: app.password_confirm
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

<style lang="scss">
    form#register {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: #fff;

        .form-header {
            padding: 0px 10px;
            p {
                font-size: 1.5rem;
            }
        }

        .form-group {
            position: relative;
            padding: 0px 10px;
            &.has-error {
                input {
                    border-bottom-color: #8b0000;
                }
            }
            input {
                border-width: 0px;
                border-bottom: 1px solid #1b1e21;
                border-radius: 0px;
            }
        }

    }
</style>