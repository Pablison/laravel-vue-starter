<template>
    <q-page padding>
        <div class="row items-center q-mb-md">
            <div class="text-h4">Gerenciar Usuários</div>
            <q-space /><q-btn v-if="authStore.isAdmin" color="primary" icon="add" label="Novo Usuário"
                @click="openDialog()" />
        </div>

        <q-table :rows="rows" :columns="columns" row-key="id" :loading="loading">
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <div v-if="authStore.isAdmin">
                        <q-btn flat round color="primary" icon="edit" size="sm" @click="openDialog(props.row)" />
                        <q-btn flat round color="negative" icon="delete" size="sm" @click="deleteUser(props.row)" />
                    </div>
                    <div v-else class="text-grey-6 text-caption">
                        Visualizar apenas
                    </div>
                </q-td>
            </template>
        </q-table>

        <UserDialog ref="userDialogRef" @saved="fetchUsers" />

    </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'
import { useQuasar } from 'quasar'
import UserDialog from 'components/UserDialog.vue'
import { useAuthStore } from 'stores/auth'

const $q = useQuasar()
const rows = ref([])
const loading = ref(false)
const userDialogRef = ref(null)
const authStore = useAuthStore()

const columns = [
    { name: 'id', align: 'left', label: 'ID', field: 'id', sortable: true },
    { name: 'name', align: 'left', label: 'Nome', field: 'name', sortable: true },
    { name: 'email', align: 'left', label: 'E-mail', field: 'email', sortable: true },
    { name: 'role', align: 'left', label: 'Função', field: 'role', sortable: true },
    { name: 'actions', align: 'center', label: 'Ações', field: 'actions' }
]

const fetchUsers = async () => {
    loading.value = true
    try {
        const response = await api.get('/users')
        rows.value = response.data
    } catch (error) {
        console.error(error)
        $q.notify({ type: 'negative', message: 'Erro ao carregar usuários.' })
    } finally {
        loading.value = false
    }
}

const openDialog = (user = null) => {
    userDialogRef.value.open(user)
}

const deleteUser = (user) => {
    $q.dialog({
        title: 'Confirmar',
        message: `Tem certeza que deseja excluir ${user.name}?`,
        cancel: true,
        persistent: true
    }).onOk(async () => {
        try {
            await api.delete(`/users/${user.id}`)
            $q.notify({ type: 'positive', message: 'Usuário excluído.' })
            fetchUsers()
        } catch (error) {
            console.error(error)
            $q.notify({ type: 'negative', message: 'Erro ao excluir.' })
        }
    })
}

onMounted(() => {
    fetchUsers()
})
</script>