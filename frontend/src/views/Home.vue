<script setup>
import { computed, onMounted, ref } from "vue"
import { useRouter } from "vue-router"
import api from "../services/api"

const router = useRouter()
const selectedCategory = ref("Semua")
const auctions = ref([])
const loading = ref(false)
const error = ref("")

const categories = [
  { name: "Elektronik", icon: "📱", count: "24 item" },
  { name: "Kendaraan", icon: "🏍️", count: "12 item" },
  { name: "Seni", icon: "🎨", count: "9 item" },
  { name: "Fashion", icon: "👗", count: "18 item" }
]

const filteredAuctions = computed(() => {
  if (selectedCategory.value === "Semua") return auctions.value
  return auctions.value.filter((item) => item.category === selectedCategory.value)
})

function formatPrice(value) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0
  }).format(value)
}

function goToCreate() {
  router.push("/create")
}

function goToDetail(id) {
  router.push(`/auction/${id}`)
}

function scrollToSection(id) {
  document.getElementById(id)?.scrollIntoView({ behavior: "smooth" })
}

function selectCategory(category) {
  selectedCategory.value = category
  scrollToSection("auction-section")
}

async function loadAuctions() {
  loading.value = true
  error.value = ""

  try {
    const res = await api.get("/auctions")
    const items = Array.isArray(res.data?.data) ? res.data.data : []

    auctions.value = items.map((item) => ({
      id: item.id,
      title: item.title,
      category: item.category,
      price: formatPrice(item.price),
      bid: item.bid_count || 0,
      timeLeft: item.time_left || "Segera",
      image: item.image_url || "https://images.unsplash.com/photo-1516035069371-29a1b244cc32"
    }))
  } catch (e) {
    error.value = "Gagal memuat lelang dari server."
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadAuctions()
})
</script>

<template>
  <div class="layout">
    <main class="main-content">
      <header class="topbar">
        <div class="search-wrap">
          <span class="search-icon">🔎</span>
          <input placeholder="Cari lelang menarik..." />
        </div>
        <div class="notif-btn">
          <span>🔔</span>
          <div class="notif-dot"></div>
        </div>
      </header>

      <section class="hero">
        <div class="hero-copy">
          <p class="eyebrow">Live bidding • real-time</p>
          <h1>Temukan barang impian<br />dengan harga terbaik</h1>
          <p class="hero-sub">
            Ikuti lelang favorit Anda dan dapatkan akses ke barang langka dengan penawaran real-time yang aman dan cepat.
          </p>
          <div class="hero-actions">
            <button class="btn-primary" @click="goToCreate">Mulai Bid</button>
            <button class="btn-ghost" @click.prevent="scrollToSection('categories-section')">Lihat kategori</button>
          </div>
          <div class="hero-badges">
            <span>⚡ Cepat</span>
            <span>🔐 Aman</span>
            <span>📦 Langka</span>
          </div>
        </div>

        <div class="hero-stats">
          <div class="stat-card highlight">
            <p class="stat-label">Hari ini</p>
            <strong>1.2k+</strong>
            <span>Lelang aktif</span>
          </div>
          <div class="stat-card">
            <p class="stat-label">Kepercayaan</p>
            <strong>98%</strong>
            <span>Transaksi aman</span>
          </div>
        </div>
      </section>

      <section id="categories-section" class="categories-section">
        <div class="section-head">
          <div>
            <p class="section-kicker">Kategori</p>
            <h2 class="section-title">Kategori populer</h2>
          </div>
          <span class="section-chip chip-purple">4 opsi tersedia</span>
        </div>
        <div class="category-list">
          <button
            v-for="cat in categories"
            :key="cat.name"
            class="category-item"
            :class="{ active: selectedCategory === cat.name }"
            @click="selectCategory(cat.name)"
          >
            <span class="category-icon">{{ cat.icon }}</span>
            <strong>{{ cat.name }}</strong>
            <small>{{ cat.count }}</small>
          </button>
        </div>
      </section>

      <section class="auction-section" id="auction-section">
        <div class="section-head">
          <div>
            <p class="section-kicker">Live</p>
            <h2 class="section-title">Lelang aktif</h2>
          </div>
          <span class="section-chip chip-amber">Update real-time</span>
        </div>

        <div v-if="loading" class="state-card">Memuat lelang...</div>
        <div v-else-if="error" class="state-card error">{{ error }}</div>
        <div v-else-if="filteredAuctions.length === 0" class="state-card">Belum ada lelang untuk kategori ini.</div>
        <div v-else class="cards">
          <article v-for="a in filteredAuctions" :key="a.id" class="auction-card">
            <div class="card-img">
              <img :src="a.image + '?w=400&h=200&fit=crop'" :alt="a.title" />
              <span class="card-cat-tag">{{ a.category }}</span>
              <button class="card-fav">♥</button>
            </div>
            <div class="card-body">
              <div class="card-title">{{ a.title }}</div>
              <div class="card-meta">
                <span class="card-price">{{ a.price }}</span>
                <span class="bid-count">👥 {{ a.bid }} bid</span>
              </div>
              <div class="countdown">🕐 <span>Berakhir {{ a.timeLeft }}</span></div>
              <button class="card-bid-btn" @click="goToDetail(a.id)">Lihat Detail</button>
            </div>
          </article>
        </div>
      </section>
    </main>
  </div>
