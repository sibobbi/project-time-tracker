<template>
    <v-layout class="rounded rounded-md">
        <v-app-bar title="Project Time Tracker"></v-app-bar>

        <v-navigation-drawer>
            <v-list>
                <router-link v-if="token" :to="{ name: 'projects' }"  :key="$route.fullPath" >
                    <v-list-item title="Проекты" class="border border-1"></v-list-item>
                </router-link>
                <router-link v-if="!token" :to="{ name: 'user.login' }"  :key="$route.fullPath" >
                    <v-list-item title="Войти" class="border border-1"></v-list-item>
                </router-link>
                <router-link v-if="!token" :to="{ name: 'user.register' }"  :key="$route.fullPath" >
                    <v-list-item title="Регистрация" class="border border-1"></v-list-item>
                </router-link>
                <v-list-item v-if="token" class="border border-1" @click="logout">
                    <a @click.prevent="" href="#">Выйти из аккаунта</a>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-main class="d-flex align-center justify-center" style="min-height: 300px;">
            <router-view></router-view>
        </v-main>
    </v-layout>
</template>
<script>
export default {
    data() {
        return {
            token: null
        }
    },
    beforeMount() {
        this.getToken()
    },
    onBeforeUpdate() {
        this.getToken()
    },
    methods: {
        getToken() {
            this.token = localStorage.getItem('x_xsrf_token')
        },
        logout() {
            axios.post('/logout').then(res => {
                localStorage.removeItem('x_xsrf_token')
                /* this.$router.push({ name: 'user.login' }) */
                window.location.reload()
            })
        }
    }
}
</script>