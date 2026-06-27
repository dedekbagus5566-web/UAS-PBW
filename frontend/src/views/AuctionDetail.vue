<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()

const auctionId = route.params.id

const auction = ref(null)
const bids = ref([])

const bidAmount = ref('')
const bidLoading = ref(false)
const bidMessage = ref('')

const loading = ref(true)
const error = ref('')

const timeLeft = ref('')
let timer = null

function back() {
  router.push('/')
}

function formatPrice(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(value || 0)
}

// =====================
// LOAD AUCTION
// =====================
async function loadAuction() {
  loading.value = true
  error.value = ''

  try {
    const res = await api.get(`/auctions/${auctionId}`)
    auction.value = res.data?.data || null

    clearInterval(timer)
    startCountdown()

  } catch (e) {
    console.error(e)
    error.value = 'Gagal memuat detail lelang.'
  } finally {
    loading.value = false
  }
}

// =====================
// LOAD BIDS
// =====================
async function getBids() {
  try {
    const res = await api.get(`/auctions/${auctionId}/bids`)
    bids.value = res.data.data || []
  } catch (e) {
    console.error(e)
  }
}

// =====================
// COUNTDOWN
// =====================
function startCountdown() {
  if (!auction.value?.ends_at) return

  const endTime = new Date(auction.value.ends_at).getTime()

  timer = setInterval(() => {
    const now = new Date().getTime()
    const diff = endTime - now

    if (diff <= 0) {
      timeLeft.value = "⛔ Waktu Habis"
      clearInterval(timer)
      closeAuction()
      return
    }

    const hours = Math.floor(diff / (1000 * 60 * 60))
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((diff % (1000 * 60)) / 1000)

    timeLeft.value = `${hours}j ${minutes}m ${seconds}d`
  }, 1000)
}

// =====================
// CLOSE AUCTION
// =====================
async function closeAuction() {
  try {
    await api.post(`/auctions/${auctionId}/close`)
    await loadAuction()
    await getBids()
  } catch (e) {
    console.error(e)
  }
}

// =====================
// SUBMIT BID
// =====================
async function submitBid() {
  if (!auction.value || auction.value.status !== 'active') {
    bidMessage.value = 'Lelang sudah ditutup'
    return
  }

  bidLoading.value = true
  bidMessage.value = ''

  try {
    await api.post(`/auctions/${auctionId}/bid`, {
      amount: bidAmount.value
    })

    bidMessage.value = 'Bid berhasil'
    bidAmount.value = ''

    await loadAuction()
    await getBids()

  } catch (e) {
    console.error(e)
    bidMessage.value = e.response?.data?.message || 'Bid gagal'
  } finally {
    bidLoading.value = false
  }
}

onMounted(() => {
  loadAuction()
  getBids()
})

onUnmounted(() => {
  clearInterval(timer)
})
</script>

<template>
  <div class="page">

    <div class="card">

      <button class="back-btn" @click="back">
        ← Kembali
      </button>

      <div v-if="loading" class="state">
        Memuat detail lelang...
      </div>

      <div v-else-if="error" class="state error">
        {{ error }}
      </div>

      <div v-else-if="auction">

        <!-- HEADER -->
        <div class="header">
          <div class="badge">Lelang</div>
          <h1>{{ auction?.title }}</h1>
          <p class="subtitle">
            {{ auction?.category }} • {{ auction?.user?.name }}
          </p>
        </div>

        <!-- IMAGE -->
        <img
          class="hero-image"
          :src="auction?.image_url || 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32'"
        />

        <!-- COUNTDOWN -->
        <div class="status-box">
          ⏳ Sisa Waktu: {{ timeLeft }}
        </div>

        <!-- STATUS -->
        <div class="status-box">
          Status: <b>{{ auction?.status }}</b>
        </div>

        <!-- WINNER -->
        <div v-if="auction?.status === 'closed'" class="status-box">
          🏆 Pemenang: {{ auction?.winner?.name || 'Tidak ada' }}
        </div>

        <!-- PRICE -->
        <div class="meta-grid">
          <div class="meta-item">
            <div>Harga Awal</div>
            <b>{{ formatPrice(auction?.price) }}</b>
          </div>

          <div class="meta-item">
            <div>Harga Sekarang</div>
            <b>{{ formatPrice(auction?.current_price) }}</b>
          </div>
        </div>

        <!-- BID FORM -->
        <div class="bid-form">

          <h3>Lakukan Penawaran</h3>

          <input
            type="number"
            v-model="bidAmount"
            placeholder="Masukkan nominal bid"
            :disabled="auction?.status !== 'active'"
          />

          <button
            @click="submitBid"
            :disabled="bidLoading || auction?.status !== 'active'"
          >
            {{ bidLoading ? 'Mengirim...' : 'Bid Sekarang' }}
          </button>

          <p>{{ bidMessage }}</p>

        </div>

        <!-- CLOSE BUTTON -->
        <button
          v-if="auction?.status === 'active'"
          @click="closeAuction"
        >
          Tutup Lelang
        </button>

        <!-- BID LIST -->
        <div class="bid-section">

          <h3>Riwayat Penawaran</h3>

          <div v-if="bids.length === 0" class="empty">
            Belum ada bid
          </div>

          <div v-for="bid in bids" :key="bid.id" class="bid-card">
            <div>
              <b>{{ bid?.user?.name }}</b>
              <p>{{ formatPrice(bid?.amount) }}</p>
            </div>

            <small>
              {{ new Date(bid?.created_at).toLocaleString('id-ID') }}
            </small>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>

<style scoped>
.page {
  display: flex;
  justify-content: center;
  padding: 30px;
}

.card {
  width: 750px;
  background: white;
  padding: 30px;
  border-radius: 20px;
}

.hero-image {
  width: 100%;
  height: 280px;
  object-fit: cover;
  border-radius: 12px;
  margin: 15px 0;
}

.meta-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}

.meta-item {
  background: #f8fafc;
  padding: 10px;
  border-radius: 10px;
}

.status-box {
  margin: 10px 0;
  padding: 10px;
  background: #f0fdf4;
  border-radius: 10px;
}

.bid-form {
  margin-top: 20px;
  padding: 15px;
  background: #f8fafc;
  border-radius: 10px;
}

.bid-card {
  display: flex;
  justify-content: space-between;
  margin-top: 10px;
  background: #f8fafc;
  padding: 10px;
  border-radius: 10px;
}

.back-btn {
  margin-bottom: 10px;
}
</style>