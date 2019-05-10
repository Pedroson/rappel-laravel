<template>
    <div>
        <transition name="fade">
            <div class="alert alert-danger" v-if="alert && error">
                <p>{{ serverErrors }}</p>
            </div>
        </transition>
        <transition name="fade">
            <div class="alert alert-success" v-if="alert && success">
                password successfully reset, you can now login
            </div>
        </transition>
        <div class="form-container centered">
            <h1>Reset Password</h1>
            <form id="resetPassword" class="rappel-corner" autocomplete="off" @submit.prevent="resetPassword" method="post">
                <input type="hidden" name="token" v-model="token">
                <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'password') || isActive && password, 'has-error': (error && serverErrors.errors.password && !password) || errors.has('password') }">
                    <label for="password">Password</label>
                    <input v-on:focus="isFocused('password', $event)" v-on:blur="isFocused('password', $event)" type="password" id="password" class="form-control" name="password" v-model="password" v-validate="'required|min:6|max:10'" ref="password">
                    <span class="help-block" v-if="error && serverErrors.errors.password && !password">{{ tidyError(serverErrors.errors.password) }}</span>
                    <span class="help-block" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>
                <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'password_confirm') || isActive && password_confirm, 'has-error': (error && serverErrors.errors.password_confirm && !password_confirm) || errors.has('password_confirm') }">
                    <label for="password_confirm">Password Confirm</label>
                    <input v-on:focus="isFocused('password_confirm', $event)" v-on:blur="isFocused('password_confirm', $event)" type="password" id="password_confirm" class="form-control" name="password_confirm" v-model="password_confirm" v-validate="'required|min:6|max:10|confirmed:password'" data-vv-as="password">
                    <span class="help-block" v-if="error && serverErrors.errors.password_confirm && !password_confirm">{{ tidyError(serverErrors.errors.password_confirm) }}</span>
                    <span class="help-block" v-if="errors.has('password_confirm')">{{ errors.first('password_confirm') }}</span>
                </div>
                <div class="form-group-button">
                    <button type="submit" class="btn btn-default btn-full">Reset Password</button>
                </div>
            </form>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                password: null,
                password_confirm: null,
                error: false,
                serverErrors: {
                    msg: false,
                    errors: false
                },
                success: false,
                isActive: false,
                index: false,
                alert: false
            }
        },
        computed: {
            token() {
                return this.$route.params.token;
            }
        },
        watch: {
            success: function(val) {
                if(val === true) {
                    var app = this;
                    setTimeout(function() {
                        app.alert = false;
                        app.errors = false;
                        app.serverErrors = false;
                    }, 3000);
                }
            },
            error: function(val) {
                if(val === true) {
                    var app = this;
                    setTimeout(function() {
                        app.alert = false;
                        app.errors = false;
                        app.serverErrors = false;
                    }, 3000);
                }
            }
        },
        methods: {
            resetPassword(){
                var app = this;
                this.$validator.validateAll().then(function(result) {
                    if(result === true) {
                        app.axios.post('/auth/reset-password', {
                            'token': app.token,
                            'password': app.password,
                            'password_confirm': app.password_confirm
                        })
                          .then(function() {
                              app.success = true;
                              app.alert = true;
                          })
                          .catch(function(resp) {
                              app.error = true;
                              app.alert = true;
                              app.serverErrors.msg = resp.response.data.msg;
                              if(resp.response.data.errors) {
                                  app.serverErrors.errors = resp.response.data.errors;
                              }
                          });
                    }
                });
            },
            tidyError(error) {
                return error[0];
            },
            isFocused($name, $event) {
                this.index = $name;
                this.isActive = $event.type === 'focus' || $event.type === 'blur' && this[$name];
            }
        },
        mounted () {
            var app = this;
            this.axios.get('/auth/check-token-validity/'+this.token)
              .then(function(resp) {
              })
              .catch(function(resp) {
                  app.$router.push({
                      name: 'forgotten-password',
                      params: {
                          errors: resp.response.data.msg
                      }
                  });
              });
        }
    }
</script>

<style lang="scss">
    @import '~@/app.scss';

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }

    .form-container.centered {
        @include absoluteCenter;
        width: 400px;

        h1 {
            color: #fff;
        }


        form#resetPassword {
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