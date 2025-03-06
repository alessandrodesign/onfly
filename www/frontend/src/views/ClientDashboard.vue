<template>
  <v-app>
    <v-container>
      <v-card>
        <v-card-title>
          Dashboard Cliente
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" sm="8">
              <v-text-field
                v-model="search"
                label="Buscar pedidos"
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <!-- Ao clicar, chamamos fetchOrders(1) para resetar a paginação -->
              <v-btn color="primary" @click="fetchOrders(1)" block>Buscar</v-btn>
            </v-col>
          </v-row>

          <!-- Tabela com Loading -->
          <v-data-table
            class="mt-4"
            :headers="headers"
            :items="orders.data"
            :items-per-page="10"
            :loading="loading"
          >
            <!-- Slot de loading customizado (opcional) -->
            <template #loading>
              <div class="text-center ma-5">
                <v-progress-circular
                  size="40"
                  color="primary"
                  indeterminate
                />
              </div>
            </template>

            <!-- Slot para a coluna de status (opcional, se quiser cores diferentes) -->
            <template #item.status="{ item }">
              <v-chip
                :color="statusColor(item.status)"
                text-color="white"
                label
                small
              >
                {{ item.status }}
              </v-chip>
            </template>
          </v-data-table>

          <!-- Paginação -->
          <v-pagination
            v-model="orders.current_page"
            :length="orders.last_page"
            class="mt-4"
            @input="changePage"
          ></v-pagination>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>
import api from '../services/api';

export default {
  name: 'ClientDashboard',
  data() {
    return {
      loading: false,       // controla o spinner de carregamento
      search: '',
      orders: {
        data: [],
        current_page: 1,
        last_page: 1
      },
      headers: [
        {text: 'ID', value: 'id'},
        {text: 'Destino', value: 'destination'},
        {text: 'Data Ida', value: 'departure_date'},
        {text: 'Data Volta', value: 'return_date'},
        {text: 'Status', value: 'status'}
      ]
    }
  },
  created() {
    this.fetchOrders(1);
  },
  methods: {
    async fetchOrders(page = 1) {
      this.loading = true;
      try {
        // Inclui 'page' e 'search' como parâmetros
        const res = await api.get('/orders', {
          params: {
            page,
            search: this.search
          }
        });
        this.orders = res.data;
      } catch (err) {
        console.error('Erro ao buscar pedidos', err);
      } finally {
        this.loading = false;
      }
    },
    changePage(page) {
      this.fetchOrders(page);
    },
    statusColor(status) {
      switch (status) {
        case 'solicitado':
          return 'blue darken-2';
        case 'aprovado':
          return 'green darken-2';
        case 'cancelado':
          return 'red darken-2';
        default:
          return 'grey darken-2';
      }
    }
  }
}
</script>

<style scoped>
</style>
