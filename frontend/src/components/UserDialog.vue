<template>
  <q-dialog v-model="isOpen">
    <q-card style="min-width: 400px">
      <q-card-section>
        <div class="text-h6">{{ isEditing ? 'Editar Usuário' : 'Novo Usuário' }}</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form @submit="saveUser" class="q-gutter-md">
          
          <q-input 
            filled v-model="form.name" label="Nome" 
            lazy-rules :rules="[val => !!val || 'Campo obrigatório']" 
          />
          
          <q-input 
            filled v-model="form.email" label="E-mail" type="email"
            lazy-rules :rules="[val => !!val || 'Campo obrigatório']" 
          />

          <q-input 
            filled v-model="form.password" label="Senha" type="password"
            :rules="isEditing ? [] : [val => !!val || 'Campo obrigatório', val => val.length >= 6 || 'Mínimo 6 caracteres']"
          />

          <q-select 
            filled v-model="form.role" label="Função" 
            :options="['admin', 'user']" 
            emit-value map-options
          />

          <div aling="right">
            <q-btn label="Cancelar" color="primary" flat v-close-popup />
            <q-btn :label="isEditing ? 'Salvar' : 'Criar'" type="submit" color="primary" :loading="saving" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref } from 'vue'
import { api } from 'boot/axios'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const emit = defineEmits(['saved']) 

const isOpen = ref(false)
const isEditing = ref(false)
const saving = ref(false)

const form = ref({
  id: null, name: '', email: '', password: '', role: 'user'
})

const open = (user = null) => {
  if (user) {
    isEditing.value = true
    form.value = { ...user, password: '' } 
  } else {
    isEditing.value = false
    form.value = { id: null, name: '', email: '', password: '', role: 'user' }
  }
  isOpen.value = true
}

const saveUser = async () => {
  saving.value = true
  try {
    if (isEditing.value) {
      await api.put(`/users/${form.value.id}`, form.value)
      $q.notify({ type: 'positive', message: 'Atualizado com sucesso!' })
    } else {
      await api.post('/users', form.value)
      $q.notify({ type: 'positive', message: 'Criado com sucesso!' })
    }
    
    isOpen.value = false
    emit('saved')
  } catch (error) {
    console.error(error)
    const msg = error.response?.data?.message || 'Erro ao salvar.'
    $q.notify({ type: 'negative', message: msg })
  } finally {
    saving.value = false
  }
}

defineExpose({ open })
</script>