<template>
    <section class="main" v-bind:class="this.$route.name">
        <div id="header" class="container-fluid">
            <div class="row">
                <div class="col-2">
                    <router-link :to="{ name: 'home' }">
                        <h1 class="logo">Rappel</h1>
                    </router-link>
                </div>
                <div class="col-10">
                    <nav class="float-right">
                        <ul class="list-inline">
                            <li v-if="!$auth.check()">
                                <router-link :to="{ name: 'login' }">Login</router-link>
                            </li>
                            <li v-if="!$auth.check()">
                                <router-link :to="{ name: 'register' }">Register</router-link>
                            </li>
                            <li v-if="$auth.check()">
                                <a href="#" @click.prevent="$auth.logout()">Logout</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div id="body">
            <router-view class="parent"></router-view>
        </div>
        <div class="footer"></div>
    </section>
</template>

<style lang="scss">
    //@import '~@/app.scss';
</style>