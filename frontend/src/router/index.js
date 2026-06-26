import { createRouter, createWebHistory } from "vue-router"

import Home from "../views/home.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"
import AuctionDetail from "../views/AuctionDetail.vue"
import CreateAuction from "../views/CreateAuction.vue"
import MyAuctions from "../views/MyAuctions.vue"

const routes = [
  {
    path: "/",
    component: Home
  },
  {
    path: "/login",
    component: Login
  },
  {
    path: "/register",
    component: Register
  },
  {
    path: "/auction/:id",
    component: AuctionDetail
  },
  {
    path: "/create",
    component: CreateAuction
  },
  {
    path: "/my-auctions",
    component: MyAuctions
  }
]

export default createRouter({
  history: createWebHistory(),
  routes
})