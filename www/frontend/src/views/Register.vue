<template>
  <v-app>
    <v-container>
      <v-card class="mx-auto" max-width="400">
        <v-card-title>
          Registrar
        </v-card-title>
        <v-card-text>
          <!-- Exibe erro geral de registro -->
          <v-alert v-if="error" type="error" class="mb-2">
            {{ error }}
          </v-alert>

          <v-form @submit.prevent="register">
            <!-- Nome -->
            <v-text-field
              v-model="name"
              label="Nome"
              required
              outlined
              dense
            ></v-text-field>

            <!-- Email -->
            <v-text-field
              v-model="email"
              label="Email"
              required
              outlined
              dense
              :error="!!emailError"
              :error-messages="[emailError]"
              :append-outer-icon="emailChecking ? 'mdi-loading' : ''"
            ></v-text-field>

            <!-- Senha -->
            <v-text-field
              v-model="password"
              label="Senha"
              type="password"
              required
              outlined
              dense
            ></v-text-field>

            <!-- Botão Registrar -->
            <v-btn
              color="primary"
              type="submit"
              block
              :loading="loading"
              :disabled="loading || !!emailError"
            >
              Registrar
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import api from '../services/api'
import { ref, watch } from 'vue'

export default {
  name: 'Register',
  setup() {
    // Campos do formulário
    const name = ref('')
    const email = ref('')
    const password = ref('')

    // Estados de erro e loading
    const error = ref('')
    const loading = ref(false)

    // Verificação de email
    const emailError = ref('')
    const emailChecking = ref(false)
    let emailTimeout = null

    // Observa mudanças em email e faz verificação com debounce
    watch(email, (newVal) => {
      emailError.value = ''
      if (emailTimeout) clearTimeout(emailTimeout)
      // Aguarda 500ms sem digitar para chamar a API
      emailTimeout = setTimeout(() => {
        checkEmail(newVal)
      }, 500)
    })

    // Método para consultar se o email existe
    const checkEmail = async (value) => {
      if (!value) return
      emailChecking.value = true
      try {
        const res = await api.get('/check-email', {
          params: { email: value },
        })
        if (res.data.exists) {
          emailError.value = 'E-mail já cadastrado.'
        } else {
          emailError.value = ''
        }
      } catch (err) {
        emailError.value = 'Erro ao verificar e-mail.'
      } finally {
        emailChecking.value = false
      }
    }

    // Método de registro
    const register = async () => {
      error.value = ''
      loading.value = true
      try {
        const res = await api.post('/register', {
          name: name.value,
          email: email.value,
          password: password.value,
        })
        localStorage.setItem('token', res.data.token)
        localStorage.setItem('userRole', res.data.user.role)
        // Redireciona conforme o role, mas aqui assume client
        window.location.href = '/client'
      } catch (err) {
        error.value = 'Erro ao registrar.'
      } finally {
        loading.value = false
      }
    }

    return {
      name,
      email,
      password,
      error,
      loading,
      emailError,
      emailChecking,
      register,
    }
  },
}
</script>

<style scoped>
</style>
