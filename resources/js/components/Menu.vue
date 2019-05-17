<template>
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
        <div class="menu-item">
            <router-link :to="{ name: 'todo' }">
                <img class="img-fluid" src="/svg/ikonate/dashboard.svg"/>
            </router-link>
        </div>
        <div class="menu-item">
            <router-link :to="{ name: 'reminder' }">
                <img class="img-fluid" src="/svg/ikonate/alarm.svg"/>
            </router-link>
        </div>
        <div class="menu-item">
            <router-link :to="{ name: 'settings' }">
                <img class="img-fluid" src="/svg/ikonate/settings.svg"/>
            </router-link>
        </div>
        <div class="menu-item">
            <a href="#" @click.prevent="$auth.logout()"><img class="img-fluid" src="/svg/ikonate/exit.svg"/></a>
        </div>
    </div>
</template>