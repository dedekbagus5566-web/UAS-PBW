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



function back(){

router.push('/dashboard')

}



function formatPrice(value){

return new Intl.NumberFormat('id-ID',{

style:'currency',
currency:'IDR',
maximumFractionDigits:0

}).format(value || 0)

}





// =========================
// LOAD AUCTION
// =========================

async function loadAuction(){

try{


const res = await api.get(`/auctions/${auctionId}`)


auction.value = res.data.data ?? res.data



startCountdown()



}catch(err){


console.log(err)

error.value="Gagal memuat detail lelang"


}


finally{

loading.value=false

}


}







// =========================
// LOAD BID HISTORY
// =========================


async function getBids(){


try{


const res = await api.get(
`/auctions/${auctionId}/bids`
)


bids.value = res.data.data ?? res.data



}catch(err){

console.log(err)

}


}







// =========================
// COUNTDOWN
// =========================


function startCountdown(){


if(!auction.value?.ends_at)
return



clearInterval(timer)



timer=setInterval(()=>{


const end =
new Date(auction.value.ends_at).getTime()


const now =
new Date().getTime()



const diff=end-now



if(diff<=0){


timeLeft.value="⛔ Waktu Habis"

clearInterval(timer)

return

}



const hours =
Math.floor(diff/(1000*60*60))


const minutes =
Math.floor(
(diff%(1000*60*60))
/
(1000*60)
)



const seconds =
Math.floor(
(diff%(1000*60))
/
1000
)



timeLeft.value =
`${hours}j ${minutes}m ${seconds}d`



},1000)



}









// =========================
// SUBMIT BID FIX
// =========================


async function submitBid(){



if(!bidAmount.value){

bidMessage.value="Masukkan nominal bid"

return

}



try{


bidLoading.value=true



await api.post('/bids',{


auction_id: auction.value.id,


amount: bidAmount.value



})



bidMessage.value="Bid berhasil"



bidAmount.value=''


await loadAuction()

await getBids()



}catch(err){



console.log(err)


bidMessage.value =
err.response?.data?.message ||
"Bid gagal"



}



finally{


bidLoading.value=false


}


}









// =========================
// CLOSE AUCTION
// =========================


async function closeAuction(){


try{


await api.post(
`/auctions/${auctionId}/close`
)


await loadAuction()


}catch(err){

console.log(err)

}


}








onMounted(()=>{


loadAuction()

getBids()


})




onUnmounted(()=>{


clearInterval(timer)


})



</script>





<template>


<div class="page">


<div class="card">



<button
class="back-btn"
@click="back"
>

← Kembali

</button>





<div v-if="loading">

Loading...

</div>




<div v-else-if="error">

{{error}}

</div>






<div v-else-if="auction">





<h1>

{{auction.title}}

</h1>



<p>

{{auction.category}}

</p>






<img
class="hero-image"
:src="auction.image_url"
/>







<div class="status-box">

⏳ {{timeLeft}}

</div>




<div class="status-box">

Status:

<b>
{{auction.status}}
</b>

</div>






<div class="price">


<p>
Harga Awal
</p>


<h3>

{{formatPrice(auction.price)}}

</h3>


<p>
Harga Sekarang
</p>


<h2>

{{formatPrice(auction.current_price)}}

</h2>


</div>








<div class="bid-form">


<h3>

Masukkan Bid

</h3>



<input

type="number"

v-model="bidAmount"

placeholder="Nominal bid"


/>



<button
@click="submitBid"
>

{{bidLoading?'Mengirim':'Bid Sekarang'}}

</button>



<p>

{{bidMessage}}

</p>



</div>








<button
v-if="auction.status==='active'"
@click="closeAuction"

>

Tutup Lelang

</button>








<h3>

Riwayat Bid

</h3>



<div
v-for="bid in bids"
:key="bid.id"
class="bid-card"

>


<b>

{{bid.user?.name}}

</b>


<p>

{{formatPrice(bid.amount)}}

</p>


</div>







</div>


</div>


</div>


</template>






<style scoped>


.page{

padding:30px;

display:flex;

justify-content:center;

}



.card{

background:white;

width:750px;

padding:30px;

border-radius:20px;

}




.hero-image{

width:100%;

height:300px;

object-fit:cover;

border-radius:15px;

margin:20px 0;

}



.status-box{

background:#f1f5f9;

padding:15px;

border-radius:12px;

margin:10px 0;

}



.price{

margin:20px 0;

}




.bid-form{

background:#f8fafc;

padding:20px;

border-radius:15px;

}



input{

width:100%;

padding:12px;

margin:10px 0;

}



button{

padding:10px 15px;

border:none;

border-radius:10px;

cursor:pointer;

}



.bid-card{

background:#f8fafc;

padding:12px;

margin-top:10px;

border-radius:10px;

}


</style>