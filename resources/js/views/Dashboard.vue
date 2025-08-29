<template>
  <div class="dashboard-container">
    <header>
      <h1 v-if="isUserAdmin">v4 Fly | Dashboard Administrativa</h1>
      <h1 v-else>v4 Fly | Minhas Viagens </h1>
      <button @click="handleLogout">Sair</button>
    </header>
    <main>
      <p v-if="isUserAdmin">Bem-vindo à sua dashboard Administrativa, {{ userName }}!</p>
      <p v-else>Olá, {{ userName }}! Esta é a sua área exclusiva para acompanhar seus pedidos de viagem.</p>
      <div class="travel-orders-section">
        <div class="section-header">
           <h2 v-if="isUserAdmin"> Lista de Pedidos</h2>
           <h2 v-else>Bem-vindo à sua área de pedidos de viagem, {{ userName }}!</h2>
          <button v-if="!isUserAdmin" @click="isAddModalVisible = true" class="add-button">
           + Adicionar Pedido
          </button>
        </div>
        <div v-if="isLoading" class="loading">Carregando pedidos...</div>
        <div v-else-if="error" class="error">
          Ocorreu um erro ao buscar seus pedidos. Tente novamente mais tarde.
        </div>
        <table v-else-if="travelOrders.length > 0" class="orders-table">
          <thead>
            <tr>
              <th>Solicitante</th>
              <th>Destino</th>
              <th>IDA</th>
              <th>VOLTA</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in travelOrders" :key="order.id">
              <td>{{ order.user?.name || 'N/A' }}</td>
              <td>{{ order.destination }}</td>
              <td>{{ formatDate(order.start_date) }}</td>
              <td>{{ formatDate(order.end_date) }}</td>
              <td>
                <span :class="[
                    'status-badge',
                    `status-${order.status.toLowerCase()}`
                ]">
                  {{ order.status }}
                </span>
              </td>
              <td class="actions-cell">
                <button @click="showDetails(order)" class="action-button">Ver Detalhes</button>
                <template v-if="isUserAdmin && order.status === 'Pendente'">
                  <button @click.stop="updateStatus(order.id, 'Aprovado')" class="action-button btn-approve">Aprovar</button>
                  <button @click.stop="updateStatus(order.id, 'Cancelado')" class="action-button btn-cancel">Reprovar</button>
                </template>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="no-orders">
          Você ainda não tem nenhum pedido de viagem.
        </div>
      </div>
    </main>

    <TravelOrderDetailModal :visible="isModalVisible" :order="selectedOrder" @close="closeDetailsModal" />
    <AddOrderModal :visible="isAddModalVisible" @close="isAddModalVisible = false" @order-added="handleOrderAdded" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../store/auth';
import { useRouter } from 'vue-router';
import apiClient from '../services/api';
import TravelOrderDetailModal from '../components/TravelOrderDetailModal.vue';
import AddOrderModal from '../components/AddOrderModal.vue';

const authStore = useAuthStore();
const router = useRouter();
const travelOrders = ref([]);
const isLoading = ref(false);
const error = ref(null);
const isModalVisible = ref(false);
const selectedOrder = ref(null);
const isAddModalVisible = ref(false);
const userName = computed(() => authStore.user?.name || 'usuário');

const isUserAdmin = computed(() => authStore.isAdmin);

const fetchTravelOrders = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await apiClient.get('/travel-orders');
    travelOrders.value = response.data;
  } catch (err) {
    console.error("Erro ao buscar pedidos:", err);
    error.value = err;
  } finally {
    isLoading.value = false;
  }
};

const updateStatus = async (orderId, newStatus) => {
  if (!confirm(`Tem certeza que deseja alterar o status para "${newStatus}"?`)) {
    return;
  }

  try {
    const response = await apiClient.patch(`/travel-orders/${orderId}/status`, {
      status: newStatus,
    });

    const index = travelOrders.value.findIndex(o => o.id === orderId);
    if (index !== -1) {
      travelOrders.value[index] = response.data;
    }
    
    alert(`Pedido atualizado para "${newStatus}" com sucesso!`);

  } catch (err) {
    console.error("Erro ao atualizar status:", err);
    const errorMessage = err.response?.data?.message || 'Não foi possível atualizar o status do pedido.';
    alert(`Erro: ${errorMessage}`);
  }
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
  return new Date(dateString).toLocaleDateString('pt-BR', options);
};

const handleLogout = () => {
  authStore.clear();
  router.push({ name: 'Login' });
};

const showDetails = (order) => {
  selectedOrder.value = order;
  isModalVisible.value = true;
};
const closeDetailsModal = () => {
  isModalVisible.value = false;
};

const handleOrderAdded = (newOrder) => {
  fetchTravelOrders();
  isAddModalVisible.value = false;
};

onMounted(() => {
  fetchTravelOrders();
});
</script>

<style scoped>

header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
header h1 { margin: 0; }
header button { padding: 8px 15px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; }
.travel-orders-section { margin-top: 30px; }
.orders-table { width: 100%; border-collapse: collapse; }
.orders-table th, .orders-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
.orders-table th { background-color: #f8f9fa; font-weight: bold; }
.status-badge { padding: 5px 10px; border-radius: 12px; color: white; font-size: 0.9em; text-transform: capitalize; }
.status-pendente { background-color: #ffc107; color: #333; }
.status-aprovado { background-color: #28a745; } 
.status-cancelado { background-color: #dc3545; }
.loading, .error, .no-orders { text-align: center; padding: 40px; color: #6c757d; font-size: 1.1em; }
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
.add-button { padding: 8px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }

.actions-cell .action-button {
  padding: 5px 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-right: 5px; 
}
.actions-cell .action-button:last-child {
  margin-right: 0;
}
.action-button { background-color: #007bff; color: white; }
.action-button:hover { background-color: #0056b3; }

.btn-approve { background-color: #28a745; }
.btn-approve:hover { background-color: #218838; }

.btn-cancel { background-color: #dc3545; }
.btn-cancel:hover { background-color: #c82333; }
</style>

