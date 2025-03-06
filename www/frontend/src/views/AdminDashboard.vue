<template>
  <v-app>
    <v-container>
      <v-card>
        <v-card-title>
          Dashboard Admin
        </v-card-title>
        <v-card-text>
          <v-row>
            <!-- Filtro de Status -->
            <v-col cols="12" sm="6" md="3">
              <v-select
                v-model="filters.status"
                :items="statuses"
                label="Status"
                dense
                outlined
              ></v-select>
            </v-col>

            <!-- Filtro de Destino -->
            <v-col cols="12" sm="6" md="3">
              <v-text-field
                v-model="filters.destination"
                label="Destino"
                dense
                outlined
              ></v-text-field>
            </v-col>

            <!-- Filtro Data Início -->
            <v-col cols="12" sm="6" md="3">
              <v-menu
                ref="menuStart"
                v-model="menuStart"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-model="filters.start_date"
                    label="Data Início"
                    prepend-icon="mdi-calendar"
                    readonly
                    dense
                    outlined
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="filters.start_date"
                  @input="menuStart = false"
                ></v-date-picker>
              </v-menu>
            </v-col>

            <!-- Filtro Data Fim -->
            <v-col cols="12" sm="6" md="3">
              <v-menu
                ref="menuEnd"
                v-model="menuEnd"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-model="filters.end_date"
                    label="Data Fim"
                    prepend-icon="mdi-calendar"
                    readonly
                    dense
                    outlined
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="filters.end_date"
                  @input="menuEnd = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>

          <!-- Botão Filtrar -->
          <v-btn color="primary" class="mt-2" @click="fetchOrders(1)">
            Filtrar
          </v-btn>

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

            <!-- Slot para a coluna de status -->
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

            <template #item.actions="{ item }">
              <v-btn icon color="primary" @click="viewOrder(item.id)" size="x-small">
                <v-icon>mdi-eye</v-icon>
              </v-btn>
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
  name: 'AdminDashboard',
  data() {
    return {
      loading: false,
      filters: {
        status: '',
        destination: '',
        start_date: '',
        end_date: ''
      },
      statuses: ['solicitado', 'aprovado', 'cancelado'],
      orders: {
        data: [],
        current_page: 1,
        last_page: 1
      },
      headers: [
        {text: 'ID', value: 'id'},
        {text: 'Solicitante', value: 'user.name'},
        {text: 'Destino', value: 'destination'},
        {text: 'Data Ida', value: 'departure_date'},
        {text: 'Data Volta', value: 'return_date'},
        {text: 'Status', value: 'status'},
        {text: 'Ações', value: 'actions', sortable: false}
      ],
      menuStart: false,
      menuEnd: false
    }
  },
  created() {
    this.fetchOrders(1);
  },
  methods: {
    async fetchOrders(page = 1) {
      this.loading = true;
      try {
        this.filters.page = page;
        const res = await api.get('/orders', {params: this.filters});
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
    viewOrder(id) {
      this.$router.push({name: 'OrderDetails', params: {id}});
    },
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
</style>
