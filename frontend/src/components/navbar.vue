<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

function logout() {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>

<template>
  <nav class="navbar">

    <div class="logo">🏛️ Auction Market</div>

    <div class="menu">

      <router-link to="/" class="link">Home</router-link>
      <router-link to="/create" class="link">Create</router-link>

      <!-- 🔥 LOGIN / LOGOUT LOGIC -->
      <template v-if="!localStorage.getItem('token')">
        <router-link to="/login" class="btn-login">Login</router-link>
        <router-link to="/register" class="btn-register">Register</router-link>
      </template>

      <button v-else class="btn-logout" @click="logout">
        Logout
      </button>

    </div>

  </nav>
</template>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  padding: 15px 20px;
  background: #111;
  color: white;
  align-items: center;
}

.menu {
  display: flex;
  gap: 10px;
  align-items: center;
}

.link {
  color: white;
  text-decoration: none;
}

.btn-login, .btn-register {
  padding: 6px 10px;
  border-radius: 6px;
  text-decoration: none;
}

.btn-login { background: #3498db; color: white; }
.btn-register { background: #2ecc71; color: white; }

.btn-logout {
  background: red;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
}
</style>