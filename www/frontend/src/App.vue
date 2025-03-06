<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'

// Variáveis reativas
const token = ref('')
const userRole = ref('')

// Instâncias do router e da rota atual
const router = useRouter()
const route = useRoute()

// Função para recarregar localStorage
function loadFromLocalStorage() {
  token.value = localStorage.getItem('token') || ''
  userRole.value = localStorage.getItem('userRole') || ''
}

// Carrega ao montar
onMounted(() => {
  loadFromLocalStorage()
})

// Sempre que a rota mudar, recarrega
watch(
  () => route.path,
  () => {
    loadFromLocalStorage()
  }
)

// Computed para verificar login e role
const isLoggedIn = computed(() => !!token.value)
const isAdmin = computed(() => userRole.value === 'admin')
const isClient = computed(() => userRole.value === 'client')

// Logout
function logout() {
  localStorage.removeItem('token')
  localStorage.removeItem('userRole')
  router.push('/login')
}
</script>

<template>
  <v-app>
    <v-app-bar color="primary" dark app image="https://picsum.photos/1920/1080?ramdom">

      <v-img
        class="mx-5"
        src="./src/assets/onfly-header.svg"
        max-width="125"
        contain
      ></v-img>

<!--      <v-toolbar-title>Onfly</v-toolbar-title>-->
      <v-spacer></v-spacer>

      <!-- Se não estiver logado, exibe Login e Register -->
      <template v-if="!isLoggedIn">
        <v-btn variant="text" to="/login">Login</v-btn>
        <v-btn variant="text" to="/register">Register</v-btn>
      </template>

      <!-- Menu para Admin -->
      <template v-else-if="isAdmin">
        <v-btn variant="text" to="/admin">Dashboard Admin</v-btn>
        <v-btn variant="text" @click="logout">Logout</v-btn>
      </template>

      <!-- Menu para Client -->
      <template v-else-if="isClient">
        <v-btn variant="text" to="/client">Dashboard Cliente</v-btn>
        <v-btn variant="text" to="/order/new">Criar Pedido</v-btn>
        <v-btn variant="text" @click="logout">Logout</v-btn>
      </template>
    </v-app-bar>

    <v-main>
      <notification-component />
      <router-view />
    </v-main>
  </v-app>
</template>

<script>
import NotificationComponent from '@/components/NotificationComponent.vue'
export default {
  components: {
    NotificationComponent
  }
}
</script>
