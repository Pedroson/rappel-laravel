<template>
    <div>
<!--        <menu-component></menu-component>-->
        <div id="content">
            <div class="content-header">
                <h1 class="content-header--text">Todo</h1>
            </div>
            <div class="content-body">
                <div class="content-section rappel-corner flex-basis-30">
                    <div class="form-container">
                        <form id="createList" autocomplete="off" @submit.prevent="createList" method="post">
                            <div class="form-group overlap" v-bind:class="{ 'active': (isActive && index === 'new_list_name') || new_list_name, 'has-error': (error && serverErrors.errors.new_list_name && !new_list_name) ||  errors.has('new_list_name') }">
                                <label for="new_list_name">Name</label>
                                <input v-on:focus="isFocused('new_list_name', $event)" v-on:blur="isFocused('new_list_name', $event)" type="text" name="new_list_name" id="new_list_name" class="form-control" v-validate="'required'" v-model="new_list_name">
                                <span class="help-block" v-if="error && serverErrors.errors.new_list_name && !new_list_name">{{ tidyError(serverErrors.errors.new_list_name) }}</span>
                                <span class="help-block" v-if="errors.has('new_list_name')">{{ errors.first('new_list_name') }}</span>
                            </div>
                            <div class="form-group-button fixed-right">
                                <button type="submit" class="btn btn-default btn-full">Add new list</button>
                            </div>
                        </form>
                    </div>
                    <div class="list-container">
                        <ul>
                            <li v-for="list in lists" v-bind:data-id="list.id">
                                {{ list.name }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="content-section rappel-corner flex-basis-70"></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                lists : [

                ],
                new_list_name: '',
                error: false,
                success: false,
                serverErrors: {
                    msg: false,
                    errors: false
                },
                isActive: false,
                alert: false,
                index: false,
                loading: false,
            }
        },
        watch: {
            success: function (val) {
                if (val === true) {
                    var app = this
                    setTimeout(function () {
                        app.alert = false
                    }, 3000)
                }
            },
            error: function (val) {
                if (val === true) {
                    var app = this
                    setTimeout(function () {
                        app.alert = false
                    }, 3000)
                }
            }
        },
        methods: {
            createList() {
                var app = this
                this.$validator.validateAll().then(function(result) {
                    if(result === true) {
                        app.axios.post('/auth/todo/list/create', {
                            'name': app.new_list_name
                        })
                          .then(function (resp) {
                              app.success = true;
                              app.alert = true;
                              app.lists.push(resp.data.payload)
                              app.serverErrors.msg = resp.data.msg;
                          })
                          .catch(function (resp) {
                              app.error = true
                              app.alert = true
                              app.serverErrors.msg = resp.response.data.msg
                              if (resp.response.data.errors) {
                                  app.serverErrors.errors = resp.response.data.errors
                              }
                          })
                    }
                });
            },
            getLists() {
                let app = this;
                app.axios.get('/auth/todo/list/index')
                  .then(function (resp) {
                      app.success = true;
                      app.serverErrors.msg = resp.data.msg;
                      resp.data.payload.forEach(function(item){
                          app.lists.push((item));
                      })
                  })
                  .catch(function (resp) {
                      app.error = true
                      app.alert = true
                      app.serverErrors.msg = resp.response.data.msg
                      if (resp.response.data.errors) {
                          app.serverErrors.errors = resp.response.data.errors
                      }
                  })
            },
            tidyError (error) {
                return error[0]
            },
            isFocused ($name, $event) {
                this.index = $name
                this.isActive = $event.type === 'focus' || $event.type === 'blur' && this[$name]
            }
        },
        mounted () {
            this.getLists();
        }
    }
</script>

<style lang="scss">
    @import '~@/app.scss';

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    .form-container {
        height: 20%;

        h1 {
            color: #fff;
        }


        form#createList {
            position: relative;
            height: 100%;
            background: #fff;
            padding: 1.75rem 1rem 0 1rem;

            #profile_picture {
                position: absolute;
                top: -1000px;
            }
        }

        .form-body {
            height: 100%;
            //overflow-y: scroll;
            //height: 100%;
            padding-bottom: 50px;
            //margin: 0px -15px;
            @include desktop {
                //overflow-y: initial;
                //height: 100%;
                //padding-bottom: 0;
                //margin: 0;
            }
        }

        .form-group {
            position: relative;
            padding: 0px 10px;
            margin-bottom: 1.75rem;

            &.profile-image {
                .avatar-container {
                    width: 140px;
                    height: 140px;
                    padding: 10px;
                    margin: auto;
                }
                .change-profile-picture {
                    position: absolute;
                    top: 70%;
                    left: 53%;
                    background: #fff;
                    border-radius: 50%;
                    box-shadow: 1px 1px 8px -3px rgba(0,0,0,0.75);
                    cursor: pointer;
                    z-index: 15;
                    img {
                        width: 40px;
                    }
                }
            }

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
                    border-width: 0px;
                    border-bottom: 1px solid #8b0000;
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

            &.fixed-btm {
                width: 100%;
                position: absolute;
                bottom: 0;
                left: 16px;
                z-index: 10;
            }
        }
    }

    .list-container {
        height: 80%;
    }

    #menu {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 80px;
        background: #fff;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;

        .menu-item {
            img {
                width: 45px;
            }
        }

        @include desktop {
            top: 0;
            bottom: initial;
            width: 100px;
            height: 100%;
            flex-direction: column;

            .menu-item {
                img {
                    width: 65px;
                }
            }
        }
    }
</style>