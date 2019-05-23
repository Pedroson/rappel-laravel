<template>
    <div>
        <div class="alert alert-danger rappel-corner" v-if="alert && error && !success">
            <p>{{ serverErrors.msg }}</p>
        </div>
        <div class="alert alert-success rappel-corner" v-if="alert && success">
            <p>{{ serverErrors.msg }}</p>
        </div>
<!--        <menu-component></menu-component>-->
        <div id="content">
            <div class="content-header">
                <h1 class="content-header--text">Profile</h1>
            </div>
            <div class="content-body">
                <div class="content-section rappel-corner">
                    <div class="loader" v-if="loading">
                        <p>Loading...</p>
                    </div>
                    <div class="form-container full-height" v-if="!loading">
                        <form id="updateProfile" autocomplete="off" @submit.prevent="updateProfile" method="post" enctype="multipart/form-data">
                            <simplebar class="form-body" data-simplebar-auto-hide="true">
                                <input type="file" name="profile_picture" id="profile_picture" ref="profile_picture" v-on:change="updateProfilePicture($event)">
                                <div class="form-group profile-image">
                                    <div class="avatar-container" v-on:mouseover.self="showProfileAdd = true" v-on:mouseleave.self="showProfileAdd = false">
                                        <avatar :username="name" :customStyle="avatarStyles" :src="profile_picture"></avatar>
                                        <div class="change-profile-picture" v-show="showProfileAdd" v-on:click="clickEvent($event)">
                                            <img class="img-fluid" src="/svg/ikonate/plus.svg"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group overlap"
                                     v-bind:class="{ 'active': (isActive && index === 'name') || name, 'has-error': (error && serverErrors.errors.name && !name) ||  errors.has('name') }">
                                    <label for="name">Name</label>
                                    <input v-on:focus="isFocused('name', $event)" v-on:blur="isFocused('name', $event)"
                                           type="text" id="name" class="form-control" name="name" v-model="name"
                                           v-validate="'required|alpha_spaces'">
                                    <span class="help-block" v-if="error && serverErrors.errors.name && !name">{{ tidyError(serverErrors.errors.name) }}</span>
                                    <span class="help-block" v-if="errors.has('name')">{{ errors.first('name') }}</span>
                                </div>
                                <div class="form-group overlap"
                                     v-bind:class="{ 'active': (isActive && index === 'email') || email, 'has-error': (error && serverErrors.errors.email && !email) || errors.has('email') }">
                                    <label for="email">E-mail</label>
                                    <input v-on:focus="isFocused('email', $event)" v-on:blur="isFocused('email', $event)"
                                           type="email" id="email" class="form-control" name="email" v-model="email"
                                           v-validate="'required|email'">
                                    <span class="help-block" v-if="error && serverErrors.errors.email && !email">{{ tidyError(serverErrors.errors.email) }}</span>
                                    <span class="help-block" v-if="errors.has('email')">{{ errors.first('email') }}</span>
                                </div>
                            </simplebar>
                            <div class="form-group-button fixed-btm">
                                <button type="submit" class="btn btn-default btn-full">Update profile</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="content-section rappel-corner"></div>
            </div>
        </div>
    </div>
</template>

<script>
    import Avatar from 'vue-avatar';
    import Simplebar from 'simplebar-vue';
    import 'simplebar/dist/simplebar.min.css';

    export default {
        components: {
          'avatar': Avatar,
          'simplebar': Simplebar
        },
        data () {
            return {
                id: '',
                name: '',
                email: '',
                profile_picture: '',
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
                avatarStyles: {
                    'width': '120px',
                    'height': '120px',
                    'font': '45px Helvetica, Arial, sans-serif',
                    'margin': 'auto',
                    'background-size': '120px 120px'
                },
                showProfileAdd: false
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
            getProfile () {
                var app = this
                app.loading = true
                app.axios.get('/auth/user')
                  .then(function (resp) {
                      app.id = resp.data.data.id
                      app.name = resp.data.data.name
                      app.email = resp.data.data.email
                      if(resp.data.data.profile_picture) {
                          app.profile_picture = '/storage/profile_pictures/'+app.id+'/'+resp.data.data.profile_picture;
                      }
                      app.isActive = true
                      app.loading = false
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
            updateProfile () {
                var app = this
                app.axios.post('/auth/user/' + app.id + '/update', {
                    'name': app.name,
                    'email': app.email
                })
                  .then(function (resp) {
                      app.success =true;
                      app.alert = true;
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
            },
            updateProfilePicture($event) {
                var app = this,
                    formData = new FormData();
                formData.append('profile_picture', app.$refs.profile_picture.files[0]);
                app.axios.post('/auth/user/' + app.id + '/update/profile-picture',
                  formData,
                  {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                  .then(function (resp) {
                        console.log(resp);
                        app.profile_picture = '/storage/' + resp.data.data;
                  })
                  .catch(function (resp) {
                      console.log(resp);
                  })
            },
            clickEvent($event) {
                var elem = this.$refs.profile_picture;
                elem.click();
            },
            tidyError (error) {
                return error[0]
            },
            isFocused ($name, $event) {
                this.index = $name
                this.isActive = $event.type === 'focus' || $event.type === 'blur' && this[$name]
            }
        },
        created () {
            this.getProfile()
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

    .form-container.full-height {
        height: 100%;

        h1 {
            color: #fff;
        }


        form#updateProfile {
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