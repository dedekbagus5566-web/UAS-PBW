<script setup>
import Sidebar from './components/Sidebar.vue'
</script>

<template>
  <div class="app-shell">
    <Sidebar />

    <div class="content-area">
      <nav class="top-nav">
        <router-link to="/" class="brand">
          <span class="brand-mark">⚡</span>
          <span>Bali Auction</span>
        </router-link>

        <div class="nav-links">
          <router-link to="/">Home</router-link>
          <router-link to="/create">Create Auction</router-link>
          <router-link to="/login">Login</router-link>
        </div>
      </nav>

      <main class="page-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<style scoped>
.app-shell {
  min-height: 100vh;
  background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
}

.content-area {
  margin-left: 260px;
  min-height: 100vh;
  padding-top: 76px;
}

.top-nav {
  position: fixed;
  top: 0;
  left: 260px;
  right: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 24px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(14px);
  border-bottom: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
}

.brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  text-decoration: none;
}

.brand-mark {
  width: 38px;
  height: 38px;
  display: grid;
  place-items: center;
  border-radius: 12px;
  background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
  color: white;
  font-size: 18px;
  box-shadow: 0 8px 20px rgba(124, 58, 237, 0.25);
}

.nav-links {
  display: flex;
  gap: 16px;
}

.nav-links a {
  color: #475569;
  text-decoration: none;
  font-weight: 600;
  padding: 8px 10px;
  border-radius: 10px;
}

.nav-links a.router-link-active {
  color: #6d28d9;
  background: #f5f3ff;
}

.page-content {
  padding: 24px 28px 40px;
}

@media (max-width: 800px) {
  .content-area {
    margin-left: 0;
    padding-top: 72px;
  }

  .top-nav {
    left: 0;
  }

  .page-content {
    padding: 16px 16px 28px;
  }
}
</style>