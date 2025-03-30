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

// Approval & Rejection with fade-out message
document.querySelector('.dashboard tbody').addEventListener('click', function(event) {
    const row = event.target.closest('tr');
    const name = row.cells[2].textContent;

    if (event.target.classList.contains('approve') || event.target.classList.contains('reject')) {
        const action = event.target.classList.contains('approve') ? 'Approved' : 'Rejected';

        // Create message element
        const message = document.createElement('div');
        message.className = 'status-message';
        message.textContent = name + ' has been ' + action.toLowerCase();
        document.body.appendChild(message);

        // Fade-out effect
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => message.remove(), 1000);
        }, 2000);

        // Remove the row
        row.remove();
    }
});
