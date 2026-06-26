<template>
  <aside class="featured">
    <h3>⭐ Lelang Unggulan</h3>

    <div class="box">
      <div class="badge">Hot Deal</div>
      <h4>Mobil Klasik</h4>
      <p>Harga Saat Ini: Rp 95.000.000</p>
      <span>25 penawaran</span>
    </div>

    <div class="mini-list">
      <div class="mini-item">
        <strong>Jam Rolex</strong>
        <span>12 bid</span>
      </div>
      <div class="mini-item">
        <strong>Tas Prancis</strong>
        <span>8 bid</span>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.featured {
  background: #f8fafc;
  padding: 24px 18px;
  border-left: 1px solid #e2e8f0;
}

.box {
  margin-top: 16px;
  background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);
  padding: 16px;
  border-radius: 16px;
}

.badge {
  display: inline-block;
  background: #f59e0b;
  color: white;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  margin-bottom: 8px;
}

.mini-list {
  display: grid;
  gap: 10px;
  margin-top: 14px;
}

.mini-item {
  background: white;
  padding: 10px 12px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
