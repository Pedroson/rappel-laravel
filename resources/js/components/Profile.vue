<template>
    <div>
        <div id="menu">
            <div class="menu-item">
                <router-link :to="{ name: 'dashboard' }">
                    <img class="img-fluid" src="/svg/ikonate/home.svg"/>
                </router-link>
            </div>
            <div class="menu-item">
                <router-link :to="{ name: 'profile' }">
                    <img class="img-fluid" src="/svg/ikonate/user.svg"/>
                </router-link>
            </div>
            <div class="menu-item"><img class="img-fluid" src="/svg/ikonate/dashboard.svg"/></div>
            <div class="menu-item"><img class="img-fluid" src="/svg/ikonate/alarm.svg"/></div>
            <div class="menu-item"><img class="img-fluid" src="/svg/ikonate/settings.svg"/></div>
            <div class="menu-item"> <a href="#" @click.prevent="$auth.logout()"><img class="img-fluid" src="/svg/ikonate/exit.svg"/></a></div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>

<style lang="scss">
    @import '~@/app.scss';

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