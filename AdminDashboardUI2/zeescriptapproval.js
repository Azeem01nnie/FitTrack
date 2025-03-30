document.getElementById('sort-options').addEventListener('change', function() {
    const table = document.querySelector('.dashboard tbody');
    const rows = Array.from(table.rows);
    const sortOption = this.value;

    rows.sort((a, b) => {
        const nameA = a.cells[2].textContent.toLowerCase();
        const nameB = b.cells[2].textContent.toLowerCase();
        const dateA = new Date(a.cells[0].textContent);
        const dateB = new Date(b.cells[0].textContent);

        switch (sortOption) {
            case 'a-z':
                return nameA.localeCompare(nameB);
            case 'z-a':
                return nameB.localeCompare(nameA);
            case 'recent':
                return dateB - dateA;
            case 'oldest':
                return dateA - dateB;
            default:
                return 0;
        }
    });

    table.innerHTML = '';
    rows.forEach(row => table.appendChild(row));
});

// Search functionality
document.getElementById('search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const table = document.querySelector('.dashboard tbody');
    const rows = Array.from(table.rows);

    rows.forEach(row => {
        const name = row.cells[2].textContent.toLowerCase();
        const username = row.cells[1].textContent.toLowerCase();
        const match = name.includes(query) || username.includes(query);
        row.style.display = match ? '' : 'none';
    });
});

document.querySelector('.dashboard tbody').addEventListener('click', function(event) {
    const row = event.target.closest('tr');
    const name = row.cells[2].textContent;

    if (event.target.classList.contains('approve')) {
        alert(name + ' has been approved.');
        row.remove(); // Remove approved row from table
    } else if (event.target.classList.contains('reject')) {
        alert(name + ' has been rejected.');
        row.remove(); // Remove rejected row from table
    }
});
