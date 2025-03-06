<template>
  <!-- Exibe a lista de notificações se houver ao menos uma -->
  <div class="notification-container" v-if="notifications.length">
    <div
      v-for="(notification, index) in notifications"
      :key="index"
      class="notification"
    >
      {{ notification }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotificationComponent',
  data() {
    return {
      notifications: []
    }
  },
  created() {
    // Escuta eventos do canal privado "orders"
    // Ajuste conforme o nome do seu canal no broadcastOn() do evento
    window.Echo.private('orders')
      .listen('NewOrderEvent', (event) => {
        // Exemplo de mensagem para novo pedido
        this.notifications.push(
          `Novo pedido criado: #${event.order.id} - Destino: ${event.order.destination}`
        );
      })
      .listen('OrderStatusChangedEvent', (event) => {
        // Exemplo de mensagem para mudança de status
        this.notifications.push(
          `Status do pedido #${event.order.id} mudou para "${event.order.status}"`
        );
      });
  }
}
</script>

<style scoped>
.notification-container {
  position: fixed;
  top: 80px;
  right: 20px;
  width: 300px;
  z-index: 9999;
}

.notification {
  background-color: #f2f2f2;
  border: 1px solid #ccc;
  padding: 8px;
  margin-bottom: 8px;
  border-radius: 4px;
  font-size: 14px;
}
</style>
