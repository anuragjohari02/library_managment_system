let navbar = document.querySelector(".header .navbar");
let accountBox = document.querySelector(".header .account-box");

document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  accountBox.classList.remove("active");
};

document.querySelector("#user-btn").onclick = () => {
  accountBox.classList.toggle("active");
  navbar.classList.remove("active");
};

window.onscroll = () => {
  navbar.classList.remove("active");
  accountBox.classList.remove("active");
};

document.querySelector("#close-update").onclick = () => {
  document.querySelector(".edit-product-form").style.display = "none";
  window.location.href = "admin_products.php";
};

// for multi select dropdown
var expanded = false;

function showCheckboxes() {
    var checkboxes = document.getElementById("checkboxes");
    if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
    } else {
        checkboxes.style.display = "none";
        expanded = false;
    }
}

window.onclick = function (event) {
    if (!event.target.matches('.selectBox')) {
        var checkboxes = document.getElementById("checkboxes");
        if (expanded && checkboxes !== event.target) {
            checkboxes.style.display = "none";
            expanded = false;
        }
    }
}
