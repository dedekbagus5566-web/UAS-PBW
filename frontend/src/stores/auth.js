import {defineStore}
from "pinia"


export const useAuthStore =
defineStore("auth",{


state:()=>({

token:null,
user:null

}),


actions:{


setToken(token){

this.token=token

localStorage.setItem(
"token",
token
)

}


}


})