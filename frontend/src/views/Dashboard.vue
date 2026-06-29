<script setup>

import { ref,onMounted } from 'vue'
import api from '../services/api'


const auctions = ref([])
const loading = ref(true)



async function getAuctions(){

try{

const res = await api.get('/auctions')


console.log(res.data)


auctions.value = res.data.data ?? res.data       


}catch(error){

console.log(error)

}
finally{

loading.value=false

}

}



onMounted(getAuctions)


</script>


<template>

<div class="page">


<!-- HEADER -->

<header class="header">


<div>

<h1>
🏛 Bali Auction
</h1>

<p>
Marketplace Lelang Online
</p>

</div>



<div class="actions">


<router-link 
to="/create"
class="create-btn"
>

+ Buat Lelang

</router-link>


<router-link
to="/login"
class="login-btn"
>

Login

</router-link>


</div>



</header>





<!-- CONTENT -->

<div class="container">



<h2>
Lelang Terbaru
</h2>



<div v-if="loading">

Loading...

</div>




<div 
v-else
class="grid"
>



<div
v-for="item in auctions"
:key="item.id || item._id"
class="card"
>



<img

v-if="item.image_url"

:src="item.image_url"

/>


<div 
v-else
class="no-image"
>

No Image

</div>




<div class="body">


<span class="status">

🟢 Aktif

</span>



<h3>

{{item.title}}

</h3>



<p>

{{item.category}}

</p>




<h2>

Rp {{item.current_price}}

</h2>




<router-link

:to="`/auction/${item.id}`"

class="detail-btn"

>

Lihat Detail

</router-link>



</div>



</div>



</div>



</div>



</div>



</template>





<style scoped>


.page{

min-height:100vh;

background:#f5f6fa;

padding:25px;

}



/* HEADER */

.header{

background:white;

padding:25px;

border-radius:20px;

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:30px;

}



.header h1{

margin:0;

}



.header p{

color:#777;

}



.actions{

display:flex;

gap:10px;

}




.create-btn{

background:#111;

color:white;

padding:12px 20px;

border-radius:12px;

text-decoration:none;

}




.login-btn{

background:#eee;

color:#111;

padding:12px 20px;

border-radius:12px;

text-decoration:none;

}



/* GRID */


.grid{

display:grid;

grid-template-columns:
repeat(auto-fill,minmax(250px,1fr));

gap:20px;

}





.card{

background:white;

border-radius:20px;

overflow:hidden;

box-shadow:
0 5px 20px rgba(0,0,0,.08);

}





.card img{

width:100%;

height:180px;

object-fit:cover;

}





.no-image{

height:180px;

background:#ddd;

display:flex;

justify-content:center;

align-items:center;

}





.body{

padding:18px;

}




.status{

background:#dcfce7;

color:#166534;

padding:5px 10px;

border-radius:20px;

font-size:12px;

}



.body h3{

margin-top:15px;

}



.detail-btn{

display:block;

text-align:center;

background:#111;

color:white;

padding:10px;

border-radius:12px;

text-decoration:none;

margin-top:15px;

}


</style>