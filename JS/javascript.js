function menuButton() {
  document.getElementById("menuList").classList.toggle("show")
}

function userMenu() {
  document.getElementById("contentUser").classList.toggle("show");
}

function PageLoad() {
  if(document.getElementById("areaPersonale") != null){
    sessionStorage.setItem('loggedIn', 'true');
    console.log('H1');
  }

  if(sessionStorage.getItem('loggedIn') == 'true'){
    document.getElementById("LogIn").style.display = "none";
    document.getElementById("LogOut").style.display = "block";
  } else {
    document.getElementById("LogIn").style.display = "block";
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

function statusPass(){
    var x = document.getElementById("ragionevolePassword");
    changeStatus(x);
  }

  function statusPassLogin(){
    var x = document.getElementById("passwordLogin");
    changeStatus(x);
  }

  function changeStatus(x){
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
