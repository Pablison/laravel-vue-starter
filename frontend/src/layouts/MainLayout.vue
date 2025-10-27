<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" />

        <q-toolbar-title> Meu Dashboard </q-toolbar-title>
        
        <span class="q-mr-md" v-if="authStore.user" >
          Olá, {{ authStore.user.name }}
        </span>

        <q-btn flat dense icon="logout" aria-label="SAir" @click="handleLogout" />

      </q-toolbar>
    </q-header>
  </q-layout>
</template>

<script setup>
import { useAuthStore } from 'src/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};


</script>
