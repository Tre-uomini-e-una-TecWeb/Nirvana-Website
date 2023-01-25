function menuButton() {
  document.getElementById("menuList").classList.toggle("show")
}

function userMenu() {
  document.getElementById("contentUser").classList.toggle("show");
}

function goUp() {
  let elem = document.getElementById("cima");
  window.scroll({
    top: elem.offsetTop, 
    left: 0, 
    behavior: 'smooth' 
});
  document.getElementById("goUp").style.opacity = '0';
}

function PageLoad() {
  if(document.getElementById("areaPersonale") != null){
    sessionStorage.setItem('loggedIn', 'true');
    sessionStorage.setItem('cambiato', 'false');
  }

  if(sessionStorage.getItem('loggedIn') == 'true'){
    // document.getElementById("LogIn").style.display = "none";
    // document.getElementById("LogOut").style.display = "block";
    // document.getElementById("LogOut").style.border = "none";
    // document.getElementById("LogOut").style.padding = "0";
    document.getElementById("LogIn").classList.add('hide');
    document.getElementById("LogOut").classList.add('menuLogOutUt');
  } else {
    // document.getElementById("LogIn").style.display = "block";
    // document.getElementById("LogIn").style.border = "none";
    // document.getElementById("LogIn").style.padding = "0";
    // document.getElementById("LogOut").style.display = "none";
    document.getElementById("LogIn").classList.add("menuLogInUt");
    document.getElementById("LogOut").classList.add('hide');
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

let lastScrollTop = 0;

document.addEventListener("scroll", function(){ 
  let headerH = document.querySelector("header").offsetHeight;
  let st = window.pageYOffset || document.documentElement.scrollTop; 
  if (st > lastScrollTop || st < headerH){
    // downscroll code
    document.getElementById("goUp").style.opacity = '0';
  } else {
    // upscroll code
    document.getElementById("goUp").style.opacity = '1';
  }
  lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
}, false);


document.addEventListener("scroll", function(event){
  let scrollBrn = document.querySelector("#top-right");
  let footer = document.querySelector("footer");
  let body = document.querySelector("body");
  const  x = window.matchMedia("(max-width: 920px)").matches;
  const y = (body.offsetHeight - footer.offsetHeight - 90 );
  if(x){
    if((window.scrollY + window.innerHeight) >= (document.documentElement.scrollHeight - footer.offsetHeight + 20)){
      scrollBrn.classList.remove("menuLogMob");
      scrollBrn.classList.add("menuLogMobStop");
      // scrollBrn.style.position = "absolute";
      // let classStyle = document.getElementsByClassName("menuLogMobStop");
      let topRule = new Array();
      if (document.styleSheets[1].cssRules[9].cssRules[19]) {
        topRule = document.styleSheets[1].cssRules[9].cssRules[19];
      } else if (document.styleSheets[1].rules[9].rules[19]) { // StackOverflow diceva che alcuni browser usavano .rules NON TOGLIERE!
        topRule = document.styleSheets[1].rules[9].rules[19];
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


function statusPassLog1(){
    var x = document.getElementById("password1");
    changeStatus(x);
  }

function statusPassLog2(){
  var x = document.getElementById("password2");
  changeStatus(x);
}

function statusPassPers1(){
  var x = document.getElementById("passwordPers1");
  changeStatus(x);
}

function statusPassPers2(){
var x = document.getElementById("passwordPers2");
changeStatus(x);
}

function changeStatus(x){
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
