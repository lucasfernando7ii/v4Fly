import { defineStore } from 'pinia';
import { ref, computed } from 'vue';


export const useAuthStore = defineStore('auth', () => {

  const token = ref(null);
  const user = ref(null);

  const isAuthenticated = computed(() => !!token.value);
  const isAdmin = computed(() => {

    return user.value && user.value.is_admin === true;
  });

  function init() {
    const storedToken = localStorage.getItem('token');
    const storedUser = localStorage.getItem('user');

    if (storedToken) {
      token.value = storedToken;
    }
    if (storedUser) {
      try {
        user.value = JSON.parse(storedUser);
      } catch (error) {
        console.error('Erro ao carregar dados do usuário do localStorage', error);
        user.value = null;
      }
    }
  }

  /**
   * 
   * @param {string} tokenValue 
   */
  function setToken(tokenValue) {
    token.value = tokenValue;
    localStorage.setItem('token', tokenValue);
  }

  /**
   * 
   * @param {object} userValue - 
   */
  function setUser(userValue) {
    user.value = userValue;
    localStorage.setItem('user', JSON.stringify(userValue));
  }

  function clear() {
    token.value = null;
    user.value = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  }
  return {
    // State
    token,
    user,
    // Getters
    isAuthenticated,
    isAdmin, 
    // Actions
    init,
    setToken,
    setUser,
    clear,
  };
});
