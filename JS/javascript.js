function menuButton() {
  document.getElementById("menuList").classList.toggle("show")
}

function userMenu() {
  document.getElementById("contentUser").classList.toggle("show");
}

function PageLoad() {
  if(document.getElementById("areaPersonale") != null){
    sessionStorage.setItem('loggedIn', 'true');
    // sessionStorage.setItem('cambiato', 'false');
    console.log('H1');
  }

  if(sessionStorage.getItem('loggedIn') == 'true'){
    document.getElementById("LogIn").style.display = "none";
    document.getElementById("LogOut").style.display = "block";
    document.getElementById("LogOut").style.border = "none";
    document.getElementById("LogOut").style.padding = "0";
    // sessionStorage.setItem('cambiato', 'true');
  } else {
    document.getElementById("LogIn").style.display = "block";
    document.getElementById("LogIn").style.border = "none";
    document.getElementById("LogIn").style.padding = "0";
    document.getElementById("LogOut").style.display = "none";
  }
  
}

function LogOut() {
  sessionStorage.setItem('loggedIn', 'false');
}
  

document.addEventListener("click", function(event){
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
    var i;
    for(i = 0; i < menu.length; i++){
      var openMenu = menu[i];
      if(openMenu.classList.contains('show')){
        openMenu.classList.remove('show');
      }
    }
  } 
});



