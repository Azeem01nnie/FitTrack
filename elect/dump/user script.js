function toggleDropdown() {
    var menu = document.getElementById("dropdownMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}
document.addEventListener("click", function(event) {
    var dropdown = document.getElementById("dropdownMenu");
    var button = document.querySelector(".profile-button");
    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});


window.onclick = function(event) {
    if (!event.target.matches(".profile-button")) {
        let dropdown = document.getElementById("dropdownMenu");
        if (dropdown.classList.contains("show")) {
            dropdown.classList.remove("show");
        }
    }
};

function scrollLeft() {
    document.getElementById('slider').scrollBy({ left: -250, behavior: 'smooth' });
}

function scrollRight() {
    document.getElementById('slider').scrollBy({ left: 250, behavior: 'smooth' });
}

function toggleDropdown1() {
    let dropdown = document.getElementById("dropdownMenu1");
    dropdown.classList.toggle("show");
}