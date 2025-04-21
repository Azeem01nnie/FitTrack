function openModal() {
    document.getElementById("logoutModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("logoutModal").style.display = "none";
  }

  function logout() {
    alert("You have been logged out.");
    closeModal();
    window.location.href = "http://localhost/myprojects/FiTrackElective/FitTrack/marfolder/LogIn.php";
  }

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const sortSelect = document.getElementById("sort-options");
    const tableBody = document.querySelector("tbody");

    searchInput.addEventListener("input", function () {
        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });

    sortSelect.addEventListener("change", function () {
        const rowsArray = Array.from(tableBody.querySelectorAll("tr"));
        const sortType = sortSelect.value;

        rowsArray.sort((a, b) => {
            const aText = a.children[2].textContent.toLowerCase(); // full name
            const bText = b.children[2].textContent.toLowerCase();
            const aDate = new Date(a.children[0].textContent);
            const bDate = new Date(b.children[0].textContent);

            switch (sortType) {
                case "a-z":
                    return aText.localeCompare(bText);
                case "z-a":
                    return bText.localeCompare(aText);
                case "recent":
                    return bDate - aDate;
                case "oldest":
                    return aDate - bDate;
                default:
                    return 0;
            }
        });

        rowsArray.forEach(row => tableBody.appendChild(row));
    });
});
