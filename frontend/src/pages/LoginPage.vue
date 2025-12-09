<template>
  <div class="login-page-background relative-position overflow-hidden flex flex-center">
    <!-- Elementos de Fundo Animados (Blobs) -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <!-- Container Principal com Efeito Glass (Vidro) -->
    <div class="login-container relative-position z-top">
      
      <!-- Cabeçalho -->
      <div class="text-center q-mb-xl">
        <div class="text-h4 text-weight-bold text-white q-mb-sm tracking-tight">
          Bem-vindo de volta
        </div>
        <div class="text-grey-5 text-subtitle2">
          Insira suas credenciais para acessar sua conta
        </div>
      </div>

      <!-- Card Glassmorphism -->
      <q-card flat class="login-card glass-effect q-pa-lg">
        <q-form @submit.prevent="handleLogin" class="q-gutter-y-md">
          
          <!-- Input Email -->
          <div>
            <label class="text-grey-4 text-caption q-mb-xs block q-pl-xs">Email</label>
            <q-input
              dense
              outlined
              v-model="email"
              type="email"
              placeholder="nome@exemplo.com"
              class="glass-input"
              dark
              color="primary"
              :rules="[(val) => !!val || 'O e-mail é obrigatório']"
              hide-bottom-space
            >
              <template v-slot:prepend>
                <q-icon name="email" color="grey-5" />
              </template>
            </q-input>
          </div>

          <!-- Input Senha -->
          <div>
            <label class="text-grey-4 text-caption q-mb-xs block q-pl-xs">Senha</label>
            <q-input
              dense
              outlined
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="••••••••"
              class="glass-input"
              dark
              color="primary"
              :rules="[(val) => !!val || 'A senha é obrigatória']"
              hide-bottom-space
            >
              <template v-slot:prepend>
                <q-icon name="lock" color="grey-5" />
              </template>
              <template v-slot:append>
                <q-icon
                  :name="showPassword ? 'visibility_off' : 'visibility'"
                  class="cursor-pointer"
                  color="grey-5"
                  @click="showPassword = !showPassword"
                />
              </template>
            </q-input>
          </div>

          <!-- Opções (Manter conectado / Esqueci senha) -->
          <div class="row items-center justify-between q-mt-sm">
            <q-checkbox
              v-model="staySignedIn"
              label="Lembrar de mim"
              dense
              size="sm"
              dark
              color="primary"
              class="text-grey-5"
            />
            <a href="#" class="text-primary text-caption text-weight-medium no-decoration hover-underline">
              Esqueceu a senha?
            </a>
          </div>

          <!-- Botão Login -->
          <q-btn
            type="submit"
            class="full-width q-mt-md gradient-btn shadow-custom"
            size="lg"
            rounded
            unelevated
            :loading="loading"
          >
            <span class="text-capitalize text-weight-bold">Entrar no Sistema</span>
            <q-icon name="arrow_forward" size="xs" class="q-ml-sm" />
          </q-btn>
        </q-form>

        <!-- Rodapé -->
        <div class="text-center q-mt-lg pt-4 border-top-glass">
          <span class="text-grey-5 text-caption">Não tem uma conta? </span>
          <a href="#" class="text-primary text-caption text-weight-bold no-decoration">
            Registre-se agora
          </a>
        </div>
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
const quasar = useQuasar();
const authStore = useAuthStore();

const email = ref("");
const password = ref("");
const showPassword = ref(false); // Adicionado para o toggle de senha
const staySignedIn = ref(false);
const loading = ref(false);

const handleLogin = async () => {
  if (!email.value || !password.value) {
    quasar.notify({
      type: 'negative',
      message: 'Por favor, preencha todos os campos',
      position: 'top'
    });
    return;
  }

  loading.value = true;
  try {
    await authStore.login({
      email: email.value,
      password: password.value
    });
    router.replace('/');
    quasar.notify({
      type: 'positive',
      message: 'Login realizado com sucesso!',
      position: 'top',
    });
  } catch (error) {
    quasar.notify({
      type: 'negative',
      message: 'Erro no login: Credenciais inválidas.',
      position: 'top',
    });
    console.error('Erro no login:', error);
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Fundo Geral */
.login-page-background {
  min-height: 100vh;
  background-color: #0f172a; /* Slate 900 */
  width: 100%;
}

.login-container {
  width: 100%;
  max-width: 450px;
  padding: 20px;
}

/* Efeito Glassmorphism no Card */
.glass-effect {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
  border-radius: 16px;
}

/* Estilização Customizada dos Inputs do Quasar para ficarem "Glass" */
:deep(.glass-input .q-field__control) {
  background: rgba(30, 41, 59, 0.5) !important; /* Slate 800 com opacidade */
  border: 1px solid rgba(71, 85, 105, 0.5);
  border-radius: 8px;
  transition: all 0.3s ease;
}

:deep(.glass-input .q-field__control:hover) {
  border-color: rgba(99, 102, 241, 0.5); /* Indigo suave no hover */
}

:deep(.glass-input.q-field--focused .q-field__control) {
  border-color: #3b82f6; /* Blue 500 no foco */
  box-shadow: 0 0 0 1px #3b82f6;
  background: rgba(30, 41, 59, 0.8) !important;
}

:deep(.glass-input .q-field__native),
:deep(.glass-input .q-field__prefix),
:deep(.glass-input .q-field__suffix),
:deep(.glass-input .q-field__input) {
  color: white !important;
}

/* Botão Gradiente */
.gradient-btn {
  background: linear-gradient(to right, #2563eb, #4f46e5); /* Blue to Indigo */
  transition: transform 0.2s, box-shadow 0.2s;
}

.gradient-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
}

.text-primary {
  color: #60a5fa !important; /* Blue 400 mais claro para dark mode */
}

.no-decoration {
  text-decoration: none;
}

.hover-underline:hover {
  text-decoration: underline;
}

.border-top-glass {
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Animação dos Blobs de Fundo */
.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.4;
  animation: float 7s infinite;
}

.blob-1 {
  top: -10%;
  left: -10%;
  width: 400px;
  height: 400px;
  background: #2563eb; /* Blue */
  animation-delay: 0s;
}

.blob-2 {
  top: -10%;
  right: -10%;
  width: 400px;
  height: 400px;
  background: #9333ea; /* Purple */
  animation-delay: 2s;
}

.blob-3 {
  bottom: -20%;
  left: 20%;
  width: 400px;
  height: 400px;
  background: #4f46e5; /* Indigo */
  animation-delay: 4s;
}

@keyframes float {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
</style>