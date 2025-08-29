<template>

  <div v-if="visible" class="modal-overlay" @click="$emit('close')">

    <div class="modal-container" @click.stop>
      <div class="modal-header">
        <h2>Detalhes do Pedido</h2>
        <button @click="$emit('close')" class="close-button">&times;</button>
      </div>
      <div class="modal-body" v-if="order">
        <p><strong>Nome:</strong> {{ order.user.name }}</p> 
        <p><strong>Destino:</strong> {{ order.destination }}</p>
        <p><strong>IDA:</strong> {{ formatDate(order.start_date) }}</p>
        <p><strong>VOLTA:</strong> {{ formatDate(order.end_date) }}</p>
        <p><strong>Status:</strong> <span :class="`status-${order.status}`">{{ order.status }}</span></p>
        <p><strong>Motivo da Viagem:</strong></p>
        <p class="reason">{{ order.reason || 'Não informado.' }}</p>
      </div>
      <div class="modal-footer">
        <button @click="$emit('close')">Fechar</button>
      </div>
    </div>
  </div>
</template>

<script setup>

const props = defineProps({
  order: {
    type: Object,
    default: () => null
  },
  visible: {
    type: Boolean,
    default: false
  }
});


defineEmits(['close']);


const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
  return new Date(dateString).toLocaleDateString('pt-BR', options);
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-container {
  background-color: white;
  padding: 20px 30px;
  border-radius: 8px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
  margin-bottom: 20px;
}

.close-button {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
}

.modal-body p {
  margin: 10px 0;
}

.reason {
  background-color: #f8f9fa;
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #eee;
}

.modal-footer {
  border-top: 1px solid #eee;
  padding-top: 15px;
  margin-top: 20px;
  text-align: right;
}

.status-Pendente {
  color: #ffc107;
}

.status-Aprovado {
  color: #28a745;
}

.status-Cancelado {
  color: #dc3545;
}
</style>
