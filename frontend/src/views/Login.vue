<script setup>

import { ref } from "vue"
import { useRouter } from "vue-router"
import api from "../services/api"


const router = useRouter()


const email = ref("")
const password = ref("")


const loading = ref(false)



async function login(){


try{


loading.value = true



const res = await api.post("/login",{


email: email.value,


password: password.value



})





// SIMPAN TOKEN SANCTUM

localStorage.setItem(

"token",

res.data.token

)





// SIMPAN USER

localStorage.setItem(

"user",

JSON.stringify(res.data.user)

)







alert("Login berhasil")





router.push("/dashboard")






}catch(error){



console.log(error)



alert(

error.response?.data?.message ||

"Login gagal, cek email dan password"

)



}



finally{


loading.value=false


}



}



</script>





<template>


<div class="page">


<div class="card">





<div class="brand">


<span class="brand-mark">

⚡

</span>


<span>

Bali Auction

</span>


</div>






<h1>

Masuk ke akun Anda

</h1>



<p>

Selamat datang kembali, silakan login untuk ikut lelang.

</p>








<div class="form-group">


<label>

Email

</label>


<input

v-model="email"

type="email"

placeholder="contoh@email.com"

/>



</div>








<div class="form-group">


<label>

Password

</label>


<input

v-model="password"

type="password"

placeholder="********"

/>



</div>







<button

@click="login"

:disabled="loading"

>


{{loading ? "Memproses..." : "Masuk"}}


</button>







<router-link

to="/register"

class="link"

>


Belum punya akun? Daftar


</router-link>






</div>


</div>


</template>






<style scoped>


.page {


min-height: calc(100vh - 120px);


display:flex;


justify-content:center;


align-items:center;


}



.card {


width:100%;


max-width:430px;


background:white;


padding:30px;


border-radius:24px;


box-shadow:0 18px 50px rgba(0,0,0,.08);


}




.brand{


display:flex;


align-items:center;


gap:10px;


font-size:20px;


font-weight:700;


margin-bottom:15px;


}



.brand-mark{


width:38px;


height:38px;


display:grid;


place-items:center;


border-radius:12px;


background:#7c3aed;


color:white;


}




h1{


margin-bottom:10px;


}



p{


color:#64748b;


margin-bottom:20px;


}





.form-group{


display:flex;


flex-direction:column;


gap:8px;


margin-bottom:15px;


}





input{


padding:12px;


border-radius:12px;


border:1px solid #ddd;


}





button{


width:100%;


padding:12px;


border:0;


border-radius:12px;


background:#7c3aed;


color:white;


font-weight:bold;


cursor:pointer;


}





button:disabled{


opacity:.6;


}





.link{


display:block;


text-align:center;


margin-top:15px;


color:#7c3aed;


text-decoration:none;


font-weight:bold;


}


</style>