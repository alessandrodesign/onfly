import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import AdminDashboard from '../views/AdminDashboard.vue';
import ClientDashboard from '../views/ClientDashboard.vue';
import OrderDetails from '../views/OrderDetails.vue';
import OrderForm from '../views/OrderForm.vue';

const routes = [
  { path: '/login', name: 'Login', component: Login },
  { path: '/register', name: 'Register', component: Register },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/client',
    name: 'ClientDashboard',
    component: ClientDashboard,
    meta: { requiresAuth: true, role: 'client' }
  },
  { path: '/orders/:id', name: 'OrderDetails', component: OrderDetails, meta: { requiresAuth: true, role: 'admin' } },
  {
    path: '/order/new',
    name: 'OrderForm',
    component: OrderForm,
    meta: { requiresAuth: true, role: 'client' }
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// Middleware de proteção de rotas
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');
  if (to.meta.requiresAuth && !token) {
    next({ name: 'Login' });
  } else {
    // Aqui pode adicionar lógica para verificação de role se necessário
    next();
  }
});

export default router;
