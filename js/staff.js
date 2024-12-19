function showStaff(category) {
    // Hide all staff sections
    document.querySelectorAll('section[id]').forEach(section => {
        section.classList.add('d-none');
    });

    // Show the selected staff category
    document.getElementById(category).classList.remove('d-none');
}

function backToCategories() {
    // Hide all staff sections
    document.querySelectorAll('section[id]').forEach(section => {
        section.classList.add('d-none');
    });

    // Show the category selection again
    document.getElementById('view-staff').classList.remove('d-none');
}