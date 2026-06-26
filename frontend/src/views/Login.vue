<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"
import api from "../services/api"

const router = useRouter()
const email = ref("")
const password = ref("")

async function login() {
  try {
    const res = await api.post("/login", {
      email: email.value,
      password: password.value
    })

    localStorage.setItem("token", res.data.token)
    alert("Login berhasil")
    router.push("/")
  } catch (error) {
    alert("Login gagal, cek email dan password")
  }
}
</script>

<template>
  <div class="page">
    <div class="card">
      <div class="brand">
        <span class="brand-mark">⚡</span>
        <span>Bali Auction</span>
      </div>
      <h1>Masuk ke akun Anda</h1>
      <p>Selamat datang kembali, silakan login untuk lanjut ikut lelang.</p>

      <div class="form-group">
        <label>Email</label>
        <input v-model="email" placeholder="contoh@email.com" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <input v-model="password" type="password" placeholder="********" />
      </div>

      <button @click="login">Masuk</button>

      <router-link to="/register" class="link">Belum punya akun? Daftar</router-link>
    </div>
  </div>
</template>

<style scoped>
.page {
  min-height: calc(100vh - 120px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 0;
}

.card {
  width: 100%;
  max-width: 430px;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  padding: 30px;
  border-radius: 24px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
}

.brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 12px;
}

.brand-mark {
  width: 38px;
  height: 38px;
  display: grid;
  place-items: center;
  border-radius: 12px;
  background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
  color: white;
  font-size: 16px;
}

h1 {
  margin: 0 0 8px;
  font-size: 24px;
  color: #0f172a;
}

p {
  margin: 0 0 20px;
  color: #64748b;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 14px;
}

label {
  font-weight: 600;
  color: #334155;
}

input {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 12px 14px;
  font-size: 14px;
  background: #fff;
}

button {
  width: 100%;
  margin-top: 8px;
  padding: 12px;
  border: 0;
  border-radius: 12px;
  background: linear-gradient(135deg, #4c1d95 0%, #7c3aed 100%);
  color: white;
  font-weight: 700;
  cursor: pointer;
}

.link {
  display: block;
  margin-top: 14px;
  text-align: center;
  color: #6d28d9;
  text-decoration: none;
  font-weight: 600;
}
</style>