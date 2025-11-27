<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" aria-label="Menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> Meu Dashboard </q-toolbar-title>

        <span class="q-mr-md" v-if="authStore.user">
          Olá, {{ authStore.user.name }}
        </span>

        <q-btn flat dense icon="logout" aria-label="SAir" @click="handleLogout" />

      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header>
          Navegação
        </q-item-label>

        <q-item clickable to="/">
          <q-item-section avatar>
            <q-icon name="dashboard" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Dashboard</q-item-label>
            <q-item-label caption>Página inicial</q-item-label>
          </q-item-section>
        </q-item>

        <q-item v-if="authStore.isAdmin" clickable to="/users">
          <q-item-section avatar>
            <q-icon name="people" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Gerenciar Usuários</q-item-label>
            <q-item-label caption>Apenas Admin</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from 'src/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const leftDrawerOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout();
  router.replace('/login');
};

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

</script>
