<template>
  <div class="login-page-background flex flex-center">
    <div class="login-container q-pa-lg">
      <div class="text-h4 text-weight-bold text-center text-white q-mb-md">
        Log In
      </div>
      <div class="text-center text-grey-4 q-mb-xl">
        Ainda não tem uma conta? <a href="#" class="text-primary text-weight-bold">Crie aqui</a>
      </div>

      <q-card flat class="login-card q-pa-md">
        <q-form @submit.prevent="handleLogin">
          <div class="q-mb-md">
            <label class="text-grey-7 q-mb-xs block">Email</label>
            <q-input dense outlined v-model="email" type="email" bg-color="white"
              :rules="[(val) => !!val || 'O e-mail é obrigatório']" hide-bottom-space />
          </div>
          <div class="q-mb-md">
            <label class="text-grey-7 q-mb-xs block">Password</label>
            <q-input dense outlined v-model="password" type="password" bg-color="white"
              :rules="[(val) => !!val || 'A senha é obrigatória']" hide-bottom-space />
          </div>
          <div class="flex items-center justify-between q-mt-md q-mb-lg">
            <q-checkbox v-model="staySignedIn" label="Continuar logado" dense size="sm" color="primary" />
            <a href="#" class="text-primary text-caption">Esqueceu sua senha?</a>
          </div>
          <q-btn label="Login" type="submit" color="primary" class="full-width text-capitalize" size="lg" rounded
            :loading="loading" />
        </q-form>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "stores/auth";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";

const router = useRouter();
const quasar = useQuasar()
const authStore = useAuthStore();

const email = ref("");
const password = ref("");
const staySignedIn = ref(false);
const loading = ref(false);

const handleLogin = async () => {
  if (!email.value || !password.value) {
    quasar.Notify({
      type: 'negative',
      message: 'Por favor, preencha todos os campos',
      position: 'top'
    })
    return
  }

  loading.value = true;
  try {
    await authStore.login({
      email: email.value,
      password: password.value
    });
    router.replace('/')
    quasar.notify({
      type: 'positive',
      message: 'Login realizado com sucesso!',
      position: 'top',
    })
  } catch (error) {
    quasar.notify({
      type: 'negative',
      message: 'Erro no login: Credenciais inválidas.',
      position: 'top',
    })
    console.error('Erro no login:', error)
  } finally {
    loading.value = false
  }
};
</script>

<style scoped>
.login-page-background {
  min-height: 100vh;
  background: #1a1a2e;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.login-container {
  max-width: 400px;
  width: 100%;
}

.login-card {
  background: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.q-field--outlined .q-field__control {
  border-radius: 4px;
}

.q-input--dense .q-field__control,
.q-input--dense .q-field__append {
  height: 48px;
}

.text-primary {
  color: #6a35ff !important;
}

.q-checkbox {
  color: #a0a0a0;
}
</style>
