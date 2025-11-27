import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Crie a instância do Axios
const api = axios.create({
  // Esta é a URL base do seu backend Laravel
  baseURL: 'http://localhost:8000/api'
})

const token = localStorage.getItem('token')
if (token) {
  api.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

export default boot(({ app }) => {
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { api }