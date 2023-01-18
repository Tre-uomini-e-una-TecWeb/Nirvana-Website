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
    var list = document.getElementsByClassName("LogIn");
    for (let item of list) {
      item.style.display = "none";
    }

    list = document.getElementsByClassName("LogOut");
    for (let item of list) {
      item.style.display = "block";
    }
  } else {
    var list = document.getElementsByClassName("LogIn");
    for (let item of list) {
      item.style.display = "block";
    }

    list = document.getElementsByClassName("LogOut");
    for (let item of list) {
      item.style.display = "none";
    }
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

function statusPass1(){
    var x = document.getElementById("password1");
    changeStatus(x);
  }

  function statusPass2(){
    var x = document.getElementById("password2");
    changeStatus(x);
  }

  function changeStatus(x){
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }
