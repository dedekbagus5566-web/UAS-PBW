<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const title = ref('')
const description = ref('')
const price = ref('')
const category = ref('Elektronik')
const imageUrl = ref('')

async function submit() {
  const token = localStorage.getItem('token')

  if (!token) {
    alert('Silakan login terlebih dahulu')
    router.push('/login')
    return
  }

  try {
    await api.post('/auctions', {
      title: title.value,
      description: description.value,
      price: Number(price.value),
      category: category.value,
      image_url: imageUrl.value || null
    })

    alert('Lelang berhasil dibuat')
    router.push('/')
  } catch (error) {
    console.error(error)
    alert('Gagal membuat lelang, cek kembali data Anda')
  }
}
</script>

<template>
  <div class="page">
    <div class="card">
      <div class="badge">Buat Lelang</div>
      <h2>Buat Lelang Baru</h2>
      <p>Isi detail lelang Anda dan simpan langsung ke platform.</p>

      <form @submit.prevent="submit" class="form">
        <div class="form-group">
          <label>Judul</label>
          <input v-model="title" placeholder="Contoh: Kamera Canon EOS R" required />
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <select v-model="category">
            <option>Elektronik</option>
            <option>Kendaraan</option>
            <option>Seni</option>
            <option>Fashion</option>
          </select>
        </div>

        <div class="form-group">
          <label>Harga Awal</label>
          <input v-model="price" type="number" min="1" placeholder="1000000" required />
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea v-model="description" rows="4" placeholder="Jelaskan kondisi barang..." />
        </div>

        <div class="form-group">
          <label>URL Gambar</label>
          <input v-model="imageUrl" placeholder="https://example.com/image.jpg" />
        </div>

        <button type="submit">Simpan Lelang</button>
      </form>
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
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
  max-width: 520px;
  width: 100%;
}

.badge {
  display: inline-block;
  background: #ede9fe;
  color: #6d28d9;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  margin-bottom: 12px;
}

h2 {
  margin: 0 0 8px;
  color: #111827;
  font-size: 24px;
}

p {
  color: #64748b;
  margin-bottom: 16px;
  line-height: 1.6;
}

form {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

label {
  font-size: 13px;
  font-weight: 700;
  color: #334155;
}

input,
select,
textarea {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 10px 12px;
  font: inherit;
  background: #fff;
}

button {
  border: 0;
  background: linear-gradient(135deg, #111827 0%, #334155 100%);
  color: white;
  padding: 11px 16px;
  border-radius: 999px;
  cursor: pointer;
  font-weight: 700;
}
</style>
