<script setup>
defineProps({
  data: Object
})
</script>

<template>
  <article class="card">
    <div class="image-wrap">
      <img :src="data.image" :alt="data.title" />
      <span class="floating-badge">{{ data.category }}</span>
    </div>

    <div class="card-body">
      <div class="meta">
        <span class="bid">{{ data.bid }} bid</span>
        <span class="status">Live</span>
      </div>

      <h3>{{ data.title }}</h3>

      <div class="card-footer">
        <div>
          <span class="label">Harga saat ini</span>
          <p class="price">{{ data.price }}</p>
        </div>
        <router-link :to="data.id ? '/auction/' + data.id : '/'" class="btn">
          Lihat Detail
        </router-link>
      </div>
    </div>
  </article>
</template>

<style scoped>
.card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: 1px solid #f1f5f9;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 18px 38px rgba(15, 23, 42, 0.12);
}

.image-wrap {
  position: relative;
}

img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.floating-badge {
  position: absolute;
  top: 12px;
  left: 12px;
  background: rgba(255,255,255,0.9);
  color: #6d28d9;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
}

.card-body {
  padding: 16px;
}

.meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.bid {
  color: #64748b;
  font-size: 12px;
}

.status {
  background: #dcfce7;
  color: #166534;
  padding: 4px 8px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
}

h3 {
  margin: 0 0 12px;
  font-size: 18px;
  color: #111827;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}

.label {
  display: block;
  font-size: 11px;
  color: #64748b;
  margin-bottom: 2px;
}

.price {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.btn {
  display: inline-block;
  padding: 10px 14px;
  border-radius: 999px;
  text-decoration: none;
  background: linear-gradient(135deg, #111827 0%, #334155 100%);
  color: white;
  font-size: 13px;
  font-weight: 600;
  white-space: nowrap;
}
</style>