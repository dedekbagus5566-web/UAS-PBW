<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const auctions = ref([])
const loading = ref(false)
const error = ref('')

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(value)
}

async function loadMyAuctions() {
  loading.value = true
  error.value = ''

  try {
    const res = await api.get('/my-auctions')
    auctions.value = Array.isArray(res.data?.data) ? res.data.data : []
  } catch (e) {
    error.value = 'Gagal memuat lelang Anda.'
    console.error(e)
  } finally {
    loading.value = false
  }
}

function goToCreate() {
  router.push('/create')
}

onMounted(() => {
  loadMyAuctions()
})
</script>

<template>
  <div class="page">
    <div class="header">
      <div>
        <p class="eyebrow">Dashboard</p>
        <h1>Lelang Saya</h1>
      </div>
      <button class="primary-btn" @click="goToCreate">+ Buat Lelang</button>
    </div>

    <div v-if="loading" class="state">Memuat lelang Anda...</div>
    <div v-else-if="error" class="state error">{{ error }}</div>
    <div v-else-if="auctions.length === 0" class="state">Belum ada lelang yang Anda buat.</div>
    <div v-else class="card-list">
      <article v-for="item in auctions" :key="item.id" class="card">
        <div class="card-top">
          <span class="badge">{{ item.category }}</span>
          <span class="status">{{ item.status || 'active' }}</span>
        </div>
        <h3>{{ item.title }}</h3>
        <p>{{ item.description || 'Tidak ada deskripsi.' }}</p>
        <div class="meta">
          <span>{{ formatPrice(item.price) }}</span>
          <span>👥 {{ item.bid_count || 0 }} bid</span>
        </div>
      </article>
    </div>
  </div>
</template>

<style scoped>
.page {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.eyebrow {
  margin: 0 0 4px;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.16em;
  color: #64748b;
  font-weight: 700;
}

h1 {
  margin: 0;
  font-size: 24px;
  color: #0f172a;
}

.primary-btn {
  border: 0;
  background: linear-gradient(90deg, #7c3aed, #4f46e5);
  color: white;
  padding: 10px 14px;
  border-radius: 999px;
  font-weight: 700;
  cursor: pointer;
}

.state {
  background: white;
  padding: 18px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  color: #334155;
}

.state.error {
  color: #b91c1c;
  background: #fef2f2;
}

.card-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 14px;
}

.card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 16px;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.badge {
  background: #ede9fe;
  color: #6d28d9;
  padding: 4px 8px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
}

.status {
  font-size: 11px;
  color: #16a34a;
  font-weight: 700;
  text-transform: uppercase;
}

h3 {
  margin: 0 0 6px;
  font-size: 16px;
}

p {
  margin: 0 0 12px;
  color: #64748b;
  font-size: 14px;
}

.meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
  font-weight: 700;
  color: #0f172a;
}
</style>