</template>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Inter:wght@400;500;600&display=swap");

/* ── Layout ── */
.layout {
  min-height: 100%;
  background: transparent;
  font-family: "Inter", sans-serif;
}

.state-card {
  padding: 20px;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  color: #334155;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
}

.state-card.error {
  color: #b91c1c;
  border-color: #fecaca;
  background: #fef2f2;
}

.main-content {
  display: flex;
  flex-direction: column;
  gap: 22px;
  max-width: 1400px;
  width: 100%;
  box-sizing: border-box;
}

.topbar {
  display: flex;
  align-items: center;
  gap: 12px;
}

.search-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 999px;
  padding: 12px 16px;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.search-wrap input {
  border: 0;
  outline: 0;
  width: 100%;
  font-size: 14px;
  background: transparent;
  color: #0f172a;
}

.search-icon {
  font-size: 15px;
  flex-shrink: 0;
}

.notif-btn {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #fff;
  border: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  position: relative;
  flex-shrink: 0;
  font-size: 16px;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.notif-dot {
  position: absolute;
  top: 8px;
  right: 9px;
  width: 7px;
  height: 7px;
  background: #f59e0b;
  border-radius: 50%;
  border: 1.5px solid #fff;
}

.hero {
  background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
  border-radius: 24px;
  padding: 28px;
  display: grid;
  grid-template-columns: 1.3fr 0.7fr;
  gap: 20px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
}

.hero::after {
  content: "";
  position: absolute;
  right: -50px;
  top: -50px;
  width: 240px;
  height: 240px;
  border-radius: 50%;
  background: rgba(245, 158, 11, 0.14);
  pointer-events: none;
}

.eyebrow {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.2em;
  color: #cbd5e1;
  font-weight: 700;
  margin-bottom: 10px;
}

.hero h1 {
  font-family: "Space Grotesk", sans-serif;
  font-size: 30px;
  font-weight: 700;
  color: #fff;
  line-height: 1.2;
  margin: 0 0 12px;
  letter-spacing: -0.4px;
}

.hero-sub {
  font-size: 14px;
  color: #cbd5e1;
  line-height: 1.7;
  margin: 0 0 16px;
  max-width: 620px;
}

.hero-actions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 14px;
}

.btn-primary {
  background: linear-gradient(90deg, #f59e0b, #f97316);
  color: #111827;
  border: 0;
  padding: 10px 18px;
  border-radius: 999px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  font-family: "Inter", sans-serif;
}

.btn-ghost {
  background: rgba(255, 255, 255, 0.08);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.16);
  padding: 10px 16px;
  border-radius: 999px;
  font-size: 13px;
  cursor: pointer;
}

