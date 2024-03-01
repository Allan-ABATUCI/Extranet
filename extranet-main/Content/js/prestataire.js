document.getElementById('menu-button').addEventListener('click', function() {
  var menu = document.getElementById('sliding-menu');

  if (menu.style.left === "0px") {
    menu.style.left = "-200px";
  } else {
    menu.style.left = "0px";
  }
});