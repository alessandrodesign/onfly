<template>
  <v-app>
    <v-container>
      <v-card class="px-4 py-2">
        <v-card-title>
          Detalhes do Pedido
        </v-card-title>

        <!-- Spinner enquanto 'loading' for true -->
        <v-progress-circular
          v-if="loading"
          color="primary"
          indeterminate
          class="mx-auto my-5"
        ></v-progress-circular>

        <!-- Se não estiver carregando e houver um pedido -->
        <v-card-text v-else-if="order">
          <v-list>
            <!-- Subheader de Informações -->
            <v-subheader>Informações do Pedido</v-subheader>
            <v-divider class="mb-2"></v-divider>

            <!-- ID -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-pound</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>ID: {{ order.id }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <!-- Solicitante -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-account</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Solicitante</v-list-item-title>
                <v-list-item-subtitle>
                  <span v-if="order.user && order.user.name">
                    {{ order.user.name }}
                  </span>
                  <span v-else>
                    Usuário ID #{{ order.user_id }}
                  </span>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>

            <!-- Destino -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-map-marker</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Destino: {{ order.destination }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <!-- Data Ida -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-calendar-start</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Data Ida: {{ order.departure_date }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <!-- Data Volta -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-calendar-end</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Data Volta: {{ order.return_date }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <!-- Status com cor dinâmica -->
            <v-list-item>
              <v-list-item-icon>
                <v-icon>mdi-information</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>
                  <v-chip
                    :color="statusColor(order.status)"
                    text-color="white"
                    label
                    small
                  >
                    {{ order.status }}
                  </v-chip>
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <!-- Se o pedido estiver 'solicitado', mostra botões para aprovar/cancelar -->
          <v-subheader v-if="order.status === 'solicitado'" class="mt-4">
            Ações
          </v-subheader>
          <v-divider v-if="order.status === 'solicitado'"></v-divider>
          <div
            v-if="order.status === 'solicitado'"
            class="d-flex align-center mt-3 gap-2"
          >
            <v-btn color="success" @click="updateStatus('aprovado')">
              Aprovar
            </v-btn>
            <v-btn color="error" @click="updateStatus('cancelado')">
              Cancelar
            </v-btn>
          </div>
        </v-card-text>

        <!-- Mensagem de sucesso ou erro -->
        <v-alert v-if="message" type="success" class="mt-2">
          {{ message }}
        </v-alert>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import api from '../services/api';

export default {
  name: 'OrderDetails',
  data() {
    return {
      order: null,
      message: '',
      loading: false
    }
  },
  created() {
    this.fetchOrder();
  },
  methods: {
    async fetchOrder() {
      this.loading = true;
      const id = this.$route.params.id;
      try {
        const res = await api.get(`/orders/${id}`);
        this.order = res.data;
      } catch (err) {
        this.message = 'Erro ao buscar detalhes do pedido.';
      } finally {
        this.loading = false;
      }
    },
    async updateStatus(status) {
      this.loading = true;
      try {
        const res = await api.put(`/orders/${this.order.id}`, { status });
        this.order = res.data;
        this.message = 'Status atualizado com sucesso!';
      } catch (err) {
        this.message = 'Erro ao atualizar status.';
      } finally {
        this.loading = false;
      }
    },
    // Retorna uma cor para cada status
    statusColor(status) {
      switch (status) {
        case 'solicitado':
          return 'blue darken-2'
        case 'aprovado':
          return 'green darken-2'
        case 'cancelado':
          return 'red darken-2'
        default:
          return 'grey darken-2'
      }
    }
  }
}
</script>

<style scoped>
.d-flex {
  display: flex;
}
.gap-2 {
  gap: 1rem;
}
</style>