.hero-badges {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.hero-badges span {
  background: rgba(255, 255, 255, 0.1);
  color: #f8fafc;
  font-size: 11px;
  padding: 6px 10px;
  border-radius: 999px;
}

.hero-stats {
  display: flex;
  flex-direction: column;
  gap: 12px;
  z-index: 1;
}

.stat-card {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 16px;
  padding: 16px;
}

.stat-card.highlight {
  background: linear-gradient(135deg, rgba(245, 158, 11, 0.24), rgba(249, 115, 22, 0.18));
}

.stat-label {
  font-size: 10px;
  color: #cbd5e1;
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.16em;
}

.stat-card strong {
  font-family: "Space Grotesk", sans-serif;
  font-size: 24px;
  color: #fff;
  font-weight: 700;
  display: block;
}

.stat-card span {
  font-size: 12px;
  color: #cbd5e1;
}

.section-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.section-kicker {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 0.18em;
  color: #64748b;
  font-weight: 700;
  margin-bottom: 2px;
}

.section-title {
  font-family: "Space Grotesk", sans-serif;
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
  letter-spacing: -0.2px;
}

.section-chip {
  font-size: 11px;
  padding: 6px 11px;
  border-radius: 999px;
  font-weight: 700;
}

.chip-purple {
  background: #ede9fe;
  color: #5b21b6;
}

.chip-amber {
  background: #fef3c7;
  color: #92400e;
}

.category-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 10px;
}

.category-item {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 14px 12px;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  gap: 3px;
  transition: all 0.2s ease;
  text-align: left;
}

.category-item:hover {
  border-color: #c4b5fd;
  background: #faf5ff;
  transform: translateY(-1px);
}

.category-item.active {
  border-color: #7c3aed;
  background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
}

.category-icon {
  font-size: 20px;
  margin-bottom: 2px;
}

.category-item strong {
  font-size: 13px;
  font-weight: 700;
  color: #0f172a;
}

.category-item small {
  font-size: 11px;
  color: #64748b;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 14px;
}

.auction-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  overflow: hidden;
  transition: all 0.2s ease;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.auction-card:hover {
  transform: translateY(-2px);
  border-color: #c4b5fd;
}

.card-img {
  height: 150px;
  position: relative;
  overflow: hidden;
  background: #e2e8f0;
}

.card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-cat-tag {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(15, 23, 42, 0.78);
  color: #fff;
  font-size: 10px;
  font-weight: 700;
  padding: 4px 8px;
  border-radius: 999px;
}

.card-fav {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  color: #e11d48;
  cursor: pointer;
  border: 0;
}

.card-body {
  padding: 14px;
}

.card-title {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.card-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
}

.card-price {
  font-family: "Space Grotesk", sans-serif;
  font-size: 15px;
  font-weight: 700;
  color: #0d1b2a;
}

.bid-count {
  font-size: 11px;
  color: #64748b;
}

.countdown {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #fff7ed;
  border-radius: 10px;
  padding: 6px 9px;
  margin-bottom: 10px;
  font-size: 11px;
  color: #92400e;
  font-weight: 700;
}

.card-bid-btn {
  width: 100%;
  background: #111827;
  color: #fff;
  border: 0;
  padding: 9px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s ease;
}

.card-bid-btn:hover {
  background: #1f2937;
}

@media (max-width: 1024px) {
  .hero {
    grid-template-columns: 1fr;
  }

  .hero-stats {
    flex-direction: row;
  }

  .stat-card {
    flex: 1;
  }

  .main-content {
    padding-left: 16px;
    padding-right: 16px;
  }
}

@media (max-width: 600px) {
  .hero h1 {
    font-size: 22px;
  }

  .hero-stats {
    flex-direction: column;
  }
}
</style>