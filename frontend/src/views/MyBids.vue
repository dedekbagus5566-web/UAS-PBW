<script setup>

import {onMounted,ref} from "vue"
import api from "../services/api"


const bids = ref([])


async function load(){

try{

const res = await api.get("/my-bids")

bids.value=res.data.data


}catch(e){

console.log(e)

}

}


onMounted(()=>{

load()

})


</script>


<template>


<div class="page">


<h1>
💰 Bid Saya
</h1>



<div v-if="bids.length===0">

Belum melakukan bid

</div>



<div

v-for="bid in bids"

:key="bid.id"

class="card"

>


<h3>

{{bid.auction.title}}

</h3>


<p>

Harga:
{{bid.amount}}

</p>


</div>


</div>


</template>