<template>
  <v-app>
    <v-container>
      <v-card class="mx-auto" max-width="400">
        <v-card-title>
          Login
        </v-card-title>
        <v-card-text>
          <v-alert v-if="error" type="error" class="mb-2">
            {{ error }}
          </v-alert>

          <v-form @submit.prevent="login">
            <v-text-field
              v-model="email"
              label="Email"
              required
              outlined
              dense
            ></v-text-field>
            <v-text-field
              v-model="password"
              label="Senha"
              type="password"
              required
              outlined
              dense
            ></v-text-field>
            <v-btn
              color="primary"
              type="submit"
              block
              :loading="loading"
              :disabled="loading"
            >
              Entrar
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import api from '../services/api';

export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      error: '',
      loading: false
    }
  },
  methods: {
    async login() {
      this.error = '';
      this.loading = true; // Inicia o loading
      try {
        const res = await api.post('/login', {
          email: this.email,
          password: this.password
        });
        localStorage.setItem('token', res.data.token);
        localStorage.setItem('userRole', res.data.user.role);
        if (res.data.user.role === 'admin') {
          this.$router.push('/admin');
        } else {
          this.$router.push('/client');
        }
      } catch (err) {
        this.error = 'Erro ao efetuar login.';
      } finally {
        this.loading = false; // Finaliza o loading
      }
    }
  }
}
</script>

<style scoped>
</style>
