<template>
    <v-main class="" style="min-height: 300px;">
        <v-sheet width="300" class="mx-auto border border-20" >
            <h2>Авторизация</h2>
            <v-form fast-fail @submit.prevent>
                <v-text-field v-model="email" label="Почта"></v-text-field>
                <v-text-field v-model="password" label="Пароль" type="password"></v-text-field>
                <br>
                <v-btn type="submit" block class="mt-2" @click="login">Вход</v-btn>
            </v-form>
        </v-sheet>
    </v-main>
</template>
<script>
export default {
    data: () => ({
        email: null,
        password: null,
    }),
    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then(responce => {
                axios.post('/login', { email: this.email, password: this.password }).then(res => {
                    localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN']);
                    /* this.$router.push({ name: 'projects' }) */
                    window.location.reload()
                })
            })

        }
    },
}
</script>