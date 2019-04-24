<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
        <div class="form-container">
            <h1>Login</h1>
            <form id="login" class="rappel-corner" autocomplete="off" @submit.prevent="login" method="post">
                <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'email') || isActive && email, 'has-error': (error && serverErrors.name && !email) ||  errors.has('email') }">
                    <label for="email">E-mail</label>
                    <input v-on:focus="isFocused('email', $event)" v-on:blur="isFocused('email', $event)" type="email" id="email" name="email" class="form-control" v-model="email" v-validate="'required|email'">
                    <span class="help-block" v-if="error && serverErrors.email && !email">{{ tidyError(serverErrors.email) }}</span>
                    <span class="help-block" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>
                <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'password') || isActive && password, 'has-error': (error && serverErrors.name && !passsword) ||  errors.has('password') }">
                    <label for="password">Password</label>
                    <input v-on:focus="isFocused('password', $event)" v-on:blur="isFocused('password', $event)" type="password" id="password" name="password" class="form-control" v-model="password" v-validate="'required'">
                    <span class="help-block" v-if="error && serverErrors.password && !password">{{ tidyError(serverErrors.password) }}</span>
                    <span class="help-block" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>
                <div class="form-group-button">
                    <button type="submit" class="btn btn-default btn-full">Sign in</button>
                </div>
            </form>
            <router-link :to="{ name: 'forgotten-password' }">forgotten password?</router-link>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                email: null,
                password: null,
                error: false,
                serverErrors: {},
                success: false,
                isActive: false,
                index: false,
            }
        },
        methods: {
            login(){
                var app = this
                this.$auth.login({
                    params: {
                        email: app.email,
                        password: app.password
                    },
                    success: function () {},
                    error: function () {
                        app.error = true;
                        app.serverErrors = resp.response.data.errors;
                    },
                    rememberMe: true,
                    redirect: '/dashboard',
                    fetchUser: true,
                });
            },
            tidyError(error) {
                return error[0];
            },
            isFocused($name, $event) {
                this.index = $name;
                this.isActive = $event.type === 'focus' || $event.type === 'blur' && this[$name];
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/app.scss';

    .form-container {
        @include absoluteCenter;
        width: 400px;

        h1 {
            color: #fff;
        }


        form#login {
            background: #fff;
            padding: 1.75rem 1rem 0 1rem;
        }

        .form-group {
            position: relative;
            padding: 0px 10px;
            margin-bottom: 1.75rem;

            &.overlap {
                position: relative;

                label {
                    position: absolute;
                    top: 10px;
                    left: 20px;
                    font-size: 1rem;
                    transition: all 0.25s ease-in-out;
                }

                &.active {
                    label {
                        top: -18px;
                        left: 10px;
                        font-size: 0.75rem;
                        transition: all 0.25s ease-in-out;
                    }
                }
            }
            &.has-error {
                input {
                    border-bottom-color: #8b0000;
                }
            }
            input {
                border-width: 0px;
                border-bottom: 1px solid #1b1e21;
                border-radius: 0px;
                &:focus {
                    box-shadow: none;
                }
            }
        }

        .form-group-button {
            margin: 0 -1rem;
            button[type="submit"].btn-full {
                background: $green;
                width: 100%;
                height: 50px;
                border-top-right-radius: 0;
                border-top-left-radius: 0;
            }
        }
    }
</style>