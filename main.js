// Menu SlideBar-Category

const itemSliderBar = document.querySelectorAll(".category-left-li");
itemSliderBar.forEach(function(menu, index){
   menu.addEventListener("click", function(){
    menu.classList.toggle("block")
   })
})
// -----------------------------Chi tiet van chuyen-------------------
const bigimg = document.querySelector(".product-content-left-big-img img")
const smallImg = document.querySelectorAll(".product-content-left-small-img img")
smallImg.forEach(function(imgItem, x){
   imgItem.addEventListener("click", function(){
      bigimg.src = imgItem.src
   })
})




const chitiet = document.querySelector(".chitiet");
const vanchuyen = document.querySelector(".vanchuyen");
if (chitiet){
   chitiet.addEventListener("click", function(){
      document.querySelector(".product-content-right-bottom-content-chitiet").style.display = "block"
       document.querySelector(".product-content-right-bottom-content-vanchuyen").style.display = "none"

   })
}
if (vanchuyen){
   vanchuyen.addEventListener("click", function(){
      document.querySelector(".product-content-right-bottom-content-chitiet").style.display = "none"
       document.querySelector(".product-content-right-bottom-content-vanchuyen").style.display = "block"
   })
}
const button = document.querySelector(".product-content-right-bottom-top");
if (button){
   button.addEventListener("click", function(){
      document.querySelector(".product-content-right-bottom-content-big").classList.toggle("active2");
   })
}

// Lấy giỏ hàng từ localStorage hoặc tạo mảng rỗng nếu chưa có
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// Cập nhật số lượng sản phẩm trên giỏ hàng
function updateCartCount() {
    let cartCount = document.getElementById("cart-count");
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
}

// Thêm sản phẩm vào giỏ hàng
document.addEventListener("DOMContentLoaded", function () {
   let cartCount = 0; // Biến lưu số lượng sản phẩm trong giỏ hàng
   const cartCountEl = document.getElementById("cart-count");
   const addToCartBtns = document.querySelectorAll(".product-content-right-product-button button");

   addToCartBtns.forEach(button => {
       button.addEventListener("click", function () {
           cartCount++; // Tăng số lượng
           cartCountEl.textContent = cartCount; // Cập nhật số hiển thị
           cartCountEl.classList.add("active"); // Hiển thị số lượng
       });
   });
});

