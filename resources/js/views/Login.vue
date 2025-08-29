<template>
  <div class="login-container">
    <div class="login-box">
      <h2><strong>v4 Fly</strong></h2>
      <p>Acesse sua conta:</p>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="form-group">
          <label for="password">Senha:</label>
          <input type="password" id="password" v-model="password" required />
        </div>
        <button type="submit" :disabled="isLoading">Entrar</button>
      </form>
      <p v-if="error" class="error-message">{{ error }}</p>
      <div class="register-section">
        <p>Não tem uma conta?</p>
        <button @click="goToRegister" class="register-button" :disabled="isLoading">Registrar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';
import apiClient from '../services/api';

const email = ref('');
const password = ref('');
const isLoading = ref(false);
const error = ref(null);
const router = useRouter();
const authStore = useAuthStore();

const handleLogin = async () => {
  isLoading.value = true;
  error.value = null;
  try {
    const response = await apiClient.post('/login', {
      email: email.value,
      password: password.value,
    });
    authStore.setToken(response.data.access_token);
    authStore.setUser(response.data.user);
    router.push({ name: 'Dashboard' });
  } catch (err) {
    console.error('Erro de login:', err);
    error.value = err.response?.data?.message || 'Credenciais inválidas. Tente novamente.';
  } finally {
    isLoading.value = false;
  }
};

const goToRegister = () => {
  router.push({ name: 'Register' });
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f2f5;
}

.login-box {
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

.register-section {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.register-section p {
  color: #666;
  margin-bottom: 15px;
}

.register-button {
  width: 100%;
  padding: 12px;
  background-color: #28a745; 
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 1.1em;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.register-button:hover {
  background-color: #218838;
}

.register-button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}
</style>
