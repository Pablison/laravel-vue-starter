import { boot } from 'quasar/wrappers'
import axios from 'axios'

// Crie a instância do Axios
const api = axios.create({
  // Esta é a URL base do seu backend Laravel
  baseURL: 'http://localhost:8000/api'
})

export default boot(({ app }) => {
  // Para uso dentro de arquivos .js (como as stores do Pinia)
  app.config.globalProperties.$axios = axios
  
  // Para uso dentro de componentes Vue (.vue files) com this.$api
  app.config.globalProperties.$api = api
})

// Exporte a instância 'api' para podermos usá-la em qualquer lugar
export { api }