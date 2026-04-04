<template>
  <div style="max-width: 400px; margin: 100px auto; padding: 20px;">

    <!-- Login Form -->
    <div v-if="step === 'login'">
      <h2>POS Login</h2>
      <div v-if="error" style="color: red; margin-bottom: 10px;">{{ error }}</div>
      <input v-model="email"    type="email"    placeholder="Email"    style="display:block; width:100%; margin-bottom:10px; padding:8px;" />
      <input v-model="password" type="password" placeholder="Password" style="display:block; width:100%; margin-bottom:10px; padding:8px;" />
      <button @click="handleLogin" :disabled="loading" style="padding: 10px 20px;">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>
    </div>

    <!-- Outlet Picker -->
    <div v-if="step === 'outlet'">
      <h2>Select Outlet</h2>
      <div
        v-for="o in outlets"
        :key="o.id"
        @click="handleSelectOutlet(o)"
        style="border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 10px; cursor: pointer;"
      >
        {{ o.name }}
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useSessionStore } from '@/stores/session'

const session  = useSessionStore()
const router   = useRouter()

const step     = ref('login')
const outlets  = ref([])
const email    = ref('')
const password = ref('')
const loading  = ref(false)
const error    = ref('')

async function handleLogin() {
  loading.value = true
  error.value   = ''

  try {
    const result = await session.login(email.value, password.value)

    if (result.length === 1) {
      // Only one outlet — skip picker
      await session.selectOutlet(result[0])
      router.push('/')
    } else {
      // Multiple outlets — show picker
      outlets.value = result
      step.value    = 'outlet'
    }
  } catch (e) {
    error.value = e.response?.data?.message ?? 'Login failed'
  } finally {
    loading.value = false
  }
}

async function handleSelectOutlet(o) {
  await session.selectOutlet(o)
  router.push('/')
}
</script>
