<template>
    <div>
        <transition name="fade">
            <div class="alert alert-danger" v-if="alert && error">
                <p>{{ serverErrors.msg }}</p>
            </div>
        </transition>
        <transition name="fade">
            <div class="alert alert-success" v-if="alert && success">
                please check your inbox for next steps
            </div>
        </transition>
        <div class="form-container centered">
            <h1>Forgotten Password</h1>
            <form id="forgottenPassword" class="rappel-corner" autocomplete="off" @submit.prevent="forgottenPassword" method="post">
                <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'email') || isActive && email, 'has-error': (error && serverErrors.errors.name && !email) ||  errors.has('email') }">
                    <label for="email">E-mail</label>
                    <input v-on:focus="isFocused('email', $event)" v-on:blur="isFocused('email', $event)" type="email" id="email" name="email" class="form-control" v-model="email" v-validate="'required|email'">
                    <span class="help-block" v-if="error && serverErrors.errors.email && !email">{{ tidyError(serverErrors.errors.email) }}</span>
                    <span class="help-block" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                </div>
                <div class="form-group-button">
                    <button type="submit" class="btn btn-default btn-full">Send</button>
                </div>
            </form>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return {
                email: null,
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
        watch: {
            success: function(val) {
                if(val === true) {
                    var app = this;
                    setTimeout(function() {
                        app.alert = false;
                        // app.error = false;
                        // app.serverErrors = false;
                    }, 3000);
                }
            },
            error: function(val) {
                if(val === true) {
                    var app = this;
                    setTimeout(function() {
                        app.alert = false;
                        // app.error = false;
                        // app.serverErrors = false;
                    }, 3000);
                }
            }
        },
        methods: {
            forgottenPassword() {
                var app = this;
                this.$validator.validateAll().then(function(result) {
                    if(result === true) {
                        app.axios.post('/auth/forgotten-password', {
                            'email': app.email
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
        }, mounted() {
            if(this.$route.params.errors) {
                this.error = true;
                this.alert = true;
                this.serverErrors.msg = this.$route.params.errors;
            }
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


        form#forgottenPassword {
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