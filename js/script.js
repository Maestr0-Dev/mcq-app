document.getElementById("show-login").addEventListener("click",function(){
    document.querySelector(".form").classList.add("active")
})
document.querySelector(".close-btn").addEventListener("click",function(){
    document.querySelector(".form").classList.remove("active")
})

   