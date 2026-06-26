<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()
const auction = ref(null)
const loading = ref(true)
const error = ref('')

function back() {
  router.push('/')
}

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(value)
}

async function loadAuction() {
  loading.value = true
  error.value = ''

  try {
    const res = await api.get(`/auctions/${route.params.id}`)
    auction.value = res.data?.data || null
  } catch (e) {
    error.value = 'Gagal memuat detail lelang.'
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadAuction()
})
</script>

<template>
  <div class="page">
    <div class="card">
      <button class="back-btn" @click="back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Kembali
      </button>

      <div v-if="loading" class="state">Memuat detail lelang...</div>
      <div v-else-if="error" class="state error">{{ error }}</div>
      <div v-else-if="auction" class="content">
        <div class="header">
          <div class="badge">Lelang</div>
          <h1>{{ auction.title }}</h1>
          <p class="subtitle">{{ auction.category }} • {{ auction.user?.name || 'Penjual' }}</p>
        </div>

        <div class="hero-image">
          <img :src="auction.image_url || 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32'" :alt="auction.title" />
        </div>

        <div class="meta-grid">
          <div class="meta-item">
            <span class="meta-label">Harga Awal</span>
            <span class="meta-value">{{ formatPrice(auction.price) }}</span>
          </div>
          <div class="meta-item">
            <span class="meta-label">Status</span>
            <span class="status-pill">Aktif</span>
          </div>
        </div>

        <div class="divider" />

        <div class="description">
          <h2>Deskripsi</h2>
          <p>{{ auction.description || 'Tidak ada deskripsi tambahan.' }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page {
  min-height: calc(100vh - 120px);
  display: grid;
  place-items: center;
  padding: 8px 0;
}

.card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  padding: 32px;
  border-radius: 24px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
  max-width: 720px;
  width: 100%;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: none;
  border: 1px solid #e2e8f0;
  color: #64748b;
  font-size: 13px;
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  margin-bottom: 22px;
}

.header {
  margin-bottom: 18px;
}

.badge {
  display: inline-block;
  background: #eff6ff;
  color: #3b82f6;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 4px 10px;
  border-radius: 6px;
  margin-bottom: 12px;
}

h1 {
  font-size: 24px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 6px;
}

.subtitle {
  font-size: 14px;
  color: #64748b;
  margin: 0;
}

.hero-image {
  margin: 16px 0 18px;
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
}

.hero-image img {
  width: 100%;
  height: 240px;
  object-fit: cover;
  display: block;
}

.meta-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 20px;
}

.meta-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 14px 16px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.meta-label {
  font-size: 11px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #94a3b8;
}

.meta-value {
  font-size: 15px;
  font-weight: 700;
  color: #0f172a;
}

.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 600;
  color: #16a34a;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  padding: 4px 10px;
  border-radius: 999px;
  width: fit-content;
}

.divider {
  height: 1px;
  background: #f1f5f9;
  margin: 8px 0 18px;
}

.description h2 {
  font-size: 16px;
  margin: 0 0 8px;
  color: #111827;
}

.description p {
  margin: 0;
  color: #475569;
  line-height: 1.7;
}

.state {
  padding: 18px;
  border-radius: 14px;
  background: #f8fafc;
  color: #334155;
}

.state.error {
  color: #b91c1c;
  background: #fef2f2;
}
</style>