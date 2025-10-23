import { defineStore } from 'pinia'
import { api } from 'boot/axios'

export const useAuthStore = defineStore('auth', {
  
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: JSON.parse(localStorage.getItem('user')) || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isAdmin: (state) => state.user && state.user.role === 'admin',
  },

  actions: {
    /**
     * Ação de Login
     * Pega o email/senha, envia para a API, e se der certo,
     * salva o token e os dados do usuário.
     */
    async login(credentials) {
      try {
        const response = await api.post('/login', credentials)
        const token = response.data.access_token

        this.token = token
        localStorage.setItem('token', token)

        api.defaults.headers.common['Authorization'] = `Bearer ${token}`

        await this.fetchUser()
        
        return true 

      } catch (error) {
        this.token = null
        localStorage.removeItem('token')
        console.error('Erro no login:', error)
        return false 
      }
    },

    /**
     * Ação para buscar dados do usuário logado
     * (usaremos a rota GET /api/me)
     */
    async fetchUser() {
      if (!this.token) return

      try {
        const response = await api.get('/me')
        this.user = response.data
        localStorage.setItem('user', JSON.stringify(response.data))
      } catch (error) {
        console.error('Erro ao buscar usuário:', error)

        this.logout() 
      }
    },

    /**
     * Ação de Logout
     */
    async logout() {
      // TODO: Chamar a rota /api/logout do backend

      // Limpa tudo
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      delete api.defaults.headers.common['Authorization']
    },
  },
})