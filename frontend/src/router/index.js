import { createRouter, createWebHistory } from "vue-router"


import Home from "../views/home.vue"
import Dashboard from "../views/Dashboard.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"

import AuctionDetail from "../views/AuctionDetail.vue"
import CreateAuction from "../views/CreateAuction.vue"

import MyAuctions from "../views/MyAuctions.vue"
import MyBids from "../views/MyBids.vue"



const routes = [



{
    path:"/",
    redirect:"/dashboard"
},



{
 path:"/dashboard",
 component:Dashboard
},



{
    path:"/login",
    name:"login",
    component:Login
},



{
    path:"/register",
    name:"register",
    component:Register
},



{
    path:"/auction/:id",
    name:"auction-detail",
    component:AuctionDetail
},



{
    path:"/create",
    name:"create",
    component:CreateAuction
},



{
    path:"/my-auctions",
    component:MyAuctions
},



{
    path:"/my-bids",
    component:MyBids
}



]





const router=createRouter({

history:createWebHistory(),

routes

})



export default router