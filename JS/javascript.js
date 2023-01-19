function menuButton() {
  document.getElementById("menuList").classList.toggle("show")
}

function userMenu() {
  document.getElementById("contentUser").classList.toggle("show");
}

function PageLoad() {
  // sessionStorage.setItem('cambiato', 'false');
  if(document.getElementById("areaPersonale") != null){
    sessionStorage.setItem('loggedIn', 'true');
    sessionStorage.setItem('cambiato', 'false');
  }
  if(!("cambiato" in sessionStorage)){
    sessionStorage.setItem('cambiato', 'false');
    console.log("object");
  }

  if(/*sessionStorage.getItem('cambiato') == 'false' &&*/ sessionStorage.getItem('loggedIn') == 'true'){
    // document.getElementById("LogIn").style.display = "none";
    // document.getElementById("LogOut").style.display = "block";
    // document.getElementById("LogOut").style.border = "none";
    // document.getElementById("LogOut").style.padding = "0";
    document.getElementById("LogIn").classList.add('hide');
    document.getElementById("LogOut").classList.add('menuLogOutUt');
    // sessionStorage.setItem('cambiato', 'true');
    console.log('hii');
  } else /*if (sessionStorage.getItem('cambiato') == 'false')*/{
    // document.getElementById("LogIn").style.display = "block";
    // document.getElementById("LogIn").style.border = "none";
    // document.getElementById("LogIn").style.padding = "0";
    // document.getElementById("LogOut").style.display = "none";
    document.getElementById("LogIn").classList.add("menuLogInUt");
    document.getElementById("LogOut").classList.add('hide');
    // sessionStorage.setItem('cambiato', 'true');
    // console.log('object');
  }
  // if(sessionStorage.getItem('loggedIn') == 'true'){
  //   var list = document.getElementsByClassName("LogIn");
  //   for (let item of list) {
  //     item.style.display = "none";
  //   }

  //   list = document.getElementsByClassName("LogOut");
  //   for (let item of list) {
  //     item.style.display = "block";
  //   }
  // } else {
  //   var list = document.getElementsByClassName("LogIn");
  //   for (let item of list) {
  //     item.style.display = "block";
  //   }

  //   list = document.getElementsByClassName("LogOut");
  //   for (let item of list) {
  //     item.style.display = "none";
  //   }
  
}

function LogOut() {
  sessionStorage.setItem('loggedIn', 'false');
  // sessionStorage.setItem('cambiato', 'false');
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

document.addEventListener("scroll", function(event){
  let scrollBrn = document.querySelector("#top-right");
  let footer = document.querySelector("footer");
  let body = document.querySelector("body");
  const  x = window.matchMedia("(max-width: 800px)").matches;
  const y = (body.offsetHeight - footer.offsetHeight - 90 );
  if(x){
    if((window.scrollY + window.innerHeight) >= (document.documentElement.scrollHeight - footer.offsetHeight + 20)){
      scrollBrn.classList.remove("menuLogMob");
      scrollBrn.classList.add("menuLogMobStop");
      // scrollBrn.style.position = "absolute";
      // let classStyle = document.getElementsByClassName("menuLogMobStop");
      let topRule = new Array();
        if (document.styleSheets[4].cssRules[6].cssRules[13]) {
            topRule = document.styleSheets[4].cssRules[6].cssRules[13];
        } else if (document.styleSheets[4].rules[6].rules[13]) { // StackOverflow diceva che alcuni browser usavano .rules NON TOGLIERE!
            topRule = document.styleSheets[4].rules[6].rules[13];
        }
        topRule.style.top = y + 'px';
        // theRules.style.righ
      // scrollBrn.style.top = (y) + "px";
      // scrollBrn.style.bottom = -60 + "em";
    } else {
      scrollBrn.classList.remove("menuLogMobStop");
      scrollBrn.classList.add("menuLogMob")
      // scrollBrn.style.position = "fixed";
      // scrollBrn.style.bottom = 2 + "em";
      // scrollBrn.style.top = "initial";
      // console.log('HIII');
      // console.log(document.body.clientHeight);
      // console.log(footer.offsetHeight);
      // console.log(window.scrollY);
      }
  } /*else {
    // scrollBrn.classList.remove("menuLogMobStop");
    // scrollBrn.classList.remove("menuLogMob");
    // scrollBrn.classList.add("menuLogDesk");
  }*/
});


// function statusPass1(){
//     var x = document.getElementById("password1");
//     changeStatus(x);
//   }

//   function statusPass2(){
//     var x = document.getElementById("password2");
//     changeStatus(x);
//   }

//   function changeStatus(x){
//     if (x.type === "password") {
//       x.type = "text";
//     } else {
//       x.type = "password";
//     }
//   }
