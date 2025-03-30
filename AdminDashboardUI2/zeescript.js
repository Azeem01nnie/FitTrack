document.getElementById("sort-options").addEventListener("change", function () {
    let table = document.querySelector("tbody");
    let rows = Array.from(table.rows);
    let sortBy = this.value;

    rows.sort((a, b) => {
        let colIndex = sortBy === "a-z" || sortBy === "z-a" ? 0 : 2; // Sort by Username or Time In
        let textA = a.cells[colIndex].innerText.toLowerCase();
        let textB = b.cells[colIndex].innerText.toLowerCase();

        if (sortBy === "a-z") return textA.localeCompare(textB);
        if (sortBy === "z-a") return textB.localeCompare(textA);
        if (sortBy === "recent") return new Date(textB) - new Date(textA);
        if (sortBy === "oldest") return new Date(textA) - new Date(textB);
    });

    table.innerHTML = "";
    rows.forEach(row => table.appendChild(row));
});

document.getElementById('search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const table = document.querySelector('.attendance-log tbody');
    const rows = Array.from(table.rows);

    rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const username = row.cells[0].textContent.toLowerCase();
        const match = name.includes(query) || username.includes(query);
        row.style.display = match ? '' : 'none';
    });
});
