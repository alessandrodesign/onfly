<template>
  <v-app>
    <v-container>
      <v-card class="mx-auto" max-width="500">
        <v-card-title>
          Criar Pedido de Viagem
        </v-card-title>
        <v-card-text>
          <v-alert v-if="message" type="success" class="mb-2">
            {{ message }}
          </v-alert>
          <v-alert v-if="error" type="error" class="mb-2">
            {{ error }}
          </v-alert>

          <!-- Formulário com validações e referência para validação -->
          <v-form ref="orderForm" v-model="formIsValid" @submit.prevent="createOrder">
            <!-- Destino -->
            <v-text-field
              v-model="order.destination"
              label="Destino"
              required
              outlined
              dense
              :rules="[requiredRule]"
            ></v-text-field>

            <!-- Data de Ida -->
            <v-text-field
              v-model="order.departure_date"
              label="Data de Ida"
              type="date"
              required
              outlined
              dense
              :rules="[requiredRule, validateDepartureDate]"
              :min="today"
            ></v-text-field>

            <!-- Data de Volta -->
            <v-text-field
              v-model="order.return_date"
              label="Data de Volta"
              type="date"
              required
              outlined
              dense
              :rules="[requiredRule, validateReturnDate]"
              :min="order.departure_date"
            ></v-text-field>

            <!-- Botão Enviar -->
            <v-btn
              color="primary"
              type="submit"
              block
              :loading="loading"
              :disabled="loading || !formIsValid"
            >
              Enviar Pedido
            </v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import { ref, computed } from 'vue'
import api from '../services/api'

export default {
  name: 'OrderForm',
  setup() {
    // Dados do formulário
    const order = ref({
      destination: '',
      departure_date: '',
      return_date: ''
    })

    // Estados de mensagem, erro e loading
    const message = ref('')
    const error = ref('')
    const loading = ref(false)

    // Validação do form
    const formIsValid = ref(false)
    const orderForm = ref(null)

    // Data de hoje no formato yyyy-mm-dd para restringir datas
    const today = new Date().toISOString().split('T')[0]

    // Regras de validação
    const requiredRule = (value) => !!value || 'Campo obrigatório.'

    const validateDepartureDate = (value) => {
      if (!value) return true
      // Verifica se é anterior a hoje
      if (value < today) {
        return 'A data de partida não pode ser anterior a hoje.'
      }
      return true
    }

    const validateReturnDate = (value) => {
      if (!value) return true
      // Verifica se a data de retorno é >= data de partida
      if (value < order.value.departure_date) {
        return 'A data de retorno deve ser maior ou igual à data de ida.'
      }
      return true
    }

    // Função de criação do pedido
    const createOrder = async () => {
      message.value = ''
      error.value = ''

      // Se o form estiver inválido, não prossegue
      const isValid = orderForm.value?.validate()
      if (!isValid) return

      loading.value = true
      try {
        await api.post('/orders', order.value)
        message.value = 'Pedido criado com sucesso!'
        order.value.destination = ''
        order.value.departure_date = ''
        order.value.return_date = ''
      } catch (err) {
        error.value = 'Erro ao criar pedido. Verifique os dados e tente novamente.'
      } finally {
        loading.value = false
      }
    }

    return {
      order,
      message,
      error,
      loading,
      formIsValid,
      orderForm,
      requiredRule,
      validateDepartureDate,
      validateReturnDate,
      createOrder,
      today
    }
  }
}
</script>

<style scoped>
</style>
