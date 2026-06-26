<script setup>

import { useRouter, useRoute } from "vue-router"

const router = useRouter()
const route = useRoute()


function goTo(path){

router.push(path)

}


const menus=[

{
name:"Dashboard",
icon:"🏠",
path:"/"
},

{
name:"Lelang Saya",
icon:"📦",
path:"/my-auctions"
},

{
name:"Buat Lelang",
icon:"➕",
path:"/create"
},

{
name:"Favorit",
icon:"❤️",
path:"/favorite"
},

{
name:"Notifikasi",
icon:"🔔",
path:"/notification"
}


]


</script>


<template>


<aside class="sidebar">


<!-- BRAND -->

<div class="brand">

<div class="logo">
⚡
</div>

<div>
<h2>Bali<span>Auction</span></h2>
<p>Auction Platform</p>
</div>

</div>




<!-- MENU -->

<div class="menu">


<p class="menu-title">
MENU UTAMA
</p>



<div

v-for="item in menus"

:key="item.path"


class="menu-item"


:class="{
active:route.path===item.path
}"


@click="goTo(item.path)"


>


<span class="icon">

{{item.icon}}

</span>


{{item.name}}



</div>



</div>





<!-- CREATE BUTTON -->


<button

class="create-btn"

@click="goTo('/create')"

>


<span>
⚡
</span>

Buat Lelang


</button>





<!-- USER CARD -->


<div class="user-card">


<div class="avatar">
👤
</div>


<div>


<strong>
Putra Mahendra
</strong>


<p>
Penawar Aktif
</p>


</div>


</div>





<div class="status-card">


<div class="live">

● Live Auction

</div>


<h3>
24 Jam
</h3>


<p>
Lelang berjalan realtime
</p>


</div>



</aside>


</template>





<style scoped>


.sidebar{


position:fixed;

left:0;

top:0;

height:100vh;

width:260px;


background:

linear-gradient(
180deg,
#0f172a,
#1e1b4b
);


color:white;

padding:25px 18px;


display:flex;

flex-direction:column;


box-shadow:

8px 0 30px rgba(0,0,0,.15);


}




/* BRAND */


.brand{


display:flex;

align-items:center;

gap:12px;

margin-bottom:35px;


}


.logo{

width:46px;

height:46px;

border-radius:14px;

background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 100%);

display:flex;

align-items:center;

justify-content:center;

font-size:22px;

box-shadow:0 8px 20px rgba(124,58,237,.25);

}


.brand h2{


margin:0;

font-size:22px;

font-weight:800;


}


.brand h2 span{


color:#a78bfa;


}


.brand p{


font-size:11px;

color:#94a3b8;

margin:3px 0;


}





/* MENU */


.menu-title{


font-size:11px;

color:#64748b;

letter-spacing:1px;

margin-bottom:12px;


}



.menu{


display:flex;

flex-direction:column;

gap:8px;

}




.menu-item{


display:flex;

align-items:center;


gap:14px;


padding:14px;


border-radius:15px;


cursor:pointer;


color:#cbd5e1;


transition:.25s;


}



.icon{


font-size:20px;

}



.menu-item:hover{

background:rgba(255,255,255,.08);

color:white;

transform:translateX(4px);

}



.menu-item.active{


background:

linear-gradient(
90deg,
#7c3aed,
#9333ea
);


color:white;


box-shadow:

0 8px 20px rgba(124,58,237,.35);


}




/* BUTTON */


.create-btn{


margin-top:30px;


padding:15px;


border:none;


border-radius:16px;


background:#f59e0b;


color:#111827;


font-weight:700;


font-size:14px;


cursor:pointer;


transition:.3s;


}



.create-btn:hover{


transform:translateY(-3px);


}




/* USER */


.user-card{


margin-top:auto;


background:

rgba(255,255,255,.08);


padding:14px;


border-radius:18px;


display:flex;

align-items:center;


gap:12px;


}



.avatar{


background:#7c3aed;


width:40px;

height:40px;

border-radius:50%;


display:flex;

align-items:center;

justify-content:center;


}


.user-card strong{


font-size:14px;

}


.user-card p{


font-size:11px;

color:#cbd5e1;


margin:3px 0;


}





/* STATUS */


.status-card{


margin-top:15px;


background:

rgba(255,255,255,.08);


border-radius:18px;


padding:15px;


}


.live{


color:#22c55e;

font-size:12px;

font-weight:600;


}


.status-card h3{


margin:8px 0 0;


}


.status-card p{


font-size:12px;

color:#94a3b8;


}





@media(max-width:800px){


.sidebar{


position:relative;

width:100%;

height:auto;


}



}


</style>