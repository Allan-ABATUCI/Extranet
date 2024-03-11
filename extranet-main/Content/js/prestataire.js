document.getElementById('menu-button').addEventListener('click', function() {
  var menu = document.getElementById('sliding-menu');

  if (menu.style.left === "0px") {
    menu.style.left = "-200px";
  } else {
    menu.style.left = "0px";
  }
});
function setActiveLink(linkId) {
    var links = document.querySelectorAll('a');
    links.forEach(function(link) {
        link.classList.remove('active');
    });
    var activeLink = document.getElementById(linkId);
    if (activeLink) {
        activeLink.classList.add('active');
    } else {
        console.error("Link with ID " + linkId + " not found.");
    }
}
