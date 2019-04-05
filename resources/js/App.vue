<template>
    <section class="main" v-bind:class="this.$route.name">
        <div class="container-fluid">
            <div class="row">
                <div class="header">
                    <div class="col-2">
                        <h1 class="logo">Rappel</h1>
                    </div>
                    <div class="col-10">
                        <nav>
                            <ul class="list-inline">
                                <li v-if="this.$route.name != 'home'">
                                    <router-link :to="{ name: 'home' }">Home</router-link>
                                </li>
                                <li v-if="!$auth.check()" class="pull-right">
                                    <router-link :to="{ name: 'login' }">Login</router-link>
                                </li>
                                <li v-if="!$auth.check()" class="pull-right">
                                    <router-link :to="{ name: 'register' }">Register</router-link>
                                </li>
                                <li v-if="$auth.check()" class="pull-right">
                                    <a href="#" @click.prevent="$auth.logout()">Logout</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <router-view></router-view>
        </div>
        <div class="footer"></div>
    </section>
</template>

<style lang="scss">
    @import '~@/app.scss';
</style>