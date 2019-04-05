<template>
    <div>
        <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
        </div>
        <div class="alert alert-success" v-if="success">
            <p>Registration completed. You can now <router-link :to="{name:'login'}">sign in.</router-link></p>
        </div>
        <form autocomplete="off" @submit.prevent="register" v-if="!success" method="post">
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
    @import '~@/app.scss';

    .rappel-corner {
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .rappel-button-corners {
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .center--all {
        @include absoluteCenter();
    }

    .center--v {
        @include absoluteCenterY();
    }

    .center--h {
        @include absoluteCenterX();
    }

    .form-group {
        position: relative;
        padding-top: 10px;

        &.fixed-submit {
            position: absolute;
            bottom: 0px;
            left: 0px;
            margin-bottom: 0px;
            width: 100%;
        }

        &:not(.submit) {

            &:after {
                content: "";
                display: block;
                position: absolute;
                bottom: 0px;
                left: 0px;
                width: 0%;
                height: 1px;
                background: #000;
                transition: all 0.25s ease-in-out;
                z-index: 2;
            }

            &.active {

                &:after {
                    content: "";
                    display: block;
                    position: absolute;
                    bottom: 0px;
                    left: 0px;
                    width: 100%;
                    height: 1px;
                    background: #000;
                    transition: all 0.25s ease-in-out;
                    z-index: 2;
                }
            }
        }

        label {
            position: absolute;
            top: 20px;
            left:5px;
            font-size: 16px;
            color: #a7a7a7;
            transition: all 0.25s ease-in-out;
            z-index: 1;

            &.active {
                font-size: 12px;
                top: 0px;
                left: 0px;
                color: #000;
                transition: all 0.25s ease-in-out;
            }
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            border-width: 0px;
            border-bottom-width: 1px;
            border-color: #a7a7a7;
            height: 40px;
            outline: none;
        }
    }

    .button {
        text-align: center;
        border-width: 0px;
        color: #fff;
        padding: 10px 20px;
        height: 60px;

        &--full {
            width: 100%;
        }

        &--green {
            background: $green;

            &:hover {
                background: darken($green, 10%);
                transition: background-color 0.25s ease-in-out;

            }
        }

        &--fixed-bottom {
            position: absolute;
            bottom:0px;
            left: 0px;
            margin-bottom: 0px;
            margin-top: 60px;
        }
    }

    .fixed-submit-container {
        position: relative;
        padding-bottom: 80px;
    }

    .form-error {
        color: #b4000d;
        margin-bottom: 15px;
    }

    span.validation-error {
        color: #b36c6f;
        font-size: 12px;
    }
</style>