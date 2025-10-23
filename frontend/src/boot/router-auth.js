import { boot } from 'quasar/wrappers'
import { useAuthStore } from 'stores/auth' 

export default boot(({ router }) => {

  router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()
    const isAuthenticated = authStore.isAuthenticated

    if (to.meta.requiresAuth && !isAuthenticated) {
      next('/login') 
    }
    else if (to.meta.guest && isAuthenticated) {
      next('/')
    }
    else {
      next()
    }
  })
})