
function toggleDetails(button) {
    let details = button.parentElement.nextElementSibling;
    if (details.classList.contains("hidden")) {
        details.classList.remove("hidden");
        button.innerText = "View Less";
    } else {
        details.classList.add("hidden");
        button.innerText = "View More";
    }
}


function searchAccounts() {
    let input = document.getElementById("searchBar").value.toUpperCase();
    let cards = document.getElementsByClassName("account-card");

    for (let i = 0; i < cards.length; i++) {
        let name = cards[i].querySelector(".summary p").innerText.toUpperCase();
        if (name.indexOf(input) > -1) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}


function logout() {
    localStorage.removeItem("loggedIn");
    window.location.href = "index.html";
}

function checkLogin() {
    if (!localStorage.getItem("loggedIn")) {
        window.location.href = "index.html";
    }
}


if (window.location.pathname.includes("account.html")) {
    checkLogin();
}