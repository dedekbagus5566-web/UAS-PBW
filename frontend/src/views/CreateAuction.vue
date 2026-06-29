<script setup>

import { ref } from "vue"
import { useRouter } from "vue-router"
import api from "../services/api"


const router = useRouter()


const title = ref("")
const description = ref("")
const price = ref("")
const category = ref("")

const image = ref(null)
const preview = ref(null)


function chooseImage(e){

    const file = e.target.files[0]

    image.value = file


    if(file){

        preview.value = URL.createObjectURL(file)

    }

}



async function submit(){


try{


const formData = new FormData()



formData.append(
"title",
title.value
)


formData.append(
"description",
description.value
)


formData.append(
"price",
price.value
)


formData.append(
"category",
category.value
)



if(image.value){

formData.append(
"image",
image.value
)

}





await api.post(
"/auctions",
formData,
{
headers:{

"Content-Type":"multipart/form-data"

}

}

)



alert("Lelang berhasil dibuat")


router.push("/dashboard")



}

catch(error){

console.log(
error.response?.data
)


alert(
error.response?.data?.message 
|| 
"Terjadi error"
)


}



}


</script>





<template>


<div class="page">


<div class="card">


<h1>
Buat Lelang
</h1>



<input

v-model="title"

placeholder="Judul barang"

/>



<textarea

v-model="description"

placeholder="Deskripsi"

/>




<input

v-model="price"

type="number"

placeholder="Harga awal"

/>




<input

v-model="category"

placeholder="Kategori"

/>





<label>

Upload Gambar

</label>


<input

type="file"

accept="image/*"

@change="chooseImage"

/>





<img

v-if="preview"

:src="preview"

class="preview"

/>





<button @click="submit">

Simpan Lelang

</button>



</div>


</div>



</template>





<style scoped>


.page{

display:flex;

justify-content:center;

padding:30px;

}



.card{

width:450px;

background:white;

padding:25px;

border-radius:20px;

}



input,
textarea{

width:100%;

padding:12px;

margin-bottom:15px;

border-radius:10px;

border:1px solid #ddd;

}



textarea{

height:100px;

}



.preview{

width:100%;

height:200px;

object-fit:cover;

border-radius:15px;

margin-bottom:15px;

}



button{

width:100%;

padding:12px;

background:#111;

color:white;

border:none;

border-radius:10px;

cursor:pointer;

}


</style>