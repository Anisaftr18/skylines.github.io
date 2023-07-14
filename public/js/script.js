// Navbar
var navbar = document.getElementById("navbar");
var menu = document.getElementById("menu");

window.onscroll = function(){
  if(window.pageYOffset >= menu.offsetTop){
    navbar.classList.add("sticky");
    
  }
  else{
    navbar.classList.remove("sticky");
  }
}

// Tables
$(document).ready(function () {
    $('#example').DataTable();
});