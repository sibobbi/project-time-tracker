<template>
    <v-sheet width="300" class="mx-auto">
        <h2>Регистрация</h2>
        <v-form fast-fail @submit.prevent>
            <v-text-field v-model="name" label="Имя" :rules="nameRules"></v-text-field>
            <v-text-field v-model="email" label="Почта"></v-text-field>
            <v-text-field v-model="password" label="Пароль" type="password"></v-text-field>
            <v-text-field v-model="password_confirmation" label="Повторите пароль" type="password"></v-text-field>
            <v-btn type="submit" block class="mt-2" @click="register">Регистрация</v-btn>
        </v-form>
    </v-sheet>
</template>
<script>
export default {
    data: () => ({
        name: null,
        email: null,
        password: null,
        password_confirmation: null,
        nameRules: [
            value => {
                if (value?.length > 0) return true
                return 'Поле не должно быть пустым.'
            },
        ],
    }),
    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie').then(responce => {
                axios.post('/register', { 
                    name: this.name, 
                    email: this.email, 
                    password: this.password,
                    password_confirmation: this.password_confirmation
                 })
                    .then(res => {
                        localStorage.setItem('x_xsrf_token',res.config.headers['X-XSRF-TOKEN']);
                        /* this.$router.push({ name: 'projects' }) */
                        window.location.reload()
                })
            })
        }
    },
}
</script>