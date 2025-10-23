<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex flex-center bg-grey-2">
        <q-card style="width: 400px">
          <q-card-section class="q-pa-md">
            <div class="text-h6 text-center">Login</div>
          </q-card-section>

          <q-card-section>
            <q-form @submit.prevent="handleLogin">
              <q-input
                v-model="email"
                label="E-mail"
                type="email"
                lazy-rules
                :rules="[(val) => !!val || 'O e-mail é obrigatório']"
              />

              <q-input
                v-model="password"
                label="Senha"
                type="password"
                lazy-rules
                :rules="[(val) => !!val || 'A senha é obrigatória']"
              />

              <div class="q-mt-md">
                <q-btn label="Entrar" type="submit" color="primary" class="full-width" />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from "vue";
import { useAuthStore } from "stores/auth";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";

const router = useRouter();
const $q = useQuasar();
const authStore = useAuthStore();

const email = ref("");
const password = ref("");

const handleLogin = async () => {
  const success = await authStore.login({
    email: email.value,
    password: password.value,
  });

  if (success) {
    router.push("/");
  } else {
    $q.notify({
      color: "negative",
      position: "top",
      message: "E-mail ou senha inválidos",
    });
  }
};
</script>
