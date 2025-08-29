<template>
  <div class="register-container">
    <div class="register-box">
      <h2>Registrar</h2>
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="name">Nome:</label>
          <input type="text" id="name" v-model="name" required />
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Senha:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirmar Senha:</label>
          <input type="password" id="password_confirmation" v-model="password_confirmation" required />
        </div>
        <button type="submit" :disabled="isLoading">Registrar</button>
      </form>
      <p v-if="error" class="error-message">{{ error }}</p>
      <p v-if="successMessage" class="success-message">{{ successMessage }}</p>

      <div class="login-section">
        <p>Já tem uma conta?</p>
        <button @click="goToLogin" class="login-button" :disabled="isLoading">Fazer Login</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '../services/api';

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const isLoading = ref(false);
const error = ref(null);
const successMessage = ref(null);
const router = useRouter();

const handleRegister = async () => {
  isLoading.value = true;
  error.value = null;
  successMessage.value = null;
  try {
    const response = await apiClient.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    });
    successMessage.value = response.data.message || 'Registro realizado com sucesso!';
    name.value = '';
    email.value = '';
    password.value = '';
    password_confirmation.value = '';

  } catch (err) {
    console.error('Erro de registro:', err);
    if (err.response && err.response.data && err.response.data.errors) {
      const errors = err.response.data.errors;
      let errorMessage = '';
      for (const key in errors) {
        errorMessage += errors[key].join(', ') + '\n';
      }
      error.value = errorMessage;
    } else {
      error.value = err.response?.data?.message || 'Erro ao registrar. Tente novamente.';
    }
  } finally {
    isLoading.value = false;
  }
};

const goToLogin = () => {
  router.push({ name: 'Login' });
};
</script>

<style scoped>
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f2f5;
}

.register-box {
  background-color: #fff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

h2 {
  margin-bottom: 25px;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 8px;
  color: #555;
  font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 1em;
}

button[type="submit"] {
  width: 100%;
  padding: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1.1em;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #0056b3;
}

button[type="submit"]:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.error-message {
  color: #dc3545;
  margin-top: 15px;
  font-size: 0.9em;
}

.success-message {
  color: #28a745;
  margin-top: 15px;
  font-size: 0.9em;
}

.login-section {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.login-section p {
  color: #666;
  margin-bottom: 15px;
}

.login-button {
  width: 100%;
  padding: 12px;
  background-color: #6c757d; 
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1.1em;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-button:hover {
  background-color: #5a6268;
}

.login-button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
