<template>
  <div v-if="visible" class="modal-overlay" @click="$emit('close')">
    <div class="modal-container" @click.stop>
      <div class="modal-header">
        <h2>Novo Pedido de Viagem</h2>
        <button @click="$emit('close')" class="close-button">&times;</button>
      </div>
      <form @submit.prevent="submitForm">
        <div class="modal-body">

          <div class="form-group">
            <label for="destination">Destino</label>
            <input type="text" id="destination" v-model="form.destination" required>
          </div>


          <div class="date-group">
            <div class="form-group">
              <label for="start_date">IDA</label>
              <input type="date" id="start_date" v-model="form.start_date" required>
            </div>
            <div class="form-group">
              <label for="end_date">VOLTA</label>
              <input type="date" id="end_date" v-model="form.end_date" required>
            </div>
          </div>


          <div class="form-group">
            <label for="reason">Motivo da Viagem</label>
            <textarea id="reason" v-model="form.reason" rows="4" required></textarea>
          </div>

          <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>

        </div>
        <div class="modal-footer">
          <button type="button" @click="$emit('close')" class="btn-secondary">Cancelar</button>
          <button type="submit" :disabled="isLoading" class="btn-primary">
            {{ isLoading ? 'Enviando...' : 'Enviar Pedido' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '../services/api';


defineProps({
  visible: { type: Boolean, default: false }
});
const emit = defineEmits(['close', 'order-added']);


const form = ref({
  destination: '',
  start_date: '',
  end_date: '',
  reason: ''
});


const isLoading = ref(false);
const errorMessage = ref('');


const submitForm = async () => {
  isLoading.value = true;
  errorMessage.value = '';

  try {

    if (new Date(form.value.end_date) < new Date(form.value.start_date)) {
      errorMessage.value = 'A VOLTA não pode ser anterior à IDA.';
      isLoading.value = false;
      return;
    }


    const response = await apiClient.post('/travel-orders', form.value);


    emit('order-added', response.data);


    emit('close');

  } catch (error) {
    console.error('Erro ao criar pedido:', error);
    errorMessage.value = 'Ocorreu um erro ao enviar seu pedido. Verifique os dados e tente novamente.';
  } finally {
    isLoading.value = false;
  }
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
  max-width: 550px;
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

.modal-body {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input,
.form-group textarea {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1em;
}

.date-group {
  display: flex;
  gap: 20px;
}

.date-group .form-group {
  flex: 1;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  border-top: 1px solid #eee;
  padding-top: 15px;
  margin-top: 20px;
}

.modal-footer button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:disabled {
  background-color: #a0c7e4;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.error-message {
  color: #dc3545;
  text-align: center;
  margin-top: 10px;
}
</style>
