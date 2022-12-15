function menuButton() {
  console.log(document.getElementById("menuList"));
  document.getElementById("menuList").classList.toggle("show");
  var dropD = document.getElementById("menuList");
  console.log(dropD);
  console.log('Hi');
}

function userMenu() {
  document.getElementById("contentUser").classList.toggle("show");
}

document.addEventListener("click", function(event){
  //console.log(event.target);
  if (!event.target.matches('.buttonMenu')) {
    var dropdowns = document.getElementsByClassName("menuCont");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
  if(!event.target.matches('.userMenu')){
    var menu = document.getElementsByClassName("userLinks");
    console.log(menu);
    console.log(menu.length);
    var i;
    for(i = 0; i < menu.length; i++){
      var openMenu = menu[i];
      if(openMenu.classList.contains('show')){
        openMenu.classList.remove('show');
      }
    }
  } 
});



